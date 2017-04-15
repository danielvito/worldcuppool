<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Bet extends Controller_Private {
	
	public function before()
	{
		parent::before();
		$objDaoConfig = new Model_Config;
		$arrPalpites = $objDaoConfig->getByDescription('palpites');
		$this->boolPalpites = ( ! isset($arrPalpites['value']) OR $arrPalpites['value'] != 'on')? FALSE : TRUE;
		
		$arrPalpites = $objDaoConfig->getByDescription('palpites_adicionais');
		$this->boolPalpitesAdicionais = ( ! isset($arrPalpites['value']) OR $arrPalpites['value'] != 'on')? FALSE : TRUE;
	}
	
	public function action_index() {
		$this->template->strHeader = ___('labels.bet');
		
		$objDaoTeam = new Model_Team;
		$arrTeams = $objDaoTeam->getList(array('status' => 1))->as_array('id', 'name');
		
		$objDaoGameUser = new Model_GameUser;
		$objRSGames = $objDaoGameUser->getGamesByUser(Util_App::session('USR.id'));
		
		$objDaoUserBet = new Model_UserBet;
		$arrDS = $objDaoUserBet->get(Util_App::session('USR.id'));
		
		$objViewContent = new View('layout/bet');
		$objViewContent->arrTeams = $arrTeams;
		$objViewContent->objRSGames = $objRSGames;
		$objViewContent->arrDS = $arrDS;
		$objViewContent->boolPalpites = $this->boolPalpites;
		$objViewContent->boolPalpitesAdicionais = $this->boolPalpitesAdicionais;
		$this->template->objViewContent = $objViewContent;
	}
	
	public function action_save() {
		if ( ! $this->boolPalpites) {
			Form::addMessage(Form::ERROR, ___('messages.bet_closed'));
			HTTP::redirect('/bet');
		}

		$arrScoreA = $this->request->post('score_a');
		$arrScoreB = $this->request->post('score_b');

		$objDaoGameUser = new Model_GameUser;
		
		foreach ($arrScoreA as $intID => $intScoreA) {
			$intScoreB = $arrScoreB[$intID];
			$arrBet = array(
				'status' => 1,
				'id_user' => Util_App::session('USR.id'),
				'id_game' => $intID,
				'score_a' => $intScoreA,
				'score_b' => $intScoreB
			);
			$objDaoGameUser->insertBet($arrBet);
		}
		
		if ($this->boolPalpitesAdicionais) {
			// user bets
			$arrUserBet = array(
				'id' => Util_App::session('USR.id'),
				'id_team1' => $this->request->post('id_team1'),
				'id_team2' => $this->request->post('id_team2'),
				'id_team3' => $this->request->post('id_team3'),
				'striker' => $this->request->post('striker'),
			);
			
			$objDaoUserBet = new Model_UserBet;
			$arrDS = $objDaoUserBet->get(Util_App::session('USR.id'));
			if ( ! $arrDS) {
				$arrUserBet['create'] = Util_Date::toSQL(NULL, TRUE);
				$objDaoUserBet->insert($arrUserBet);
			} else
				$objDaoUserBet->update($arrUserBet);
		}
		
		Form::addMessage(Form::INFO, ___('messages.data_saved'));
		HTTP::redirect('/bet');
	}
	
}