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
        $outputWithChangedComment = trim(preg_replace('/<!--!(\s)?[\w\s:\/.@,\-()]+(\s)?-->/', '<!--! https://fontawesome.com License -->', $svg) ?? '');

        // Normalize title IDs for comparison
        $outputWithNormalizedId = preg_replace('/id="solid-house-[a-f0-9]{6}-title"/', 'id="solid-house-random-title"', $outputWithChangedComment);
        $outputWithNormalizedId = preg_replace('/aria-labelledby="solid-house-[a-f0-9]{6}-title"/', 'aria-labelledby="solid-house-random-title"', $outputWithNormalizedId);

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
        ];
    }
}