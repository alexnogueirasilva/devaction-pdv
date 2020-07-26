<?php 
use App\Responsible;
use App\ApplianceNotFounController;

	function is_adm(){
		$usr = session('user_logged');
        return $usr['adm'];
	}

	function get_id_user(){
		$usr = session('user_logged');
        return $usr['id'];
	}

	