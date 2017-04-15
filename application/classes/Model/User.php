<?php
/**
 * User Model
 *
 * @package Classes/model
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
class Model_User extends Model_App {
	
	/**
	 * Return the table name.
	 * 
	 * @return string
	 */
	public function getTable()
	{
		return 'worldcuppool.user';	
	}
	
	/**
	 * Authenticate the user.
	 * 
	 * @param string $strLogin Login
	 * @param string $strPass  Password
	 * @return array
	 */
	public function authenticate($strLogin, $strPass)
	{
		$strSQL = '
			SELECT
				COUNT(1) AS QTD_TOTAL
			FROM
				worldcuppool.user U
			WHERE
				U.email = '.$this->_db->escape($strLogin).'
				AND U.password = '.$this->_db->escape(sha1($strPass)).'
				AND U.status = 1'
		;
		$arrDS = $this->_db->query(Database::SELECT, $strSQL)->current();
		return ( ! $arrDS OR ! $arrDS['QTD_TOTAL'])? FALSE : TRUE;
	}
	
	/**
	 * Reset user password.
	 * 
	 * @param string $COD_USER   User code
	 * @param string $strNewPass New Password
	 * @return integer
	 */
	public function resetPassword($COD_USER, $strNewPass)
	{
		$strSQL = '
			UPDATE
				worldcuppool.user U
			SET
				DSC_PASS = '.$this->_db->escape(sha1($strNewPass)).'
			WHERE
				U.COD_USER = '.$this->_db->escape($COD_USER)
		;
		return $this->_db->query(Database::UPDATE, $strSQL);
	}
	
	/**
	 * Authenticate the user.
	 * 
	 * @param string $strLogin Login
	 * @return array
	 */
	public function getByLogin($strLogin) 
	{
		$strSQL = '
			SELECT
				U.id, U.email, U.name, U.status, U.nickname, U.master
			FROM
				worldcuppool.user U
			WHERE
				U.email = '.$this->_db->escape($strLogin).'
				AND U.status = 1'
		;
		return $this->_db->query(Database::SELECT, $strSQL)->current();
	}
	
	/**
	 * List all user's menus.
	 * 
	 * @param integer $COD_USER User code
	 * @return array
	 */
	public function getMenusByUser($COD_USER)
	{
		$strSQL = '
			SELECT DISTINCT
				G.COD_GROUP, G.DSC_NAME, M.COD_MENU, M.DSC_NAME,
				M.DSC_FOLDER, M.DSC_CONTROLLER, M.DSC_ACTION,
				M.COD_MENU_PARENT, M.NUM_ORDER
			FROM
				pdc_sys.group G
				INNER JOIN pdc_sys.menu M ON M.COD_GROUP = G.COD_GROUP AND M.COD_STATUS = 1
				INNER JOIN pdc_sys.menuprofile MP ON MP.COD_MENU = M.COD_MENU
				INNER JOIN pdc_sys.userprofile UP ON
					UP.COD_PROFILE = MP.COD_PROFILE AND UP.COD_STATUS = 1
					AND UP.COD_USER = '.$this->_db->escape($COD_USER).'
			WHERE
				G.COD_STATUS = 1
			ORDER BY
				G.NUM_ORDER, M.COD_MENU_PARENT, M.NUM_ORDER;
		';
		$arrMenus = $this->_db->query(Database::SELECT, $strSQL);
		
		$arrMenusReference = array();
		foreach ($arrMenus as $arrDS)
			$arrMenusReference[$arrDS['COD_MENU_PARENT']][] = $arrDS;

		$arrOrderedMenus = array();
		$this->orderMenus($arrMenusReference, $arrOrderedMenus, 0);
		return $arrOrderedMenus;
	}
	
}