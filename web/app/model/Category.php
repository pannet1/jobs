<?php // model\Category.php 

namespace model;

class Category extends \DB\Cortex {
  protected
  $fieldConf = [
    'name' => [
        'type' => 'VARCHAR75',        
        'nullable' => false,
        'filter' => 'trim',
        'validate'=> 'required|max_len,75|unique',
    ],    
],
    $db = 'db',     
    $table = 'category',
    $primary = 'id';        
}
