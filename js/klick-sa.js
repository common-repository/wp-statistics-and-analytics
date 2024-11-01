/**
 * Send an action via admin-ajax.php
 * 
 * @param {string} action - the action to send
 * @param * data - data to send
 * @param Callback [callback] - will be called with the results
 * @param {boolean} [json_parse=true] - JSON parse the results
 */
var klick_sa_send_command = function (action, data, callback, json_parse) {
	json_parse = ('undefined' === typeof json_parse) ? true : json_parse;
	var ajax_data = {
		action: 'klick_sa_ajax',
		subaction: action,
		nonce: klick_sa_ajax_nonce,
		data: data
	};
	jQuery.post(ajaxurl, ajax_data, function (response) {
		
		if (json_parse) {
			try {
				var resp = JSON.parse(response);
			} catch (e) {
				console.log(e);
				console.log(response);
				return;
			}
		} else {
			var resp = response;
		}
		
		if ('undefined' !== typeof callback) callback(resp);
	});
}

/**
 * When DOM ready
 * 
 */
jQuery(document).ready(function ($) {
	klick_sa = klick_sa(klick_sa_send_command);
});

/**
 * Function for sending communications
 * 
 * @callable sendcommandCallable
 * @param {string} action - the action to send
 * @param * data - data to send
 * @param Callback [callback] - will be called with the results
 * @param {boolean} [json_parse=true] - JSON parse the results
 */
 
/**
 * Main klick_sa
 * 
 * @param {sendcommandCallable} send_command
 */
var klick_sa = function (klick_sa_send_command) {
	var $ = jQuery;

	/**
	 * Proceses the tab click handler
	 *
	 * @return void
	 */
	$('#klick_sa_nav_tab_wrapper .nav-tab').click(function (e) {
		e.preventDefault();
		
		var clicked_tab_id = $(this).attr('id');
	
		if (!clicked_tab_id) { return; }
		if ('klick_sa_nav_tab_' != clicked_tab_id.substring(0, 17)) { return; }
		
		var clicked_tab_id = clicked_tab_id.substring(17);

		$('#klick_sa_nav_tab_wrapper .nav-tab:not(#klick_sa_nav_tab_' + clicked_tab_id + ')').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');

		$('.klick-sa-nav-tab-contents:not(#klick_sa_nav_tab_contents_' + clicked_tab_id + ')').hide();
		$('#klick_sa_nav_tab_contents_' + clicked_tab_id).show();
	});


}
