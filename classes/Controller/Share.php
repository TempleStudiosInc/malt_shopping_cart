<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Share extends Controller_Customer_Website {
	
    public function action_r()
    {
    	$unique_id = $this->request->param('id');
        $share = ORM::factory('Share')->where('unique_id', '=', $unique_id)->find();
		
		$is_social_network = Request::is_social_network();
		if ($share->id > 0)
        {
        	$share_url = $share->page_url;
			
			if ( ! $is_social_network)
	        {
	            $events_share = ORM::factory('Shares_Click');
	            $events_share->share_id = $share->id;
	            $events_share->date_clicked = date('Y-m-d H:i:s');
	            $events_share->save();
	        }
        }
        else
        {
            $share_url = '/';
        }
		$this->redirect($share_url);
    }
	
	public function action_load_sharing()
    {
    	$uri = Arr::get($_GET, 'uri', URL::get_current_url());
		$uri_id = Arr::get($_GET, 'uri_id', md5($uri));
		$url = URL::get_current_url($type = 'domain').$uri;
		$share_page_title = Arr::get($_GET, 'share_page_title');
		$description = Arr::get($_GET, 'description');
		$image = Arr::get($_GET, 'image');
		
		$view = View::factory('share/embed/index');
		
		$view->uri = urlencode($uri);
		$view->url = urlencode($url);
		$view->uri_id = $uri_id;
		$view->share_page_title = $share_page_title;
		$view->description = $description;
		$view->image = $image;
		
		$view->share_count = Share::get_share_count_by_url($uri);
		$view->share_count_facebook = Share::get_share_count_by_url($uri, 'facebook');
		$view->share_count_twitter = Share::get_share_count_by_url($uri, 'twitter');
		$view->share_count_google_plus = Share::get_share_count_by_url($uri, 'google_plus');
		$view->share_count_pinterest = Share::get_share_count_by_url($uri, 'pinterest');
		
		$this->template->body = $view;
    }
	
    public function action_generate_share()
    {
    	$share = ORM::factory('Share');
		
    	$network = Arr::get($_GET, 'network', false);
    	$url = Arr::get($_GET, 'url', false);
		$url = urldecode($url);
    	$uri = Arr::get($_GET, 'uri', false);
		$uri = urldecode($uri);
		$uri_id = Arr::get($_GET, 'uri_id', false);
		$share_id = Arr::get($_GET, 'share_id', Format::generate_unique_id());
		
		$share->user_id = $this->user->id;
        $share->session_id = session_id();
        $share->unique_id = $share_id;
		$share->page_url = $uri;
		$share->page_id = md5($uri);
		$share->network = $network;
		$share->date_generated = date('Y-m-d H:i:s');
        $share->save();
        
		echo true;
		die();
    }
	
	public function action_completed()
	{
		$view = View::factory('share/completed');
		$this->template->body = $view;
	}
} // End Share
