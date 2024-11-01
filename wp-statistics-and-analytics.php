<?php
/**
Plugin Name: WP Statistics and Analytics
Description: Page, Post and User numbers delivered in you admin panel and on a standard page of your choice.
Version: 0.0.3
Author: klick on it
Author URI: http://klick-on-it.com
License: GPLv2 or later
Text Domain: klick-sa
 */

/*
This plugin developed by klick-on-it.com
*/

/*
Copyright 2017 klick on it (http://klick-on-it.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 3 - GPLv3)
as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if (!defined('ABSPATH')) die('No direct access allowed');

if (!class_exists('Klick_Sa')) :
define('KLICK_SA_VERSION', '0.0.1');
define('KLICK_SA_PLUGIN_URL', plugin_dir_url(__FILE__));
define('KLICK_SA_PLUGIN_MAIN_PATH', plugin_dir_path(__FILE__));
define('KLICK_SA_PLUGIN_SETTING_PAGE', admin_url() . 'admin.php?page=klick_sa');

class Klick_Sa {

	protected static $_instance = null;

	protected static $_options_instance = null;

	protected static $_notifier_instance = null;

	protected static $_logger_instance = null;

	protected static $_dashboard_instance = null;
	
	/**
	 * Constructor for main plugin class
	 */
	public function __construct() {
		
		register_activation_hook(__FILE__, array($this, 'klick_sa_activation_actions'));

		register_deactivation_hook(__FILE__, array($this, 'klick_sa_deactivation_actions'));

		add_action('wp_ajax_klick_sa_ajax', array($this, 'klick_sa_ajax_handler'));
		
		add_action('admin_menu', array($this, 'init_dashboard'));
		
		add_action('plugins_loaded', array($this, 'setup_translation'));
		
		add_action('plugins_loaded', array($this, 'setup_loggers'));

		add_action( 'wp_footer', array($this, 'klick_sa_ui_scripts'));

		add_action('wp_head', array($this, 'klick_sa_ui_css'));

	}

	/**
	 * Instantiate Klick_Sa if needed
	 *
	 * @return object Klick_Sa
	 */
	public static function instance() {
		if (empty(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Instantiate Klick_Sa_Options if needed
	 *
	 * @return object Klick_Sa_Options
	 */
	public static function get_options() {
		if (empty(self::$_options_instance)) {
			if (!class_exists('Klick_Sa_Options')) include_once(KLICK_SA_PLUGIN_MAIN_PATH . '/includes/class-klick-sa-options.php');
			self::$_options_instance = new Klick_Sa_Options();
		}
		return self::$_options_instance;
	}
	
	/**
	 * Instantiate Klick_Sa_Dashboard if needed
	 *
	 * @return object Klick_Sa_Dashboard
	 */
	public static function get_dashboard() {
		if (empty(self::$_dashboard_instance)) {
			if (!class_exists('Klick_Sa_Dashboard')) include_once(KLICK_SA_PLUGIN_MAIN_PATH . '/includes/class-klick-sa-dashboard.php');
			self::$_dashboard_instance = new Klick_Sa_Dashboard();
		}
		return self::$_dashboard_instance;
	}
	
	/**
	 * Instantiate Klick_Sa_Logger if needed
	 *
	 * @return object Klick_Sa_Logger
	 */
	public static function get_logger() {
		if (empty(self::$_logger_instance)) {
			if (!class_exists('Klick_Sa_Logger')) include_once(KLICK_SA_PLUGIN_MAIN_PATH . '/includes/class-klick-sa-logger.php');
			self::$_logger_instance = new Klick_Sa_Logger();
		}
		return self::$_logger_instance;
	}
	
	/**
	 * Instantiate Klick_Sa_Notifier if needed
	 *
	 * @return object Klick_Sa_Notifier
	 */
	public static function get_notifier() {
		if (empty(self::$_notifier_instance)) {
			include_once(KLICK_SA_PLUGIN_MAIN_PATH . '/includes/class-klick-sa-notifier.php');
			self::$_notifier_instance = new Klick_Sa_Notifier();
		}
		return self::$_notifier_instance;
	}
	
	/**
	 * Establish Capability
	 *
	 * @return string
	 */
	public function capability_required() {
		return apply_filters('klick_sa_capability_required', 'manage_options');
	}
	
	/**
	 * Init dashboard with menu and layout
	 *
	 * @return void
	 */
	public function init_dashboard() {
		$dashboard = $this->get_dashboard();
		$dashboard->init_menu();
		load_plugin_textdomain('klick-sa', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}

	/**
	 * To enqueue js at user side
	 *
	 * @return void
	 */
	public function klick_sa_ui_scripts(){
		$dashboard = $this->get_dashboard();
		$dashboard->init_user_end();
		
	}

	/**
	 * To enqueue css at user side
	 *
	 * @return void
	 */
	public function klick_sa_ui_css(){
		$dashboard = $this->get_dashboard();
		$dashboard->init_user_css();
		add_shortcode('Klick-SA-View',  array($this, 'klick_sa_view'));
		
	}

	/**
	 * Perform post plugin loaded setup
	 *
	 * @return void
	 */
	public function setup_translation() {
		load_plugin_textdomain('klick-sa', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}

	/**
	 * Creates an array of loggers, Activate and Adds
	 *
	 * @return void
	 */
	public function setup_loggers() {
		
		$logger = $this->get_logger();

		$loggers = $logger->klick_sa_get_loggers();
		
		$logger->activate_logs($loggers);
		
		$logger->add_loggers($loggers);
	}
	
	/**
	 * Ajax Handler
	 */
	public function klick_sa_ajax_handler() {

		$nonce = empty($_POST['nonce']) ? '' : $_POST['nonce'];

		if (!wp_verify_nonce($nonce, 'klick_sa_ajax_nonce') || empty($_POST['subaction'])) die('Security check');
		
		$parsed_data = array();
		$data = array();
		
		$subaction = sanitize_key($_POST['subaction']);
		
		$post_data = isset($_POST['data']) ? $_POST['data'] : null;
		
		parse_str($post_data, $parsed_data);
		
		switch ($subaction) {
			case "klick_sa_shorcode_data":
			
				break;	
			default:
				error_log("Klick_Sa_Commands: ajax_handler: no such sub-action (" . esc_html($subaction) . ")");
				die('No such sub-action/command');
		}
		
		$results = array();
		
		// Get sub-action class
		if (!class_exists('Klick_Sa_Commands')) include_once(KLICK_SA_PLUGIN_MAIN_PATH . 'includes/class-klick-sa-commands.php');

		$commands = new Klick_Sa_Commands();

		if (!method_exists($commands, $subaction)) {
			error_log("Klick_Sa_Commands: ajax_handler: no such sub-action (" . esc_html($subaction) . ")");
			die('No such sub-action/command');
		} else {
			$results = call_user_func(array($commands, $subaction), $data);

			if (is_wp_error($results)) {
				$results = array(
					'result' => false,
					'error_code' => $results->get_error_code(),
					'error_message' => $results->get_error_message(),
					'error_data' => $results->get_error_data(),
					);
			}
		}
		
		echo json_encode($results);
		die;
	}

	/**
	 * Plugin activation actions.
	 *
	 * @return void
	 */
	public function klick_sa_activation_actions(){
		$this->get_options()->set_default_options();
	}

	/**
	 * Plugin deactivation actions.
	 *
	 * @return void
	 */
	public function klick_sa_deactivation_actions(){
		$this->get_options()->delete_all_options();
	}

	/**
	 * Render DOM elements or shortcode area.
	 *
	 * @return void
	 */
	public function klick_sa_view(){
		ob_start();
		echo '<div class="klick-sa-data-container">';
		echo '<div class="klick-sa-overlay">';
		echo '<img class="loading-image" src="' . KLICK_SA_PLUGIN_URL . 'images/ajax-loader.gif" alt="Loading.." />';
		echo '</div>';
		echo '<div class="klick-sa-post klick-sa-data"><div class="klick-sa-inner-data"></div></div>';
		echo '<div class="klick-sa-page klick-sa-data"><div class="klick-sa-inner-data"></div></div>';
		echo '<div class="klick-sa-users klick-sa-data"><div class="klick-sa-inner-data"></div></div>';
		echo '</div>';
		return ob_get_clean();
	}

}

register_uninstall_hook(__FILE__,'klick_sa_uninstall_option');

/**
 * Delete data when uninstall
 *
 * @return void
 */
function klick_sa_uninstall_option(){
	Klick_Sa()->get_options()->delete_all_options();
}

/**
 * Instantiates the main plugin class
 *
 * @return instance
 */
function Klick_Sa(){
     return Klick_Sa::instance();
}

endif;

$GLOBALS['Klick_Sa'] = Klick_Sa();
