<?php
//declare(strict_types=1);

namespace controller;

final class admin extends Base_Controller {

	public function beforeroute(\Base $f3, array $args = []){		
		$this->tablefind('category');
		$this->tablefind('location');							
		$f3->set('content', $args['module'].'/'.$args['func'].'.html');		
		$f3->set('title',ucfirst($args['func']));			
	}

	public function get_index(\Base $f3, array $args = []){
		 $f3->reroute('admin/jobs');		
	}

	// JOB methods
	// list
	public function get_jobs(\Base $f3, array $args = []) {		
    	$this->tablefind('job');            		            
		echo \Template::instance()->render($f3->AJAX ? $f3->content : 'layout.htm'); 
	}
    // get form
	public function get_job(\Base $f3, array $args = []) {		
    echo \Template::instance()->render($f3->AJAX ? $f3->content : 'layout.htm'); 
	}

    // post form
	public function post_job(\Base $f3, array $args = []) {			
		$job = new \DB\SQL\Mapper($f3->get('db'),$args['func']);
		$job->position = $f3->get('POST.position');
		$job->cat = $get('POST.cat');
		$job->type = $get('POST.type');
		$job->loc = $get('POST.loc');		
		$job->till = $get('POST.till');
		$job->save();				
    }

	// job detail
	// post form
	public function post_detail(\Base $f3, array $args = []) {	
		$job_id = $f3->get('POST.job_id');				
        if( isset($job_id) ) {						
			$detail = new \DB\SQL\Mapper($f3->get('db'),$args['func']);
			$detail->load(['job_id=?',$job_id]);
			$detail->job_id = $f3->get('POST.job_id');
			$detail->md     = $f3->get('POST.md');
			$detail->aftersave(function($self,$pkeys){			
				$parsedown = new \Parsedown();			
				$data = $parsedown->text($self->md); 	
				$fname = 'app/ui/detail/'.$self->id.'.html';				
				$myfile = fopen($fname, "w") or die("Unable to open file!");												
				$opendiv = "<div class='container'><div class='detail'>";
				fwrite($myfile, $opendiv);				
				fwrite($myfile, $data);				
				$tpl = "</div><include href='./upload.htm'/></div>";								
				fwrite($myfile, $tpl);												
				fclose($myfile);				
			});		  												
			$detail->dry() ? $detail->save() : $detail->update();		
			$f3->reroute("/admin");		
		}
		echo "there was an error while saving job detail";
    }
    // get form;
    public function get_detail(\Base $f3, array $args = []) {			
        $job_id = $args['id'];
		if(isset($job_id)) {			
			$detail = new \DB\SQL\Mapper($f3->get('db'),$args['func']);
			$detail->load(['job_id=?',$job_id]);			
			if (!$detail->dry()) 
				$detail->copyto('POST');			
        	$f3->set('POST.job_id', $job_id);
			echo \Template::instance()->render($f3->AJAX ? $f3->content : 'layout.htm');    
		} else 
		echo "unable to get the job record";
    }

	// CATEGORY
	// get form
  	public function get_category (\Base $f3, array $args = []) {			
		echo \Template::instance()->render($f3->AJAX ? $f3->content : 'layout.htm'); 
	}

   // post form
	public function post_category(\Base $f3, array $args = []) {		
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

