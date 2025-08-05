<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://js.djkz.top
 * @since             1.0.0
 * @package           Woo_Xcx_App
 *
 * @wordpress-plugin
 * Plugin Name:       Woo小程序/H5页面设计器
 * Plugin URI:        https://js.djkz.top
 * Description:       可视化拖拽设计各端小程序、H5、APP等页面，一键导出 uni-app 页面模板，便于HBuilderX或各平台开发工具来编译。
 * Version:           1.0.0
 * Author:            kzgzs
 * Author URI:        https://js.djkz.top/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-xcx-app
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WOO_XCX_APP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-xcx-app-activator.php
 */
function activate_woo_xcx_app() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-xcx-app-activator.php';
	Woo_Xcx_App_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-xcx-app-deactivator.php
 */
function deactivate_woo_xcx_app() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-xcx-app-deactivator.php';
	Woo_Xcx_App_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woo_xcx_app' );
register_deactivation_hook( __FILE__, 'deactivate_woo_xcx_app' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-xcx-app.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woo_xcx_app() {

	$plugin = new Woo_Xcx_App();
	$plugin->run();

}
run_woo_xcx_app();
