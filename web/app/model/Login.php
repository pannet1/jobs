<?php
namespace model;

class Login extends \DB\Cortex {
  protected
  $fieldConf = [    
    'email' => [
        'type' => 'VARCHAR50',        
        'nullable' => false,
        'filter' => 'trim',
        'validate'=> 'required|valid_email|email_host', 
    ],        
    'password' => [
        'type' => 'VARCHAR100',        
        'nullable' => false,
        'filter' => 'trim',
        'validate'=> 'required|min_len,8',
    ],        
],
    $db = 'db',     
    $table = 'user',
    $primary = 'id';        
} 