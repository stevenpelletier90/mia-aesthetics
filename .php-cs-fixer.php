<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__)
    ->exclude([
        'vendor',
        'node_modules',
        'theme-bundle',
        'assets',
    ])
    ->name('*.php')
    ->notPath('rector.php')
    ->notPath('phpinsights.php');

return (new Config())
    ->setRules([
        // Keep this minimal and WP-friendly: let PHPCS/WPCS be the source of truth.
        '@PSR12' => false,
        // Enforce long array syntax per WordPress standards.
        'array_syntax' => ['syntax' => 'long'],
        // Harmless whitespace cleanups.
        'no_trailing_whitespace' => true,
        'no_trailing_whitespace_in_comment' => true,
        'single_blank_line_at_eof' => true,
        'concat_space' => ['spacing' => 'one'],
        // Disable potentially conflicting structural rules; handled by WPCS instead.
        'blank_line_after_opening_tag' => false,
        'function_declaration' => false,
        'method_argument_space' => false,
        'no_spaces_around_offset' => false,
        'trim_array_spaces' => false,
        'whitespace_after_comma_in_array' => false,
        // WordPress specific: keep these disabled here; WPCS will enforce.
        'yoda_style' => false,
        'declare_strict_types' => false,
        'class_definition' => false,
        'no_unused_imports' => true,
    ])
    ->setFinder($finder)
    ->setUsingCache(false);
