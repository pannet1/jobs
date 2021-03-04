<?php
namespace controller;

final class main extends Base_Controller {

	public function beforeroute(\Base $f3, array $args = []): void {			
		$this->tablefind('category');
		$this->tablefind('location');				
	}

	public function index(\Base $f3, array $args = []): void {			
		$f3->set('content', 'index.html');		
		echo \Template::instance()->render($f3->AJAX ? $f3->content : 'layout.htm'); 
	}
	
  // home page
	public function get_home(\Base $f3, array $args = []): void {		
		$f3->set('title', 'Home');		
		$search = $f3->get('GET.search');
		$cat = $f3->get('GET.cat');		
		$type = $f3->get('GET.type');		
		$loc = $f3->get('GET.loc');		
		
		$sql = "SELECT DISTINCT job.id, job.position, category.name as cat, job.type, location.name as loc FROM job ";					

		if($search) {			
			$search = "'%$".$search."%' ESCAPE '$'";			
			$sql .= "JOIN job as g ON job.position LIKE $search ";					
		}
		// self join job table because where clause not working
		// get job type key from hive
		$sql .= "JOIN job as j ON job.type=j.type ";
		if($type>0)	{
			$sql .= "AND job.type=:type ";				
			$param[':type'] = $type;			
		}			
		// get name from category table
		$sql .= "JOIN category ON job.cat=category.id ";
		if($cat>0) {			
			$sql .= "AND job.cat=:cat ";			
			$param[':cat'] = $cat;			
		}		
		// get location name from location table
		$sql .= "JOIN location ON job.loc=location.id ";
		if($loc>0) {		
			$sql .= "AND job.loc=:loc ";							
			$param[':loc'] = $loc;
		}					
		$db = $f3->get('db');
		$f3->set('result', $db->exec($sql, $param));
		
		$f3->set('content', 'home.html');
		echo \Template::instance()->render($f3->AJAX ? $f3->content : 'layout.htm'); 
	}
}
