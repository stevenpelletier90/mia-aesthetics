<?php
/**
 * Rector configuration for PHP code modernization.
 *
 * @package MiaAesthetics
 */

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
	->withPaths(
		array(
			__DIR__ . '/components',
			__DIR__ . '/inc',
			__DIR__ . '/*.php',
		)
	)
	// uncomment to reach your current PHP version.
	// ->withPhpSets().
	->withTypeCoverageLevel( 0 )
	->withDeadCodeLevel( 0 )
	->withCodeQualityLevel( 0 );
