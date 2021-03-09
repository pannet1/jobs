<?php
namespace controller;

final class user extends Base_Controller {

	public function beforeroute(\Base $f3, array $args = []){			
		$this->tablefind('category');
		$this->tablefind('location');		
		$f3->set('content', $args['module'].'/'.$args['func'].'.html');		
		$f3->set('title',ucfirst($args['func']));				
	}

	public function get_index(\Base $f3, array $args = []) {		
		$f3->reroute('user/login');
	}

	public function get_register(\Base $f3, array $args = []) {		
		if ($f3->get('msg')) {
		  $img=new \Image;
		  $f3->set('captcha',$f3->base64(
			$img->captcha('thunder.ttf',18,5,'SESSION.captcha')->
			  dump(),'image/png'));
		}
		echo \Template::instance()->render($f3->AJAX ? $f3->content : 'layout.htm');
	  }
	
	public function post_register(\Base $f3, array $args = []) {	
		$mapper = new \model\User();		
		$mapper->name = $f3->get('POST.name');		
		$mapper->email = $f3->get('POST.email');		
		$mapper->password = $f3->get('POST.password');		
		$mapper->mobile = $f3->get('POST.mobile');		
		/* $mapper->verification_token = $f3->get('POST.password');	*/
		$valid = $this->validation->validateCortexMapper($mapper,1);				
    	  if($valid) {
    		$mapper->save();		
			$f3->clear('SESSION.captcha');			
			$f3->reroute('/');	
       		}else {						
	   		$this->get_register($f3);
	      }	
	}
	
   public function get_login(\Base $f3, array $args = []) {		
	 if ($f3->get('msg')) {
	  $img=new \Image;
	  $f3->set('captcha',$f3->base64(
		$img->captcha('thunder.ttf',18,5,'SESSION.captcha')->
		  dump(),'image/png'));
	}	
	echo \Template::instance()->render($f3->AJAX ? $f3->content : 'layout.htm'); 
  }

  function post_login (\Base $f3, array $args = []) {			
	$captcha=$f3->get('SESSION.captcha');
	if ($captcha && strtoupper($f3->get('POST.captcha'))!=$captcha)	{
		$f3->set('msg','Invalid CAPTCHA code');	 			
	} else {
		$mapper = new \model\User();
		$mapper->email = $f3->get('POST.email');		
		$mapper->password = $f3->get('POST.password');			
		$valid = $this->validation->validateCortexMapper($mapper);					
		if($valid) 			
			$result = $mapper->findone(array('email=? AND password=?', $f3->get('POST.email'), $f3->get('POST.password')));					
			if($result) {												
				$f3->clear('SESSION.captcha');
				$f3->set('SESSION.userid',$this->user->id);				
				$f3->reroute('/');	
			}
	}	
	$this->get_login($f3);	
  }
  
}
