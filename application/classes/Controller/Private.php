<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Private extends Controller_Base {
	
	public function before()
	{
		if ( ! Util_App::isLoggedIn()) {
			Form::addMessage(Form::INFO, ___('messages.do_login'));
			HTTP::redirect('/');
		}

		parent::before();
	}

}