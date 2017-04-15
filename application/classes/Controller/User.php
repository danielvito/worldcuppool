<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Private {
	
	
	public function action_mydata()
	{
		$this->template->strHeader = ___('labels.configuration');
		$objView = View::factory('user/mydata');
		$objView->arrUser = Util_App::session('USR');
		$this->template->objViewContent = $objView;
	}
	
	public function action_save() {
		$strName = $this->request->post('name');
		$strNickName = $this->request->post('nickname');
		$strPass = $this->request->post('password');
		$strPass2 = $this->request->post('password2');
		
		$arrUser = array(
			'id' => Util_App::session('USR.id'),
			'name' => $strName,
			'nickname' => $strNickName,
		);
		
		if ( ! $strName) {
			Form::addMessage(Form::ERROR, ___('errors.fillup_name'));
			HTTP::redirect('/user/mydata');
		}
		
		if ( ! $strNickName) {
			Form::addMessage(Form::ERROR, ___('errors.fillup_nickname'));
			HTTP::redirect('/user/mydata');
		}
		
		if ($strPass OR $strPass2) {
		
			if ( ! $strPass OR ! $strPass2) {
				Form::addMessage(Form::ERROR, ___('errors.fillup_passwords'));
				HTTP::redirect('/user/mydata');
			}
			
			if ($strPass != $strPass2) {
				Form::addMessage(Form::ERROR, ___('errors.passwords_must_be_the_same'));
				HTTP::redirect('/user/mydata');
			}
			
			$arrUser['password'] = sha1($strPass);

		}
		
		$objDaoUser = new Model_User;
		$objDaoUser->update($arrUser);
		
		$arrUserUpdate = Util_App::session('USR');
		$arrUserUpdate['name'] = $arrUser['name'];
		$arrUserUpdate['nickname'] = $arrUser['nickname'];
		
		Util_App::setSession('USR', $arrUserUpdate);
		
		Form::addMessage(Form::SUCCESS, ___('messages.data_saved'));
		HTTP::redirect('/user/mydata');
		
	}

}