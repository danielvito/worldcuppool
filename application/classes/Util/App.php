<?php
/**
 * App Utils
 *
 * @package Classes/util
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
class Util_App {

	const TYPE_RAND_NUMBER = 1;
	const TYPE_RAND_ALPHA = 2;
	const TYPE_RAND_NUMBER_ALPHA = 3;
	const TYPE_RAND_SPECIAL = 4;
	const TYPE_RAND_ALL = 5;
	
	/**
	 * Checks if is a prompt execution
	 *
	 * @param Controller $objController Current controller execution
	 * @return boolean
	 */
	public static function isPrompt(Controller $objController)
	{
		return (php_sapi_name() == 'cli' OR ($objController instanceof Controller_Cron) OR defined('STDIN'))? TRUE : FALSE;
	}
	
	
	/**
	 * Retrieves system pages list
	 * 
	 * @return array
	 */
	public static function getListSystemPages()
	{
		return array('error', 'welcome');
	}
	
	public static function getMenus() {
		$arrMenus = array(
			array('/', 'Home'),
			array('/rules', ___('labels.rules')),
			array('/bet', ___('labels.bet')),
			array('/ranking', ___('labels.ranking')),
		);
		
		if (Util_App::session('USR.master')) {
			$arrMenus[] = array('/results', ___('labels.results'));
		}
		
		return $arrMenus;
	}
	
	/**
	 * Loads templates.
	 * 
	 * @param string $strTemplate Template
	 * @param View   $objView     View to be put inside the template
	 * @param array  $arrVars     Variables to be sent to the view
	 * @return View
	 */
	public static function template($strTemplate, View $objView, array $arrVars = array())
	{
		$objViewTpl = View::factory('templates/tpl_'.$strTemplate);
		foreach ($arrVars as $strName => $mixValue)
			$objViewTpl->$strName = $mixValue;
		$objViewTpl->strContent = $objView->render();
		return $objViewTpl;
	}
	
	/**
	 * Retrieves values from configuration files
	 * 
	 * @param string $strPath         Configuration path
	 * @param mixed  $mixValueDefault Value Default
	 * @param string $strContext      Context
	 * @return string
	 */
	public static function config($strPath, $mixValueDefault = NULL, $strContext = 'config')
	{
		$arrConfig = parse_ini_file(APPPATH.'../../files/'.$strContext.'.php', TRUE);
		return self::exploreArray($arrConfig, $strPath, $mixValueDefault);
	}
	
	/**
	 * Retrieves session's values
	 * 
	 * @param string $strPath         Array path
	 * @param mixed  $mixValueDefault Value default
	 * @return mixed
	 */
	public static function session($strPath, $mixValueDefault = NULL)
	{
		$objSession = Session::instance();
		return self::exploreArray($objSession->as_array(), $strPath, $mixValueDefault); 
	}
	
	/**
	 * Set session's values
	 * 
	 * @param string $strPath  Array path
	 * @param mixed  $mixValue Value
	 * @return mixed
	 */
	public static function setSession($strPath, $mixValue)
	{
		$objSession = Session::instance();
		$objSession->set($strPath, $mixValue);
	}
	
	/**
	 * Explores array through index path
	 * 
	 * @param array  $arrValues       Array values
	 * @param string $strPath         Array path
	 * @param mixed  $mixValueDefault Value default
	 * @return mixed
	 */
	public static function exploreArray(array $arrValues, $strPath, $mixValueDefault = NULL)
	{
		$arrTemp = $arrValues;
		
		$arrPath = explode('.', $strPath);		
		
		$mixValue = $mixValueDefault;
		foreach ($arrPath as $strIndex)
		{
			if (isset($arrTemp[$strIndex]))
			{
				$mixValue = $arrTemp = $arrTemp[$strIndex];				
			}
			else
				return $mixValueDefault;
		}
		
		return $mixValue;		
	}

	/**
	 * Checks if user is logged in
	 *
	 * @return boolean
	 */
	public static function isLoggedIn()
	{
		$objSession = Session::instance();
		$arrUser = $objSession->get('USR');
		if (empty($arrUser))
			return FALSE;
		return TRUE;
	}
	
	/**
	 * Logs a user in
	 *
	 * @param string $strLogin User login
	 * @param string $strPass  User password
	 * @return boolean
	 */
	public static function login($strLogin, $strPass)
	{
		$objSession = Session::instance();
		$objSession->restart();
		
		$objDaoUser = new Model_User;
		
		if ( ! $objDaoUser->authenticate($strLogin, $strPass))
			return FALSE;

		$arrUser = $objDaoUser->getByLogin($strLogin);
		if ( ! $arrUser)
			return FALSE;

		/*
		if ( ! $arrUser['DSC_LOCALE']) {
			$objDaoLocale = new Model_Locale;
			$arrLocale = $objDaoLocale->getList(array('IND_DEFAULT' => 1))->current();
			$arrUser['DSC_LOCALE'] = $arrLocale['DSC_LOCALE'];
			$arrUser['DSC_LANG'] = $arrLocale['DSC_LANG'];
		}
		
		if ( ! $arrUser['DSC_TIMEZONE']) {
			$objDaoTimezone = new Model_Timezone;
			$arrTimezone = $objDaoTimezone->getList(array('IND_DEFAULT' => 1))->current();
			$arrUser['DSC_TIMEZONE'] = $arrTimezone['DSC_TIMEZONE'];
		}

		$objDaoUserProfile = new Model_UserProfile;
		$arrProfiles = $objDaoUserProfile->getProfilesByUser($arrUser['COD_USER'])->as_array();
		*/

		$objSession = Session::instance();
		$objSession->set('USR', $arrUser);
		// $objSession->set('PROFILES', $arrProfiles);
		
		$objDaoUserLog = new Model_UserLog;
		$objDaoUserLog->insert(array('id_user' => $arrUser['id']));
		
		return TRUE;
	}

	/**
	 * Logs a user out
	 *
	 * @return void
	 */
	public static function logout()
	{
		$objSession = Session::instance();
		$objSession->destroy();
	}

	/**
	 * Creates a random string
	 *
	 * @param integer $intLength       String length
	 * @param integer $intType         Type
	 * @param boolean $boolAllowRepeat Indicates if it's allowed to repeat
	 * @return string
	 */
	public static function random($intLength, $intType = 3, $boolAllowRepeat = FALSE)
	{
		$arrCharacters = array();
		if (in_array($intType, array(self::TYPE_RAND_ALPHA, self::TYPE_RAND_NUMBER_ALPHA, self::TYPE_RAND_ALL)))
		{
			$arrCharacters = array_merge($arrCharacters,
					array(
							'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M',
							'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
					)
			);
		}
		if (in_array($intType, array(self::TYPE_RAND_NUMBER, self::TYPE_RAND_NUMBER_ALPHA, self::TYPE_RAND_ALL)))
		{
			$arrCharacters = array_merge($arrCharacters, array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0'));
		}
		if (in_array($intType, array(self::TYPE_RAND_SPECIAL, self::TYPE_RAND_ALL)))
		{
			$arrCharacters = array_merge($arrCharacters, array('!', '@', '#', '$', '%', '&', ';', ',', '.', '*'));
		}

		$arrKeys = array();
		while (count($arrKeys) < $intLength)
		{
			$strIndex = mt_rand(0, count($arrCharacters) - 1);
			if ($boolAllowRepeat OR ! in_array($arrCharacters[$strIndex], $arrKeys))
			{
				$arrKeys[] = $arrCharacters[$strIndex];
			}
		}

		return implode('', $arrKeys);
	}

}