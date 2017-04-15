<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Rules extends Controller_Public {
	
	public function action_index() {
		$this->template->strHeader = ___('labels.rules');
		
		$objViewContent = new View('layout/rules');
		$this->template->objViewContent = $objViewContent;
	}
	
}