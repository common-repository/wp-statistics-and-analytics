<?php if (!defined('KLICK_SA_VERSION')) die('No direct access allowed'); ?>

<?php 
	// To display the notices at admin page
	Klick_Sa()->get_notifier()->do_notice('top');
?>

<div class="klick-logo-and-title">
		<img src='<?php echo KLICK_SA_PLUGIN_URL ?>images/sa-banner.png' height='100px'>
</div>	

<!-- Render tabs -->
<div id="klick_sa_nav_tab_wrapper" class="nav-tab-wrapper wp-clearfix">
	<?php foreach ($tabs as $tab_id => $tab_title) { ?>
		<a id="klick_sa_nav_tab_<?php echo $tab_id; ?>" href="<?php esc_attr_e($options->admin_page_url()); ?>&amp;tab=klick_sa_<?php echo $tab_id; ?>" class="nav-tab <?php if ($active_tab == $tab_id) echo 'nav-tab-active'; ?>"><?php echo $tab_title; ?></span></a>
	<?php } ?>
</div>

<script type="text/javascript">
	var klick_sa_ajax_nonce ='<?php echo wp_create_nonce('klick_sa_ajax_nonce'); ?>';
</script>
