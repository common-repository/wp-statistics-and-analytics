<?php 

if (!defined('KLICK_SA_PLUGIN_MAIN_PATH')) die('No direct access allowed');

/**
 * Commands available from control interface (e.g. wp-admin) are here
 * All public methods should either return the data, or a WP_Error with associated error code, message and error data
 */
/**
 * Sub commands for Ajax
 *
 */
class Klick_Sa_Commands {
	private $options;
	
	/**
	 * Constructor for Commands class
	 *
	 */
	public function __construct() {
		$this->options = Klick_Sa()->get_options();
	} 

	/**
	 * Fetch and redner shorcode data page, post and users
	 *
	 * @param  Array 	$data an array of data UI form
	 *
	 * @return Array 	$result
	 */
	public function klick_sa_shorcode_data($data) {
		return array(
			'result' => $this->options->render_shorcode_data($data),
			);
	}

	/**
	 * dis-miss button
	 *
	 * @param  Array 	$data an array of data UI form
	 *
	 * @return Array 	$status
	 */
	public function dismiss_page_notice_until($data) {
		
		return array(
			'status' => $this->options->dismiss_page_notice_until($data),
			);
	}

	/**
	 * dis-miss button
	 *
	 * @param  Array 	$data an array of data UI form
	 *
	 * @return Array 	$status
	 */
	public function dismiss_page_notice_until_forever($data) {
		
		return array(
			'status' => $this->options->dismiss_page_notice_until_forever($data),
			);
	}
}
