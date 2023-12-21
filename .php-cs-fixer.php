<?php

/*
 * Font Awesome SVG Twig Bundle
 * (c) Xenbyte - https://www.xenbyte.com/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

$header = <<<EOF
Font Awesome SVG Twig Bundle

(c) Xenbyte, Stefan Brauner <info@xenbyte.com>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

$finder = PhpCsFixer\Finder::create()
    ->files()
    ->name('*.php')
    ->in(__DIR__ . '/src')
//    ->in(__DIR__ . '/tests')->exclude('Fixture')
//    ->in(__DIR__ . '/tests/Fixture/src')
;

$config = new PhpCsFixer\Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules([
                   '@Symfony' => true,
                   '@Symfony:risky' => true,

                   'declare_strict_types' => true,
                   'strict_comparison' => true,
                   'strict_param' => true,
                   'concat_space' => ['spacing' => 'one'],

                   'nullable_type_declaration_for_default_null_value' => true,
                   'ordered_class_elements' => true,
                   'ordered_imports' => true,
                   'array_syntax' => ['syntax' => 'short'],

                   'header_comment' => ['header' => $header, 'location' => 'after_open'],

//                   'mb_str_functions' => true,
                   'phpdoc_order' => true,
//                   'phpdoc_align' => false,
//                   'phpdoc_separation' => false,
//                   'phpdoc_var_without_name' => false,
               ])
    ->setFinder($finder)
    ;
