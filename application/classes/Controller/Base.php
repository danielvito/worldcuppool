<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Base extends Controller_Template {
	
	public $template = 'layout/template';
	
	/**
	 * Before
	 * 
	 * @return void
	 */
	public function before()
	{
		parent::before();
		
		Util_Media::style('shared/layout');
		// Util_Media::style('shared/template');

		Util_Media::script('shared/app');
		// Util_Media::script('shared/functions');
		
		$this->template->strHeader = '';
		$objViewTop = new View('layout/top');
		$objViewTop->arrMenus = Util_App::getMenus();
		$this->template->objViewTop = $objViewTop;
		$this->template->objViewTop->strController = UTF8::strtolower($this->request->controller());
		$this->template->objViewTop->strAction = UTF8::strtolower($this->request->action());
		
		$objViewBottom = new View('layout/bottom');
		$this->template->objViewBottom = $objViewBottom;
		
		$objViewContent = new View('layout/welcome');
		$this->template->objViewContent = $objViewContent;
	}
	
}