<?php
	//메인화면 처리
    class Home extends CI_Controller{
		function __construct() {
	        parent::__construct();
	    }

		function index(){
			go_location("/admin");
		}
    }
?>
