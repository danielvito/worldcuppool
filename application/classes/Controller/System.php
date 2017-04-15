<?php defined('SYSPATH') or die('No direct script access.');

class Controller_System extends Controller {
	
	public function action_login()
	{
		$strLogin = $this->request->post('login');
		$strPass = $this->request->post('password');
		
		if ( ! Util_App::login($strLogin, $strPass)) {
			Form::addMessage(Form::ERROR, ___('errors.login_invalid'));
			HTTP::redirect('/');
		}
		
		HTTP::redirect('/');
	}
	
	public function action_logout()
	{
		Util_App::logout();
		HTTP::redirect('/');
	}

}
