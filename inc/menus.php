<?php
/**
 * Menu structure and rendering for Mia Aesthetics theme.
 *
 * Defines the main navigation menu data structure and rendering entry point.
 * All menu sections and items are defined here for consistency and maintainability.
 */
/**
 * Returns the main menu data structure for all procedures and sections.
 * Centralizes menu definitions to avoid duplication and simplify updates.
 *
 * @return array Main menu structure
 */
function get_mia_menu_structure() {
    return [
        'procedures' => [
            'title' => 'Procedures',
            'url' => home_url('/cosmetic-plastic-surgery/'),
            'sections' => [
                'body' => [
                    'title' => 'Body',
                    'url' => home_url('/cosmetic-plastic-surgery/body/'),
                    'items' => [
                        ['title' => 'Mia Waist Corset™', 'slug' => 'mia-corset'],
                        ['title' => 'Awake Lipo', 'slug' => 'awake-liposuction'],
                        ['title' => 'Body Lift', 'slug' => 'circumferential-body-lift'],
                        ['title' => 'Brazilian Butt Lift (BBL)', 'slug' => 'brazilian-butt-lift-bbl'],
                        ['title' => 'Lipo 360', 'slug' => 'lipo-360'],
                        ['title' => 'Liposuction', 'slug' => 'liposuction'],
                        ['title' => 'Tummy Tuck', 'slug' => 'tummy-tuck'],
                        ['title' => 'Mommy Makeover', 'slug' => 'mommy-makeover'],
                        ['title' => 'Arm Lift', 'slug' => 'arm-lift'],
                        ['title' => 'Thigh Lift', 'slug' => 'thigh-lift'],
                        ['title' => 'Vaginal Rejuvenation', 'slug' => 'labiaplasty-labia-reduction-vaginal-rejuvenation'],
                    ]
                ],
                'breast' => [
                    'title' => 'Breast',
                    'url' => home_url('/cosmetic-plastic-surgery/breast/'),
                    'items' => [
                        ['title' => 'Breast Augmentation', 'slug' => 'augmentation-implants'],
                        ['title' => 'Breast Reduction', 'slug' => 'reduction'],
                        ['title' => 'Breast Lift', 'slug' => 'lift'],
                        ['title' => 'Breast Implant Revision', 'slug' => 'implant-revision-surgery'],
                    ]
                ],
                'face' => [
                    'title' => 'Face',
                    'url' => home_url('/cosmetic-plastic-surgery/face/'),
                    'items' => [
                        ['title' => 'Brow Lift', 'slug' => 'brow-lift'],
                        ['title' => 'Buccal Fat Removal', 'slug' => 'buccal-cheek-fat-removal'],
                        ['title' => 'Blepharoplasty', 'slug' => 'eyelid-lift-blepharoplasty'],
                        ['title' => 'Chin Lipo', 'slug' => 'chin-lipo'],
                        ['title' => 'Facelift', 'slug' => 'facelift'],
                        ['title' => 'Mini Facelift', 'slug' => 'mini-facelift'],
                        ['title' => 'Neck Lift', 'slug' => 'neck-lift'],
                        ['title' => 'Otoplasty', 'slug' => 'ear-pinning-otoplasty'],
                        ['title' => 'Rhinoplasty', 'slug' => 'nose-job-rhinoplasty'],
                    ]
                ],
                'men' => [
                    'title' => 'Men',
                    'url' => home_url('#'),
                    'items' => [
                        ['title' => 'Male BBL', 'slug' => 'male-bbl', 'parent' => 'body'],
                        ['title' => 'Male Breast Procedures', 'slug' => 'male-breast-procedures', 'parent' => 'breast'],
                        ['title' => 'Male Liposuction', 'slug' => 'male-liposuction', 'parent' => 'body'],
                        ['title' => 'Male Tummy Tuck', 'slug' => 'male-tummy-tuck', 'parent' => 'body'],
                    ]
                ]
            ]
        ],
        'non-surgical' => [
            'title' => 'Non-Surgical',
            'url' => home_url('/non-surgical/'),
            'categories' => [
                'injectable' => 'Injectable Treatments',
                'skin' => 'Skin Treatments', 
                'body' => 'Body Contouring',
                'wellness' => 'Wellness Treatments'
            ]
        ]
    ];
}

/**
 * Renders the main navigation menu for desktop or mobile.
 * Delegates to section-specific renderers as needed.
 *
 * @param string $type 'desktop' or 'mobile'
 */
function render_mia_menu($type = 'desktop') {
    $menu = get_mia_menu_structure();
    $is_mobile = $type === 'mobile';

    foreach ($menu as $key => $section) {
        if ($key === 'procedures') {
            render_procedures_menu($section, $is_mobile);
        } elseif ($key === 'non-surgical') {
            render_non_surgical_menu($is_mobile);
        }
        // Add other menu sections here as needed.
    }
}

// (Other menu rendering functions are defined in inc/menu-helpers.php)