<?php

if (!defined('ABSPATH')) die('No direct access allowed');

if (class_exists('Klick_Sa_No_Config')) return;

require_once(KLICK_SA_PLUGIN_MAIN_PATH . '/includes/class-klick-sa-abstract-notice.php');

/**
 * Class Klick_Sa_No_Config
 */
class Klick_Sa_No_Config extends Klick_Sa_Abstract_Notice {
	
	/**
	 * Klick_Sa_No_Config constructor
	 */
	public function __construct() {
		$this->notice_id = 'wp-statistics-and-analytics-configure';
		$this->title = __('WP Statistics and Analytics plugin is installed but not configured', 'klick-sa');
		$this->klick_sa = "";
		$this->notice_text = __('Configure it Now', 'klick-sa');
		$this->image_url = '../images/our-more-plugins/SA.svg';
		$this->dismiss_time = 'dismiss-page-notice-until';
		$this->dismiss_interval = 30;
		$this->display_after_time = 0;
		$this->dismiss_type = 'dismiss';
		$this->dismiss_text = __('Hide Me!', 'klick-sa');
		$this->position = 'dashboard';
		$this->only_on_this_page = 'index.php';
		$this->button_link = KLICK_SA_PLUGIN_SETTING_PAGE;
		$this->button_text = __('Click here', 'klick-sa');
		$this->notice_template_file = 'main-dashboard-notices.php';
		$this->validity_function_param = 'wp-statistics-and-analytics/wp-statistics-and-analytics.php';
		$this->validity_function = 'is_plugin_configured';
	}
}
