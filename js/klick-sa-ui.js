/**
 * Send an action via admin-ajax.php
 * 
 * @param {string} action - the action to send
 * @param * data - data to send
 * @param Callback [callback] - will be called with the results
 * @param {boolean} [json_parse=true] - JSON parse the results
 */
var klick_sa_ui_send_command = function (action, data, callback, json_parse) {
	json_parse = ('undefined' === typeof json_parse) ? true : json_parse;
	var ajax_data = {
		action: 'klick_sa_ajax',
		subaction: action,
		nonce: klick_sa.klick_sa_ajax_nonce,
		data: data
	};
	jQuery.post(klick_sa.ajaxurl, ajax_data, function (response) {
		
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
	klick_sa_ui = klick_sa_ui(klick_sa_ui_send_command);

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
 * Main klick_sa_ui
 * 
 * @param {sendcommandCallable} klick_sa_ui_send_command
 */
var klick_sa_ui = function (klick_sa_ui_send_command) {
	var $ = jQuery;
	$(".klick-sa-overlay").show();
	klick_us_render_shorcode();
	// Function to execute on specific interval
	setInterval(function(){ 
		klick_us_render_shorcode();
	}, 10000);

	function klick_us_render_shorcode(){
		var form_data = "";

		// Send ajax request to render shortcode data
		klick_sa_ui_send_command('klick_sa_shorcode_data', form_data, function (resp) {
			$(".klick-sa-overlay").hide();
			jQuery(".klick-sa-page").find(".klick-sa-inner-data").html("");
			jQuery(".klick-sa-post").find(".klick-sa-inner-data").html("");
			jQuery(".klick-sa-users").find(".klick-sa-inner-data").html("");

			// Page object
			jQuery(".klick-sa-page").find(".klick-sa-inner-data").append("<span><h2>PAGE</h2></span>");
			if (resp.result.hasOwnProperty('page')) {
				$.each(resp.result.page, function(index, item) {
					jQuery(".klick-sa-page").find(".klick-sa-inner-data").append(index +": "+ item + "<br>");
				});
			}

			// Post object
			jQuery(".klick-sa-post").find(".klick-sa-inner-data").append("<span><h2>POST</h2></span>");
			if (resp.result.hasOwnProperty('post')) {
				$.each(resp.result.post, function(index, item) {
					jQuery(".klick-sa-post").find(".klick-sa-inner-data").append(index +": "+ item + "<br>");
				});
			}

			// user object
			jQuery(".klick-sa-users").find(".klick-sa-inner-data").append("<span><h2>USERS</h2></span>");
			if (resp.result.hasOwnProperty('user')) {
				$.each(resp.result.user, function(index, item) {
					if (typeof item === "object") {
							$.each(item, function(i, item) {
								jQuery(".klick-sa-users").find(".klick-sa-inner-data").append(i +": "+ item + "<br>");
							});
					 } else if (typeof item === "string") {
					 	jQuery(".klick-sa-users").find(".klick-sa-inner-data").append(index +": "+ item + "<br>");
					 } else if (typeof item === "number") {
					 	jQuery(".klick-sa-users").find(".klick-sa-inner-data").append(index +": "+ item + "<br>");
					 } else {	
					 	console.log("Not any data found");
					 }

				});
			}
		});
	}
}
