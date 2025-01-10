<?php

declare(strict_types=1);

return (new \PhpCsFixer\Config())
    ->setCacheFile(__DIR__ . '/build/.php-cs-fixer.php')
    ->setFinder(
        \PhpCsFixer\Finder::create()
            ->in([
                __DIR__ . '/src',
                __DIR__ . '/tests',
            ])
            ->append([
                __FILE__,
            ])
    )
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        '@PSR12:risky' => true,
        '@PHP81Migration' => true,
        '@PHP81Migration:risky' => true,
        '@PHPUnit100Migration:risky' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,

        'blank_line_before_statement' => [
            'statements' => [
                'continue',
                'declare',
                'default',
                'return',
                'throw',
                'try',
            ],
        ],
        'date_time_immutable' => true,
        'no_unused_imports' => true,
        'final_class' => true,
        'fopen_flags' => ['b_mode' => true],
        'concat_space' => ['spacing' => 'one'],
        'binary_operator_spaces' => false,
        'php_unit_strict' => false,
        'phpdoc_to_comment' => false,
        'phpdoc_align' => false,
        'phpdoc_var_without_name' => false,
        'phpdoc_types_order' => ['null_adjustment' => 'always_last'],
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public_static',
                'property_protected_static',
                'property_private_static',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
                'phpunit',
                'method_public_static',
                'method_protected_static',
                'method_private_static',
                'magic',
                'method_public',
                'method_protected',
                'method_private',
            ],
        ],
        'php_unit_test_case_static_method_calls' => ['call_type' => 'this'],
        'ordered_imports' => ['imports_order' => ['class', 'function', 'const']],
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
    ]);
