<?php
//declare(strict_types=1);

namespace controller;

final class admin extends Base_Controller {

	public function beforeroute(\Base $f3, array $args = []): void {			
		$this->tablefind('category');
		$this->tablefind('location');							
		$f3->set('content', $args['module'].'/'.$args['func'].'.html');		
		$f3->set('title',ucfirst($args['func']));			
	}

	public function index(\Base $f3, array $args = []): void {
		$f3->reroute('admin/login');
	}

	// JOB methods
	// list
	public function get_jobs(\Base $f3, array $args = []): void {		
    	$this->tablefind('job');            		            
		echo \Template::instance()->render($f3->AJAX ? $f3->content : 'layout.htm'); 
	}
    // get form
	public function get_job(\Base $f3, array $args = []): void {		
    echo \Template::instance()->render($f3->AJAX ? $f3->content : 'layout.htm'); 
	}

    // post form
	public function post_job(\Base $f3, array $args = []): void {			
		$job = new \DB\SQL\Mapper($f3->get('db'),$args['func']);
		$job->position = $f3->get('POST.position');
		$job->cat = $get('POST.cat');
		$job->type = $get('POST.type');
		$job->loc = $get('POST.loc');		
		$job->till = $get('POST.till');
		$job->save();				
  	}

	// CATEGORY
	// get form
  	public function get_category (\Base $f3, array $args = []): void {			
		echo \Template::instance()->render($f3->AJAX ? $f3->content : 'layout.htm'); 
	}
   // post form
	public function post_category(\Base $f3, array $args = []): void {		
		$mapper = new \model\Category();		
		$mapper->name = $f3->get('POST.name');		
		$valid = $this->validation->validateCortexMapper($mapper);				
    	  if($valid) {
    		$mapper->save();		
       		}else {						
	   		$this->get_category($f3);
	      }																		
	}
}

