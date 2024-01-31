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
     * @dataProvider providerSvgIcons
     * @param string $icon
     * @param array<string, mixed> $options
     * @param string $expected
     */
    public function testFontAwesomeIcon(string $icon, array $options, string $expected): void
    {
        $outputWithChangedComment = trim(preg_replace('/<!--!(\s)?[\w\s:\/.@,\-()]+(\s)?-->/', '<!--! https://fontawesome.com License -->', $this->extension->fontAwesomeIcon($icon, $options)) ?? '');

        self::assertSame($expected, $outputWithChangedComment);
    }

    /**
     * @return array<int, array<int, array{0: string, 1: array<string, mixed>, 2: string}>>
     */
    public function providerSvgIcons(): array
    {
        return [
            [
                'house',
                [],
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="fa-icon" role="img" aria-hidden="true"><!--! https://fontawesome.com License --><path d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z" fill="currentColor"></path></svg>'
            ],
            [
                'house',
                ['color' => 'green'],
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="fa-icon" role="img" aria-hidden="true"><!--! https://fontawesome.com License --><path d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z" fill="green"></path></svg>'
            ],
//            [
//                'house',
//                ['title' => 'This is a house'],
//                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="fa-icon" role="img" aria-hidden="true"><!--! https://fontawesome.com License --><path d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z" fill="green"></path></svg>'
//            ],

            [
                'house',
                ['class' => 'my-icon', 'data-tooltip' => 'Awesome'],
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="fa-icon my-icon" role="img" aria-hidden="true" data-tooltip="Awesome"><!--! https://fontawesome.com License --><path d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z" fill="currentColor"></path></svg>'
            ],
        ];
    }
}
