<?php
/**
 * PHPInsights configuration for WordPress theme development.
 *
 * @package MiaAesthetics
 */

declare(strict_types=1);

use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenDefineFunctions;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenFinalClasses;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses;
use NunoMaduro\PhpInsights\Domain\Sniffs\ForbiddenSetterSniff;
use PhpCsFixer\Fixer\ClassNotation\FinalInternalClassFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\DeclareEqualNormalizeFixer;
use PhpCsFixer\Fixer\Operator\NewWithBracesFixer;
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\Operator\TernaryToNullCoalescingFixer;
use PhpCsFixer\Fixer\PhpTag\BlankLineAfterOpeningTagFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use SlevomatCodingStandard\Sniffs\Commenting\UselessInheritDocCommentSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ParameterTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\PropertyTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ReturnTypeHintSniff;

return array(

	/*
	|--------------------------------------------------------------------------
	| Default Preset
	|--------------------------------------------------------------------------
	|
	| This option controls the default preset that will be used by PHP Insights
	| to make your code reliable, simple, and clean. However, you can always
	| adjust the option by invoking the analysis command.
	|
	| Supported: "default", "laravel", "symfony", "magento2", "drupal"
	|
	*/

	'preset'       => 'default',

	/*
	|--------------------------------------------------------------------------
	| IDE
	|--------------------------------------------------------------------------
	|
	| This options allow to add hyperlinks in your terminal to quickly open
	| files in your favorite IDE while browsing your PhpInsights report.
	|
	| Supported: "textmate", "macvim", "emacs", "sublime", "phpstorm",
	| "atom", "vscode".
	|
	*/

	'ide'          => null,

	/*
	|--------------------------------------------------------------------------
	| Configuration
	|--------------------------------------------------------------------------
	|
	| Here you may adjust all the various `Insights` that will be used by PHP
	| Insights. This is the place to dig deeper into the metrics that matter
	| the most to you.
	|
	*/

	'exclude'      => array(
		'vendor',
		'node_modules',
		'theme-bundle',
		'assets',
		'rector.php',
	),

	'add'          => array(
		// ExampleMetric::class => [
		// ExampleInsight::class,
		// ].
	),

	'remove'       => array(
		// WordPress specific exclusions.
		DeclareStrictTypesFixer::class,              // WordPress doesn't use strict types.
		FinalInternalClassFixer::class,              // WordPress classes shouldn't be final.
		ForbiddenFinalClasses::class,                // WordPress classes shouldn't be final.
		ForbiddenNormalClasses::class,               // Allow normal classes for WordPress.
		BlankLineAfterOpeningTagFixer::class,        // WordPress templates don't need this.
		DeclareEqualNormalizeFixer::class,           // Not needed for WordPress.
		OrderedImportsFixer::class,                  // WordPress doesn't always use namespaces.
		ForbiddenDefineFunctions::class,             // WordPress uses constants.
		DisallowMixedTypeHintSniff::class,           // WordPress often needs mixed types.
		ParameterTypeHintSniff::class,               // WordPress functions often can't have type hints.
		PropertyTypeHintSniff::class,                // WordPress properties often can't have type hints.
		ReturnTypeHintSniff::class,                  // WordPress functions often can't have return types.
		TernaryToNullCoalescingFixer::class,         // WordPress needs PHP 7.0 compatibility.
		NewWithBracesFixer::class,                   // WordPress style.
		ArraySyntaxFixer::class,                     // WordPress uses long array() syntax.
		UselessInheritDocCommentSniff::class,        // WordPress uses inherited docs.
		ForbiddenSetterSniff::class,                 // WordPress needs setters.
		YodaStyleFixer::class,                       // WordPress requires Yoda conditions - conflicts with Style score.

		// Additional exclusions for WordPress themes.
		\PhpCsFixer\Fixer\ArrayNotation\NoWhitespaceBeforeCommaInArrayFixer::class, // WordPress array style.
		\PhpCsFixer\Fixer\ControlStructure\NoAlternativeSyntaxFixer::class,         // WordPress allows alternative syntax.
		\PhpCsFixer\Fixer\FunctionNotation\FunctionDeclarationFixer::class,         // WordPress function spacing.
		\PhpCsFixer\Fixer\Whitespace\IndentationTypeFixer::class,                   // WordPress uses tabs not spaces.
		\PhpCsFixer\Fixer\Basic\BracesPositionFixer::class,                          // WordPress brace style.
	),

	'config'       => array(
		// Config section - no line length sniff available.
	),

	/*
	|--------------------------------------------------------------------------
	| Requirements
	|--------------------------------------------------------------------------
	|
	| Here you may define a level you want to reach per `Insights` category.
	| When a score is lower than the minimum level defined, then an error
	| code will be returned. This is optional and individually defined.
	|
	*/

	'requirements' => array(
		'min-quality'      => 70,      // Lowered for WordPress themes.
		'min-complexity'   => 85,   // Keep high - your code is good here.
		'min-architecture' => 65, // Lowered - WordPress themes are more global.
		'min-style'        => 65,        // Lowered - WordPress has different style rules.
	),

	/*
	|--------------------------------------------------------------------------
	| Threads
	|--------------------------------------------------------------------------
	|
	| Here you may adjust how many threads (core) PHPInsights can use to run
	| the analysis. This is optional, should be a positive integer and
	| defaults to the number of CPU cores minus 1.
	|
	*/

	'threads'      => null,

	/*
	|--------------------------------------------------------------------------
	| Timeout
	|--------------------------------------------------------------------------
	| Here you may adjust the timeout (in seconds) for PHPInsights to run
	| before cancelling the analysis. This is optional, should be a positive
	| integer and defaults to 60 seconds.
	|
	*/

	'timeout'      => 120, // Increased timeout for larger codebase.
);
