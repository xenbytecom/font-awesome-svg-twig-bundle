<?php

declare(strict_types=1);

namespace Xenbyte\FontAwesomeSvgTwigBundle\Tests\Twig;

use Xenbyte\FontAwesomeSvgTwigBundle\Twig\SvgExtension;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Xenbyte\FontAwesomeSvgTwigBundle\Twig\SvgExtension
 */
class SvgExtensionTest extends TestCase
{
    private SvgExtension $extension;

    protected function setUp(): void
    {
        $this->extension = new SvgExtension('/application', '%kernel.project_dir%/../vendor/fortawesome/font-awesome', 'fa-icon');
    }

    /**
     * @covers \Xenbyte\FontAwesomeSvgTwigBundle\Twig\SvgExtension
     * @param string $icon
     * @param array{style?: string, color?: string, secondaryColor?: string, class?: string, title?: string, size?: string} $options
     * @param string $expected
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('providerSvgIcons')]
    public function testFontAwesomeIcon(string $icon, array $options, string $expected): void
    {
        $svg = $this->extension->fontAwesomeIcon($icon, $options);
        self::assertIsString($svg);
        $outputWithChangedComment = preg_replace('/<!--!(\s)?[\w\s:\/.@,\-()]+(\s)?-->/', '<!--! https://fontawesome.com License -->', $svg);
        self::assertIsString($outputWithChangedComment);
        $outputWithChangedComment = trim($outputWithChangedComment);

        // Normalize title IDs for comparison
        $outputWithNormalizedId = preg_replace('/id="[a-z-]+-[a-f0-9]{6}-title"/', 'id="solid-house-random-title"', $outputWithChangedComment);
        self::assertIsString($outputWithNormalizedId);
        $outputWithNormalizedId = preg_replace('/aria-labelledby="[a-z-]+-[a-f0-9]{6}-title"/', 'aria-labelledby="solid-house-random-title"', $outputWithNormalizedId);
        self::assertIsString($outputWithNormalizedId);

        self::assertSame($expected, $outputWithNormalizedId);
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    public static function providerSvgIcons(): array
    {
        return [
            [
                'house',
                [],
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="fa-icon" role="img" aria-hidden="true"><!--! https://fontawesome.com License --><path fill="currentColor" d="M277.8 8.6c-12.3-11.4-31.3-11.4-43.5 0l-224 208c-9.6 9-12.8 22.9-8 35.1S18.8 272 32 272l16 0 0 176c0 35.3 28.7 64 64 64l288 0c35.3 0 64-28.7 64-64l0-176 16 0c13.2 0 25-8.1 29.8-20.3s1.6-26.2-8-35.1l-224-208zM240 320l32 0c26.5 0 48 21.5 48 48l0 96-128 0 0-96c0-26.5 21.5-48 48-48z"></path></svg>'
            ],
            [
                'house',
                ['color' => 'green'],
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="fa-icon" role="img" aria-hidden="true"><!--! https://fontawesome.com License --><path fill="green" d="M277.8 8.6c-12.3-11.4-31.3-11.4-43.5 0l-224 208c-9.6 9-12.8 22.9-8 35.1S18.8 272 32 272l16 0 0 176c0 35.3 28.7 64 64 64l288 0c35.3 0 64-28.7 64-64l0-176 16 0c13.2 0 25-8.1 29.8-20.3s1.6-26.2-8-35.1l-224-208zM240 320l32 0c26.5 0 48 21.5 48 48l0 96-128 0 0-96c0-26.5 21.5-48 48-48z"></path></svg>'
            ],
            [
                'house',
                ['title' => 'This is a house'],
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="fa-icon" role="img" aria-labelledby="solid-house-random-title"><title id="solid-house-random-title">This is a house</title><!--! https://fontawesome.com License --><path fill="currentColor" d="M277.8 8.6c-12.3-11.4-31.3-11.4-43.5 0l-224 208c-9.6 9-12.8 22.9-8 35.1S18.8 272 32 272l16 0 0 176c0 35.3 28.7 64 64 64l288 0c35.3 0 64-28.7 64-64l0-176 16 0c13.2 0 25-8.1 29.8-20.3s1.6-26.2-8-35.1l-224-208zM240 320l32 0c26.5 0 48 21.5 48 48l0 96-128 0 0-96c0-26.5 21.5-48 48-48z"></path></svg>'
            ],
            [
                'house',
                ['size' => '2rem'],
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="fa-icon" role="img" aria-hidden="true" style="height:2rem"><!--! https://fontawesome.com License --><path fill="currentColor" d="M277.8 8.6c-12.3-11.4-31.3-11.4-43.5 0l-224 208c-9.6 9-12.8 22.9-8 35.1S18.8 272 32 272l16 0 0 176c0 35.3 28.7 64 64 64l288 0c35.3 0 64-28.7 64-64l0-176 16 0c13.2 0 25-8.1 29.8-20.3s1.6-26.2-8-35.1l-224-208zM240 320l32 0c26.5 0 48 21.5 48 48l0 96-128 0 0-96c0-26.5 21.5-48 48-48z"></path></svg>'
            ],
            [
                'house',
                ['class' => 'my-icon', 'data-tooltip' => 'Awesome'],
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="fa-icon my-icon" role="img" aria-hidden="true" data-tooltip="Awesome"><!--! https://fontawesome.com License --><path fill="currentColor" d="M277.8 8.6c-12.3-11.4-31.3-11.4-43.5 0l-224 208c-9.6 9-12.8 22.9-8 35.1S18.8 272 32 272l16 0 0 176c0 35.3 28.7 64 64 64l288 0c35.3 0 64-28.7 64-64l0-176 16 0c13.2 0 25-8.1 29.8-20.3s1.6-26.2-8-35.1l-224-208zM240 320l32 0c26.5 0 48 21.5 48 48l0 96-128 0 0-96c0-26.5 21.5-48 48-48z"></path></svg>'
            ],
            [
                'far address-book',
                ['color' => 'blue'],
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="fa-icon" role="img" aria-hidden="true"><!--! https://fontawesome.com License --><path fill="blue" d="M384 48c8.8 0 16 7.2 16 16l0 384c0 8.8-7.2 16-16 16L96 464c-8.8 0-16-7.2-16-16L80 64c0-8.8 7.2-16 16-16l288 0zM96 0C60.7 0 32 28.7 32 64l0 384c0 35.3 28.7 64 64 64l288 0c35.3 0 64-28.7 64-64l0-384c0-35.3-28.7-64-64-64L96 0zM240 248a56 56 0 1 0 0-112 56 56 0 1 0 0 112zm-32 40c-44.2 0-80 35.8-80 80 0 8.8 7.2 16 16 16l192 0c8.8 0 16-7.2 16-16 0-44.2-35.8-80-80-80l-64 0zM512 80c0-8.8-7.2-16-16-16s-16 7.2-16 16l0 64c0 8.8 7.2 16 16 16s16-7.2 16-16l0-64zM496 192c-8.8 0-16 7.2-16 16l0 64c0 8.8 7.2 16 16 16s16-7.2 16-16l0-64c0-8.8-7.2-16-16-16zm16 144c0-8.8-7.2-16-16-16s-16 7.2-16 16l0 64c0 8.8 7.2 16 16 16s16-7.2 16-16l0-64z"></path></svg>'
            ],
            [
                'circle-check',
                ['class' => 'fa-circle-check'],
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="fa-icon fa-circle-check" role="img" aria-hidden="true"><!--! https://fontawesome.com License --><path fill="currentColor" d="M256 512a256 256 0 1 1 0-512 256 256 0 1 1 0 512zM374 145.7c-10.7-7.8-25.7-5.4-33.5 5.3L221.1 315.2 169 263.1c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l72 72c5 5 11.8 7.5 18.8 7s13.4-4.1 17.5-9.8L379.3 179.2c7.8-10.7 5.4-25.7-5.3-33.5z"></path></svg>'
            ],
            [
                'circle-check',
                ['class' => 'fa-circle-check', 'title' => 'ja'],
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="fa-icon fa-circle-check" role="img" aria-labelledby="solid-house-random-title"><title id="solid-house-random-title">ja</title><!--! https://fontawesome.com License --><path fill="currentColor" d="M256 512a256 256 0 1 1 0-512 256 256 0 1 1 0 512zM374 145.7c-10.7-7.8-25.7-5.4-33.5 5.3L221.1 315.2 169 263.1c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l72 72c5 5 11.8 7.5 18.8 7s13.4-4.1 17.5-9.8L379.3 179.2c7.8-10.7 5.4-25.7-5.3-33.5z"></path></svg>'
            ],
        ];
    }
}