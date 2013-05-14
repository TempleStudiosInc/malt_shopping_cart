<script>
	if (sharing_loaded !== false) {
		var sharing_loaded = false;
	}
	$(function() {
		load_sharing();
		function load_sharing()
		{
			var ajax_data = {};
			ajax_data.uri = '<?php echo $uri ?>';
			ajax_data.uri_id = '<?php echo $uri_id ?>';
			
			title = $("#share_<?php echo $uri_id ?>").siblings('.meta_data').children('meta[property="og:title"]').attr('content');
			if (title === undefined) {
				title = $('meta[property="og:title"]').attr('content');
			}
			ajax_data.share_page_title = title;
			
			description = $("#share_<?php echo $uri_id ?>").siblings('.meta_data').children('meta[property="og:description"]').attr('content');
			if (description === undefined) {
				description = $('meta[property="og:description"]').attr('content');
			}
			ajax_data.description = description;
			
			image = $("#share_<?php echo $uri_id ?>").siblings('.meta_data').children('meta[property="og:image"]').attr('content');
			if (image === undefined) {
				image = $('meta[property="og:image"]').attr('content');
			}
			ajax_data.image = image;
			
			$.ajax({
				url: "/share/load_sharing",
				dataType: 'html',
				data: ajax_data,
				cache: false
			}).done(function( html ) {
				$("#share_<?php echo $uri_id ?> > .share_container").html(html);
				$("#share_<?php echo $uri_id ?> > .loader").fadeOut('slow', function() {
					$("#share_<?php echo $uri_id ?> > .loader").hide();
					$("#share_<?php echo $uri_id ?> > .share_container").fadeIn('slow');
				})
			});
		}
	});
</script>

<div id="share_<?php echo $uri_id ?>" style="clear: both;">
	<div class="loader"><?php echo HTML::image('_media/core/common/img/loader.gif') ?></div>
	<div class="share_container"></div>
</div>