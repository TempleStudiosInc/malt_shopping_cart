<div>
	<div class="share_networks" url="<?php echo $url ?>" uri="<?php echo $uri ?>" uri_id="<?php echo $uri_id ?>">
<?php
	$sharing_networks = array(
		'facebook' => array(
			'share_title' => 'Share on Facebook',
			'share_icon' => 'icon-facebook-sign'
		),
		'twitter' => array(
			'share_title' => 'Tweet on Twitter',
			'share_icon' => 'icon-twitter-sign'
		),
		'google_plus' => array(
			'share_title' => 'Share on Google+',
			'share_icon' => 'icon-google-plus-sign'
		),
		'pinterest' => array(
			'share_title' => 'Pin on Pinterest',
			'share_icon' => 'icon-pinterest-sign'
		),
	);
	
	$share = ORM::factory('Share');
	foreach ($sharing_networks as $sharing_network => $sharing_network_data)
	{
		$share_id = Format::generate_unique_id();
		
		$share_count_var = 'share_count_'.$sharing_network;
        
		$share_view = View::factory('share/embed/network/'.$sharing_network);
		$share_view->sharing_network = $sharing_network;
		$share_view->sharing_network_data = $sharing_network_data;
		$share_view->share_id = $share_id;
		$share_view->url = URL::site('/share/r/'.$share_id, 'http');
		$share_view->share_page_title = $share_page_title;
		$share_view->description = $description;
		$share_view->image = $image;
		$share_view->redirect_url = URL::site('/share/completed/'.$share_id, 'http');
		$share_view->share_count = $$share_count_var;
		echo $share_view;
	}
?>
	</div>
</div>
<script>
	$(function() {
		$('.share_icon').tooltip();
		
		if (sharing_loaded === false) {
			sharing_loaded = true;
			$('.share_icon').live('click', function(event) {
				event.preventDefault();
				
				var network = $(this).attr('network');
				
				$('#share_modal_header > h3').text(modal_title);
				$('#share_modal').modal('show');
				
				var ajax_data = {};
				ajax_data.network = network;
				ajax_data.url = $(this).parents('.share_networks').attr('url');
				ajax_data.uri = $(this).parents('.share_networks').attr('uri');
				ajax_data.uri_id = $(this).parents('.share_networks').attr('uri_id');
				ajax_data.share_id = $(this).attr('share_id');
				
				$.ajax({
					url: '/share/generate_share',
					data: ajax_data,
					dataType: 'html',
					cache: false
				}).done(function(html) {
					$('#share_modal').modal('hide');
				});
				
				var url = $(this).parent().attr('href');
				var modal_title = $(this).attr('data-original-title');
		    	var window_size = 'left=0, top=0, width=600, height=375, location=no, menubar=no, status=no, toolbar=no, scrollbars=no, resizable=no, left=0, top=0';
		    	
		    	share_window = window.open(url, modal_title, window_size);
			    share_window.moveTo(0,0);
			    share_window.focus();
			})
		}
	})
</script>

<div id="share_modal" class="modal hide fade">
	<div id="share_modal_header" class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3></h3>
	</div>
	<div class="modal-body">
		<div id="share_modal_loader">
			<h4>Loading. Please wait. <?php echo HTML::image('_media/core/common/img/loader.gif'); ?></h4>
		</div>
		<div id="share_modal_contaienr" style="display:none;"></div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal" >Close</a>
	</div>
</div>