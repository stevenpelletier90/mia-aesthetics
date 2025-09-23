<?php
/**
 * Debug helpers for tracking loaded files and assets
 */

if (!defined('ABSPATH')) {
    exit;
}

class MiaDebugHelper {
    private static $loaded_files = [];
    private static $enqueued_styles = [];
    private static $enqueued_scripts = [];
    private static $template_files = [];

    public static function init() {
        if (!self::is_debug_enabled()) {
            return;
        }

        add_action('wp_enqueue_scripts', [__CLASS__, 'track_enqueued_assets'], 999);
        add_action('wp_footer', [__CLASS__, 'display_debug_info'], 999);
        add_action('template_include', [__CLASS__, 'track_template'], 999);

        // Track included files
        register_shutdown_function([__CLASS__, 'track_included_files']);
    }

    public static function is_debug_enabled() {
        return defined('WP_DEBUG') && WP_DEBUG &&
               (current_user_can('administrator') ||
                isset($_GET['mia_debug']) && $_GET['mia_debug'] === 'true');
    }

    public static function track_template($template) {
        self::$template_files[] = str_replace(get_template_directory(), '', $template);
        return $template;
    }

    public static function track_enqueued_assets() {
        global $wp_styles, $wp_scripts;

        // Track styles
        foreach ($wp_styles->queue as $handle) {
            if (isset($wp_styles->registered[$handle])) {
                $style = $wp_styles->registered[$handle];
                self::$enqueued_styles[] = [
                    'handle' => $handle,
                    'src' => $style->src,
                    'deps' => $style->deps,
                    'ver' => $style->ver
                ];
            }
        }

        // Track scripts
        foreach ($wp_scripts->queue as $handle) {
            if (isset($wp_scripts->registered[$handle])) {
                $script = $wp_scripts->registered[$handle];
                self::$enqueued_scripts[] = [
                    'handle' => $handle,
                    'src' => $script->src,
                    'deps' => $script->deps,
                    'ver' => $script->ver,
                    'in_footer' => isset($script->extra['group']) && $script->extra['group'] === 1
                ];
            }
        }
    }

    public static function track_included_files() {
        $included_files = get_included_files();
        $theme_dir = get_template_directory();

        foreach ($included_files as $file) {
            if (strpos($file, $theme_dir) === 0) {
                $relative_path = str_replace($theme_dir, '', $file);
                if (!in_array($relative_path, self::$loaded_files)) {
                    self::$loaded_files[] = $relative_path;
                }
            }
        }
    }

