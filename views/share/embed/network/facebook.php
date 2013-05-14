<?php
	$query_array = array(
		'app_id' => Kohana::$config->load('facebook.appId'),
		'method' => 'feed',
		'link' => urldecode($url),
		'redirect_uri' => urldecode($redirect_url),
		'display' => 'popup'
	);
	
	$share_url = 'https://www.facebook.com/';
	
	if (Kohana::$config->load('facebook.appId'))
	{
		$share_url.= 'dialog/feed?';
	}
	else
	{
		$share_url.= 'sharer/sharer.php?';
	}
	$share_url.= http_build_query($query_array);
	
	$icon_class = $sharing_network_data['share_icon'].' share_icon_'.$sharing_network.' share_icon';
	$icon = '<i class="'.$icon_class.'" rel="tooltip" network="'.$sharing_network.'" share_id="'.$share_id.'" title="'.$sharing_network_data['share_title'].'"></i>';
	
	echo HTML::anchor($share_url, $icon, array('target' => '_blank'));
	echo $share_count;
?>