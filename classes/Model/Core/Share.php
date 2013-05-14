<?php defined('SYSPATH') or die('No direct access allowed.');

abstract class Model_Core_Share extends ORM {
   protected $_has_many = array(
	    'clicks' => array(
	        'model'   => 'Shares_Clicks',
	    )
    );
    protected $_belongs_to = array(
        'user' => array(
            'model'   => 'User',
        ),
    );
}