<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Results extends Controller_Private {
	
	public function action_index() {
		$this->template->strHeader = ___('labels.results');
		
		$objDaoGame = new Model_Game;
		$objRSGames = $objDaoGame->getGames();
		
		$objViewContent = new View('layout/results');
		$objViewContent->objRSGames = $objRSGames;
		$this->template->objViewContent = $objViewContent;
	}
	
	public function action_save() {
		$arrScoreA = $this->request->post('score_a');
		$arrScoreB = $this->request->post('score_b');
		$arrFinished = $this->request->post('finished');
		
		if ( ! is_array($arrFinished))
			$arrFinished = array();

		$objDaoGame = new Model_Game;

		foreach ($arrScoreA as $intID => $intScoreA) {
			$intScoreB = $arrScoreB[$intID];
			$intStatus = isset($arrFinished[$intID])? 1 : 0;
			$arrResult = array(
				'status' => $intStatus,
				'id_game' => $intID,
				'score_a' => $intScoreA,
				'score_b' => $intScoreB
			);
			$objDaoGame->insertResult($arrResult);
		}
		Form::addMessage(Form::INFO, ___('messages.data_saved'));
		HTTP::redirect('/results');
	}
	
}