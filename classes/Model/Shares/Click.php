<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Shares_Click extends ORM {
    protected $_belongs_to = array(
        'share' => array(
            'model'   => 'Share',
        ),
    );
    
    public function generate_unique_id()
    {
        return strtoupper(uniqid());
    }
}