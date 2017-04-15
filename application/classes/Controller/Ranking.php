<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ranking extends Controller_Private {
	
	public function before()
	{
		parent::before();
		$objDaoConfig = new Model_Config;
		$arrPalpites = $objDaoConfig->getByDescription('palpites');
		$this->boolPalpites = ( ! isset($arrPalpites['value']) OR $arrPalpites['value'] != 'on')? FALSE : TRUE;
	}

	public function action_index() {
		$this->template->strHeader = ___('labels.ranking');
		
		// team
		$objDaoTeam = new Model_Team;
		$arrTeams = $objDaoTeam->getList(array('status' => 1))->as_array('id', 'name');
		
		// games result
		$objDaoGame = new Model_Game;
		$objRSGames = $objDaoGame->getGames();
		
		$intTotalGames = $objRSGames->count();
		$intTotalPlayed = 0;
		foreach ($objRSGames as $arrDS) {
			if ($arrDS['status'])
				$intTotalPlayed ++;
		}
		
		// users
		$objDaoUser = new Model_User;
		$arrUsers = $objDaoUser->getList(array('status' => '1'), array(), 'nickname')->as_array('id', 'nickname');
		
		// users games and bets
		$objDaoGameUser = new Model_GameUser;
		$objDaoUserBet = new Model_UserBet;

		// points
		$arrPointsUser = array();
		$arrPointsUserGame = array();

		// user
		$arrUserGames = array();
		$arrUserBets = array();
		foreach ($arrUsers as $intID => $strNickName) {
			$arrGameUser = array();
			foreach ($objDaoGameUser->getGamesByUser($intID) as $arrDS)
				$arrGameUser[$arrDS['id']] = $arrDS;
			$arrUserGames[$intID] = $arrGameUser;
			
			$arrUserBets[$intID] = $objDaoUserBet->get($intID);
			$arrPointsUser[$intID] = $arrUserBets[$intID]['team1_points'] + $arrUserBets[$intID]['team2_points']
				+ $arrUserBets[$intID]['team3_points'] + $arrUserBets[$intID]['striker_points'];
		}

		foreach ($arrUserGames as $intUserID => $arrDS) {
			if ( ! isset($arrPointsUser[$intUserID]))
				$arrPointsUser[$intUserID] = 0;
			foreach ($arrDS as $intGameID => $arrDS2) {
				$intPoints = 0;
				
				if ($arrDS2['status'] == 1 AND $arrDS2['user_status'] == 1)
					$intPoints = Util_Business::gamePoints($arrDS2['score_a'], $arrDS2['score_b'], $arrDS2['score_a_user'], $arrDS2['score_b_user']);

				$arrPointsUserGame[$intUserID][$intGameID] = $intPoints;
				$arrPointsUser[$intUserID] += $intPoints;
			}
		}
		arsort($arrPointsUser);
		
		$objViewContent = new View('layout/ranking');
		$objViewContent->boolPalpites = $this->boolPalpites;
		$objViewContent->objRSGames = $objRSGames;
		$objViewContent->arrUsers = $arrUsers;
		$objViewContent->arrUserGames = $arrUserGames;
		$objViewContent->arrUserBets = $arrUserBets;
		$objViewContent->arrPointsUser = $arrPointsUser;
		$objViewContent->arrPointsUserGame = $arrPointsUserGame;
		$objViewContent->arrTeams = $arrTeams;
		$objViewContent->intTotalPlayed = $intTotalPlayed;
		$objViewContent->intTotalGames = $intTotalGames;
		$this->template->objViewContent = $objViewContent;
	}
	
}