<?php

/*
 * Font Awesome SVG Twig Bundle
 *
 * (c) Xenbyte, Stefan Brauner <info@xenbyte.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Xenbyte\FontAwesomeSvgTwigBundle\Twig;

use Random\RandomException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 *  FontAwesome SVG Twig Extension.
 *
 *  Usage examples:
 *
 * ```
 *  {{ fa("home") }}
 *  {{ fa("fas-home") }}
 *  {{ fa("home", {style: solid) }}
 *  {{ fa("home", {style: 'regular', color: '#330000', size: '2rem', class: 'icon') }}
 *  {{ fa("home", {style: 'duotone', color: '#333', secondaryColor: '#999', 'title': 'Title') }}
 * ```
 */
final class SvgExtension extends AbstractExtension
{
    public function __construct(private readonly string $projectDir)
    {
        // TODO: Options for default class, custom path
    }

    /**
     * @return array<TwigFunction>
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('fa', [$this, 'fontAwesomeIcon'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Gibt ein FontAwesome-Icon zurück.
     *
     * @param array{style?: string, color?: string, secondaryColor?: string, class?: string, title?: string, size?: string} $options
     */
    public function fontAwesomeIcon(string $icon, array $options = []): string
    {
        $style = $this->getIconStyle($icon, $options);
        $iconDocument = $this->getIconXml($icon, $style);

        /** @var \DOMElement $svgRoot */
        $svgRoot = $iconDocument->getElementsByTagName('svg')->item(0);

        if (\array_key_exists('class', $options)) {
            $svgRoot->setAttribute('class', 'fa-icon ' . $options['class']);
        }
        $svgRoot->setAttribute('role', 'img');

        /** @var \DOMElement $primaryPath */
        $primaryPath = $svgRoot->getElementsByTagName('path')->item(0);
        $firstPath = $primaryPath;

        /* @var \DOMElement $primaryPath */
        if ('duotone' === $style) {
            $finder = new \DOMXPath($iconDocument);

            if (\array_key_exists('secondaryColor', $options)) {
                $secondaryPath = $finder->query('//*[name()="path" and @class="fa-secondary"]');
                if ($secondaryPath->count() > 0) {
                    /** @var \DOMElement $secondary */
                    $secondary = $secondaryPath->item(0);
                    $secondary->setAttribute('fill', $options['secondaryColor']);
                }
            }

            /** @var \DOMElement $primaryPath */
            $primaryPath = $finder->query('//*[name()="path" and @class="fa-primary"]')->item(0);
        }

        if (\array_key_exists('title', $options)) {
            try {
                $random = bin2hex(random_bytes(3));
            } catch (RandomException) {
                $random = time();
            }
            $id = $style . '-' . $icon . '-' . $random . '-title';

            try {
                $titleNode = $iconDocument->createElement('title');
                $titleNode->setAttribute('id', $id);
                $titleNode->textContent = $options['title'];

                $firstPath->parentNode->insertBefore($titleNode, $firstPath);
                $svgRoot->setAttribute('aria-labelledby', $id);
            } catch (\DOMException) {
            }
        } else {
            // adds aria-hidden="true" role="img" to the svg element, if decorative
            $svgRoot->setAttribute('aria-hidden', 'true');
        }

        if (\array_key_exists('color', $options)) {
            // sets the color for the first path element
            $primaryPath->setAttribute('fill', $options['color']);
        } else {
            $primaryPath->setAttribute('fill', 'currentColor');
        }

        if (\array_key_exists('size', $options)) {
            // sets the color for the first path element
            $svgRoot->setAttribute('style', 'height:' . $options['size']);
        }

        return $iconDocument->saveHTML();
    }

    private function getIconFile(string $icon, string $style): string
    {
        $prefix = explode('-', $icon)[0] ?? 'fa';
        if (\in_array($prefix ?? 'fa', ['fab', 'fad', 'far', 'fal', 'fas'], true)) {
            $icon = str_replace($prefix . '-', '', $icon);
        }

        // Check assets-directory
        $path = $this->projectDir . '/assets/font-awesome/' . $style . '/' . $icon . '.svg';
        if (file_exists($path)) {
            return $path;
        }

        // Check vendor-directory of `fortawesome/font-awesome` (does not contains pro icons)
        $path = $this->projectDir . '/vendor/fortawesome/font-awesome/svgs/' . $style . '/' . $icon . '.svg';
        if (file_exists($path)) {
            return $path;
        }

        // no icons found
        throw new \RuntimeException('FontAwesome icon "' . $icon . '" not found.');
    }

    private function getIconXml(string $icon, string $style): \DOMDocument
    {
        $content = file_get_contents($this->getIconFile($icon, $style));

        $iconDocument = new \DOMDocument();
        $iconDocument->loadXML($content);

        return $iconDocument;
    }

    /**
     * @param array{style?: string, color?: string, secondaryColor?: string, class?: string, title?: string} $options
     */
    private function getIconStyle(string $icon, array $options): string
    {
        if (!\array_key_exists('style', $options)) {
            $prefix = explode('-', $icon);

            return match ($prefix[0] ?? 'fa') {
                'fab' => 'brands',
                'fad' => 'duotone',
                'far' => 'regular',
                'fal' => 'light',
                'fat' => 'thin',
                default => 'solid',
            };
        }

        $style = $options['style'];
        if (!\in_array($style,
            [
                'brands',
                'duotone',
                'light',
                'regular',
                'sharp-light',
                'sharp-regular',
                'sharp-solid',
                'sharp-thin',
                'solid',
                'thin',
            ], true
        )) {
            throw new \RuntimeException('Invalid FontAwesome style "' . $style . '.');
        }

        return $style;
    }
}
