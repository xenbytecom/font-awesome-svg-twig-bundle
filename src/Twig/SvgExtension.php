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
     * Inline svg output of the Font Awesome icon.
     *
     * @param array{style?: string, color?: string, secondaryColor?: string, class?: string, title?: string, size?: string} $options
     */
    public function fontAwesomeIcon(string $icon, array $options = []): false|string
    {
        $style = $this->getIconStyle($icon, $options);
        $iconName = $this->getIconName($icon);

        $iconDocument = $this->getIconXml($iconName, $style);

        /** @var \DOMElement $svgRoot */
        $svgRoot = $iconDocument->getElementsByTagName('svg')->item(0);

        if (\array_key_exists('class', $options)) {
            $svgRoot->setAttribute('class', 'fa-icon ' . $options['class']);
        } else {
            $svgRoot->setAttribute('class', 'fa-icon');
        }

        $svgRoot->setAttribute('role', 'img');

        /** @var \DOMElement $primary */
        $primary = $svgRoot->getElementsByTagName('path')->item(0);

        /** @var \DOMElement $firstChild */
        $firstChild = $svgRoot->firstChild;

        /* @var \DOMElement $primary */
        if ('duotone' === $style) {
            // set colors for duotone icons
            $finder = new \DOMXPath($iconDocument);

            if (\array_key_exists('secondaryColor', $options)) {
                $secondaryPath = $finder->query('//*[name()="path" and @class="fa-secondary"]');
                if ($secondaryPath instanceof \DOMNodeList && $secondaryPath->count() > 0) {
                    /** @var \DOMElement $secondary */
                    $secondary = $secondaryPath->item(0);
                    $secondary->setAttribute('fill', $options['secondaryColor']);
                }
            }

            $primaryPath = $finder->query('//*[name()="path" and @class="fa-primary"]');
            if ($primaryPath instanceof \DOMNodeList && $primaryPath->count() > 0) {
                /** @var \DOMElement $primary */
                $primary = $primaryPath->item(0) ?? $primary;
            }
        }

        // adds a title element
        // @see https://developer.mozilla.org/en-US/docs/Web/SVG/Element/title
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

                $firstChild->parentNode->insertBefore($titleNode, $firstChild);
                $svgRoot->setAttribute('aria-labelledby', $id);
            } catch (\DOMException) {
            }
        } else {
            // adds aria-hidden="true" role="img" to the svg element, if decorative
            $svgRoot->setAttribute('aria-hidden', 'true');
        }

        if (\array_key_exists('color', $options)) {
            // sets the color for the first path element
            $primary->setAttribute('fill', $options['color']);
        } else {
            // default: "currentColor"
            $primary->setAttribute('fill', 'currentColor');
        }

        if (\array_key_exists('size', $options)) {
            // sets the image size
            $svgRoot->setAttribute('style', 'height:' . $options['size']);
        }

        // allow data-attributes for svg objects
        foreach ($options as $option => $value) {
            if (str_starts_with($option, 'data-')) {
                $svgRoot->setAttribute($option, $value);
            }
        }

        return $iconDocument->saveHTML();
    }

    /**
     * Search for the needed icon file.
     */
    private function getIconFile(string $icon, string $style): string
    {
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

    /**
     * Read the svg file and return it as xml document.
     */
    private function getIconXml(string $icon, string $style): \DOMDocument
    {
        $content = file_get_contents($this->getIconFile($icon, $style));
        if (!\is_string($content)) {
            throw new \RuntimeException('Cannot load content of "' . $icon . '" iocn with style "' . $style . '".');
        }

        $iconDocument = new \DOMDocument();
        $iconDocument->loadXML($content);

        return $iconDocument;
    }

    /**
     * Gets the icon style by options array or icon name.
     *
     * @param array{style?: string, color?: string, secondaryColor?: string, class?: string, title?: string} $options
     */
    private function getIconStyle(string $icon, array $options): string
    {
        // style option with higher priority
        if (\in_array($options['style'] ?? '',
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
            return $options['style'];
        }

        // style option is set, but invalid
        if (isset($options['style'])) {
            throw new \RuntimeException('Invalid FontAwesome style "' . $options['style'] . '.');
        }

        // if no style option is set, check for prefix, otherwise use "solid" as the default value
        $prefix = explode(' ', $icon);

        return match ($prefix[0] ?? '') {
            'fab' => 'brands',
            'fad' => 'duotone',
            'far' => 'regular',
            'fal' => 'light',
            'fat' => 'thin',
            default => 'solid',
        };
    }

    /**
     * Gets the name of the icon without optional style prefixes.
     */
    private function getIconName(string $icon): string
    {
        // removes the prefixes
        $icon = strtolower($icon);

        $prefix = explode(' ', $icon)[0] ?? '';
        if (\in_array($prefix, ['fab', 'fad', 'far', 'fal', 'fas'], true)) {
            $icon = str_replace($prefix . ' ', '', $icon);
        }

        // removes "fa-" in the icon name
        if (str_starts_with($icon, 'fa-')) {
            $icon = substr($icon, 3);
        }

        return $icon;
    }
}
