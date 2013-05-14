<?php
	$query_array = array(
		'url' => urldecode($url),
		'media' => $image,
		'description' => $share_page_title. ' '.$description
	);
	
	$share_url = 'http://pinterest.com/pin/create/button/?';
	$share_url.= http_build_query($query_array);
	
	$icon_class = $sharing_network_data['share_icon'].' share_icon_'.$sharing_network.' share_icon';
	$icon = '<i class="'.$icon_class.'" rel="tooltip" network="'.$sharing_network.'" share_id="'.$share_id.'" title="'.$sharing_network_data['share_title'].'"></i>';
	
	echo HTML::anchor($share_url, $icon, array('target' => '_blank'));
	echo $share_count;
?>