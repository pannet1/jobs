<?php
// declare(strict_types=1);

/**
 * This is an example base controller to help establish a foundation. You can use this
 * to create other base controllers (especially for the before/afterroute events).
 * This is likely where you will put code to authenticate the user if they can
 * view the route they are intending. 
 */
namespace controller;


abstract class Base_Controller {	
	protected $validation;
	
	function __construct(\Base $f3) {		
		$db=new \DB\SQL(
			$f3->get('DBDNS') . $f3->get('DBNAME'),
			$f3->get('DBUSER'),
			$f3->get('DBPASS')
		); 		
		$f3->set('db',$db);
		$f3->set('type',array( "-- Job Type --","Full Time","Part Time"));    		
	    $f3->set('status',array("Draft","Published"));
		$this->validation = \Validation::instance();		
		$this->validation->onError(function($text,$key) {
			// f3 not available in this context ?
			$f3 = \Base::instance();
			// extract custom error from 'en' lang to hive			
			if(isset($key)) 
				$msg = $f3->get('error.'.$key);			
				if(isset($msg)) 
					$f3->set('msg', $msg);															
			// somehow the default build of error.$key always evaluates to true
			// and hence this workaround.
			$f3->set($key,$key);		
		});
  }

  public function tablefind(string $table) {
	$f3 = \Base::instance();	
	$obj = new \DB\SQL\Mapper($f3->get('db'),$table);			
	$f3->set($table,$obj->find());
  }
  
	/**
	 * This will be called before a route is executed. Return false to deny the request
	 *
	 * @param \Base $f3
	 * @return bool
	 */
	public function beforeroute(\Base $f3) {		
		if ($f3->get('POST')) {			
			$post = Xss_Filter::filter('POST');		
			$f3->set('POST',$post);			
		}
	}

}
