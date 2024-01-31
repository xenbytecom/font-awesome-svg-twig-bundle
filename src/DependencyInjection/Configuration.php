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

namespace Xenbyte\FontAwesomeSvgTwigBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('font_awesome_svg_twig');

        $treeBuilder->getRootNode()
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('icon_folder')
                    ->defaultValue('%kernel.project_dir%/../vendor/fortawesome/font-awesome')
                ->end()
                ->scalarNode('svg_class')
                    ->defaultValue('fa-icon')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
