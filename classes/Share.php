<?php defined('SYSPATH') or die('No direct script access.');

class Share {
	
    public static function embed($uri = false)
    {
    	if ($uri === false)
		{
			$uri = Request::detect_uri();
		}
		
        $view = View::factory('share/embed/embed');
		$view->uri_id = md5($uri);
		$view->uri = $uri;
		
		return $view;
    }
	
	public static function get_share_count_by_url($url = false, $network = 'all')
	{
		if ($url == false)
		{
			$url = Request::detect_uri();
		}
		
		$share_count = ORM::factory('Share')->where('page_url', '=', $url);
		if ($network !== 'all')
		{
			$share_count->where('network', '=', $network);
		}
		$share_count = $share_count->count_all();
		
		$view = View::factory('share/embed/count');
		$view->share_count = $share_count;
		$view->uri_id = md5($url);
		return $view;
	}
}