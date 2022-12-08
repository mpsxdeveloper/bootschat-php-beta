<?php

class HomeController extends controller {

    public function __construct() {   
    }
	
    public function index() {        
        $data = array(
            'info' => array()            
	);       
        $this->loadView('home', $data);        
    }
    
}