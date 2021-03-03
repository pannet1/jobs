<?php
namespace model;

class User extends \DB\Cortex {
  protected
  $fieldConf = [
    'name' => [
        'type' => 'VARCHAR55',        
        'nullable' => false,
        'filter' => 'trim',
        'validate_level'=> 1,        
        'validate'=> 'required|max_len,55',
    ],  
    'email' => [
        'type' => 'VARCHAR50',        
        'nullable' => false,
        'filter' => 'trim',        
        'validate'=> 'required|valid_email|email_host', 
    ],        
    'email' => [
        'type' => 'VARCHAR50',        
        'nullable' => false,
        'filter' => 'trim',        
        'validate_level'=> 1,        
        'validate'=> 'unique', 
    ],        
    'password' => [
        'type' => 'VARCHAR100',        
        'nullable' => false,
        'filter' => 'trim',
        'validate'=> 'required|min_len,8',
    ],    
    'mobile' => [
        'type' => 'VARCHAR10',        
        'nullable' => false,
        'filter' => 'trim',
        'validate_level'=> 1,
        'validate'=> 'unique|required|exact_len,10|integer',
    ],    
],
    $db = 'db',     
    $table = 'user',
    $primary = 'id';        
} 