<?php
/**
 * US state → abbreviation lookup table and helper.
 *
 * Provides a static map and a function to convert full state names to abbreviations.
 * Used in navigation menus and location displays.
 * Keeping as a constant avoids recreating the array on every call.
 *
 * @package Mia_Aesthetics
 */

if ( ! defined( 'MIA_AESTHETICS_STATE_ABBREVIATIONS' ) ) {
	define(
		'MIA_AESTHETICS_STATE_ABBREVIATIONS',
		array(
			'Alabama'              => 'AL',
			'Alaska'               => 'AK',
			'Arizona'              => 'AZ',
			'Arkansas'             => 'AR',
			'California'           => 'CA',
			'Colorado'             => 'CO',
			'Connecticut'          => 'CT',
			'Delaware'             => 'DE',
			'Florida'              => 'FL',
			'Georgia'              => 'GA',
			'Hawaii'               => 'HI',
			'Idaho'                => 'ID',
			'Illinois'             => 'IL',
			'Indiana'              => 'IN',
			'Iowa'                 => 'IA',
			'Kansas'               => 'KS',
			'Kentucky'             => 'KY',
			'Louisiana'            => 'LA',
			'Maine'                => 'ME',
			'Maryland'             => 'MD',
			'Massachusetts'        => 'MA',
			'Michigan'             => 'MI',
			'Minnesota'            => 'MN',
			'Mississippi'          => 'MS',
			'Missouri'             => 'MO',
			'Montana'              => 'MT',
			'Nebraska'             => 'NE',
			'Nevada'               => 'NV',
			'New Hampshire'        => 'NH',
			'New Jersey'           => 'NJ',
			'New Mexico'           => 'NM',
			'New York'             => 'NY',
			'North Carolina'       => 'NC',
			'North Dakota'         => 'ND',
			'Ohio'                 => 'OH',
			'Oklahoma'             => 'OK',
			'Oregon'               => 'OR',
			'Pennsylvania'         => 'PA',
			'Rhode Island'         => 'RI',
			'South Carolina'       => 'SC',
			'South Dakota'         => 'SD',
			'Tennessee'            => 'TN',
			'Texas'                => 'TX',
			'Utah'                 => 'UT',
			'Vermont'              => 'VT',
			'Virginia'             => 'VA',
			'Washington'           => 'WA',
			'West Virginia'        => 'WV',
			'Wisconsin'            => 'WI',
			'Wyoming'              => 'WY',
			'District of Columbia' => 'DC',
		)
	);
}

/**
 * Helper: Get US state abbreviation from full name
 *
 * Used in navigation menus to display location state abbreviations
 *
 * @param string $state Full state name.
 * @return string State abbreviation or original string if not found
 */
function mia_aesthetics_get_state_abbr( $state ) {
	if ( class_exists( 'WP_State' ) ) {
		$abbr = WP_State::abbr( $state );
		return $abbr ? $abbr : $state;
	}

	// Fallback: constant map for legacy support.
	if ( defined( 'MIA_AESTHETICS_STATE_ABBREVIATIONS' ) && isset( MIA_AESTHETICS_STATE_ABBREVIATIONS[ $state ] ) ) {
		return MIA_AESTHETICS_STATE_ABBREVIATIONS[ $state ];
	}

	return $state;
}
