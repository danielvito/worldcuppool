<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller_Base {
	
	/**
	 * Before
	 * 
	 * @return void
	 */
	public function before()
	{
		parent::before();
		
		$this->template->strHeader = ___('messages.welcome');
	}
	
	public function action_index()
	{
		// $this->response->body('hello, world!');
		$this->template->strContent = View::factory('layout/welcome');
	}

} // End Welcome