    public static function display_debug_info() {
        if (!self::is_debug_enabled()) {
            return;
        }

        // Get current template info
        global $template;
        $current_template = $template ? basename($template) : 'Unknown';

        ?>
        <div id="mia-debug-panel" style="
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            height: 100vh;
            background: #1a1a1a;
            color: #fff;
            z-index: 999999;
            overflow-y: auto;
            transition: right 0.3s ease;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            box-shadow: -2px 0 10px rgba(0,0,0,0.3);
        ">
            <div style="padding: 20px;">
                <h3 style="margin: 0 0 20px 0; color: #00d4aa; border-bottom: 1px solid #333; padding-bottom: 10px;">
                    🔧 Mia Debug Panel
                </h3>

                <div style="margin-bottom: 20px;">
                    <h4 style="color: #ff6b6b; margin: 0 0 10px 0;">📄 Current Template</h4>
                    <div style="background: #2a2a2a; padding: 10px; border-radius: 4px; font-size: 11px;">
                        <strong><?php echo esc_html($current_template); ?></strong>
                        <?php
                        // Show WordPress reading settings context
                        $front_page_id = get_option('page_on_front');
                        $blog_page_id = get_option('page_for_posts');

                        if ($front_page_id && $blog_page_id) {
                            echo '<br><small style="color: #00d4aa;">Front Page: ' . esc_html(get_the_title($front_page_id)) . ' (ID: ' . $front_page_id . ')</small>';
                            echo '<br><small style="color: #4ecdc4;">Posts Page: ' . esc_html(get_the_title($blog_page_id)) . ' (ID: ' . $blog_page_id . ')</small>';

                            if (is_home()) {
                                echo '<br><small style="color: #ffe66d;">🏠 This is the Posts/Blog page</small>';
                            } elseif (is_front_page()) {
                                echo '<br><small style="color: #ffe66d;">🏠 This is the Front page</small>';
                            }
                        } elseif ($front_page_id) {
                            echo '<br><small style="color: #00d4aa;">Front Page: ' . esc_html(get_the_title($front_page_id)) . ' (ID: ' . $front_page_id . ')</small>';
                            echo '<br><small style="color: #ff6b6b;">Posts Page: Latest posts on homepage</small>';
                        } else {
                            echo '<br><small style="color: #ff6b6b;">Using default WordPress setup</small>';
                        }

                        // Show current page info
                        global $wp_query;
                        if (is_page()) {
                            echo '<br><small style="color: #ff9ff3;">Current Page ID: ' . get_the_ID() . '</small>';
                        }
                        ?>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <h4 style="color: #4ecdc4; margin: 0 0 10px 0;">
                        🎨 CSS Files (<?php echo count(self::$enqueued_styles); ?>)
                    </h4>
                    <div style="max-height: 200px; overflow-y: auto; background: #2a2a2a; padding: 10px; border-radius: 4px;">
                        <?php foreach (self::$enqueued_styles as $style): ?>
                            <div style="margin-bottom: 8px; padding: 5px; background: #333; border-radius: 3px;">
                                <strong style="color: #00d4aa;"><?php echo esc_html($style['handle']); ?></strong><br>
                                <small style="color: #ccc;">
                                    <?php echo esc_html(self::format_asset_path($style['src'])); ?>
                                </small>
                                <?php if (!empty($style['deps'])): ?>
                                    <br><small style="color: #ff9ff3;">Deps: <?php echo esc_html(implode(', ', $style['deps'])); ?></small>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <h4 style="color: #ffe66d; margin: 0 0 10px 0;">
                        ⚡ JS Files (<?php echo count(self::$enqueued_scripts); ?>)
                    </h4>
                    <div style="max-height: 200px; overflow-y: auto; background: #2a2a2a; padding: 10px; border-radius: 4px;">
                        <?php foreach (self::$enqueued_scripts as $script): ?>
                            <div style="margin-bottom: 8px; padding: 5px; background: #333; border-radius: 3px;">
                                <strong style="color: #00d4aa;"><?php echo esc_html($script['handle']); ?></strong>
                                <?php if ($script['in_footer']): ?>
                                    <span style="color: #ff6b6b; font-size: 10px;">[FOOTER]</span>
                                <?php endif; ?>
                                <br>
                                <small style="color: #ccc;">
                                    <?php echo esc_html(self::format_asset_path($script['src'])); ?>
                                </small>
                                <?php if (!empty($script['deps'])): ?>
                                    <br><small style="color: #ff9ff3;">Deps: <?php echo esc_html(implode(', ', $script['deps'])); ?></small>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <h4 style="color: #ff9ff3; margin: 0 0 10px 0;">
                        🐘 PHP Files (<?php echo count(self::$loaded_files); ?>)
                    </h4>
                    <div style="max-height: 200px; overflow-y: auto; background: #2a2a2a; padding: 10px; border-radius: 4px;">
                        <?php foreach (self::$loaded_files as $file): ?>
                            <div style="margin-bottom: 3px; color: #ccc; font-size: 11px;">
                                <?php echo esc_html($file); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <button id="mia-debug-toggle" style="
            position: fixed;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            background: #00d4aa;
            color: #1a1a1a;
            border: none;
            padding: 10px 5px;
            border-radius: 4px 0 0 4px;
            cursor: pointer;
            z-index: 1000000;
            font-weight: bold;
            writing-mode: vertical-lr;
            text-orientation: mixed;
        ">DEBUG</button>

        <script>
        (function() {
            var panel = document.getElementById('mia-debug-panel');
            var toggle = document.getElementById('mia-debug-toggle');
            var isOpen = false;

            toggle.addEventListener('click', function() {
                if (isOpen) {
                    panel.style.right = '-400px';
                    toggle.style.right = '10px';
                    toggle.textContent = 'DEBUG';
                } else {
                    panel.style.right = '0';
                    toggle.style.right = '410px';
                    toggle.textContent = 'CLOSE';
                }
                isOpen = !isOpen;
            });

            // Close on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && isOpen) {
                    toggle.click();
                }
            });
        })();
        </script>
        <?php
    }

    private static function format_asset_path($src) {
        if (empty($src)) {
            return '[Inline/Generated CSS]';
        }

        // Handle WordPress-generated URLs
        if (strpos($src, 'ver=') !== false) {
            $src = strtok($src, '?'); // Remove query parameters for cleaner display
        }

        // Remove domain and make relative to theme
        $src = str_replace(home_url(), '', $src);
        $theme_uri = get_template_directory_uri();
        $theme_path = str_replace(home_url(), '', $theme_uri);

        if (strpos($src, $theme_path) === 0) {
            return str_replace($theme_path, '', $src);
        }

        // Handle WordPress core assets
        if (strpos($src, '/wp-includes/') !== false) {
            return '[WP Core] ' . basename($src);
        }

        if (strpos($src, '/wp-content/') !== false) {
            return '[WP Content] ' . str_replace('/wp-content/', '', $src);
        }

        return $src;
    }
}

// Initialize debug helper
MiaDebugHelper::init();