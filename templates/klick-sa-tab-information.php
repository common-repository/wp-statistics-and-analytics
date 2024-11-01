<!-- First Tab content -->
<div id="klick_sa_tab_first">
		<div class="klick-notice-message"></div>
		<div class="wp-list-table widefat fixed striped klick-sa-list"> <!-- Klick tab specific notice starts -->
		</div> <!-- Klick tab specific notice ends -->
	    <hr/>

	    <div class="klick-sa-data-container"> <!-- Klick SA data render starts -->
	    	<!-- post data -->
	    	<div class="klick-sa-post klick-sa-data">
	    		<span><h2>POST</h2></span>
	    		<?php 
	    		$post = wp_count_posts('post'); 
	    		echo "<span> Publish : " . $post->publish . "</span><br>";
	    		echo "<span> Draft : " . $post->draft . "</span><br>";
	    		echo "<span> Pending : " . $post->pending . "</span><br>";
	    		echo "<span> Trash : " . $post->trash . "</span><br>";
	    		?>
	    	</div>

	    	<!-- page data -->
	    	<div class="klick-sa-page klick-sa-data">
	    		<span><h2>PAGE</h2></span>
	    		<?php 
	    		$page = wp_count_posts('page'); 
	    		echo "<span> Publish : " . $page->publish . "<span><br>";
	    		echo "<span> Draft : " . $page->draft . "<span><br>";
	    		echo "<span> Pending : " . $page->pending . "<span><br>";
	    		echo "<span> Trash : " . $page->trash . "<span><br>";
	    		?>
	    	</div>

	    	<!-- users data -->
	    	<div class="klick-sa-user klick-sa-data">
	    		<span><h2>USERS</h2></span>
	    		<?php 
	    		$user = count_users('user'); 
	    		echo "<span> Total Users : " . $user['total_users'] . "<span> <br><br>";
	    		echo "<span> Available Roles :  <hr>";
	    		echo "<span> None : " . $user['avail_roles']['none'] . "<span><br>";
	    		echo "<span> Administrator : " . $user['avail_roles']['administrator'] . "<span><br>";
	    		?>
	    	</div>
	    </div> <!-- Klick SA data render ends -->

</div>

<script type="text/javascript">
	var klick_sa_ajax_nonce ='<?php echo wp_create_nonce('klick_sa_ajax_nonce'); ?>';
</script>
