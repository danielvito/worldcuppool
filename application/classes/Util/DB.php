<?php
/**
 * Database Utils
 *
 * @package Classes/util
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
class Util_DB {

	const RESULT_COUNT = 1;
	const RESULT_ROWS = 2;
	
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 0;
	
	const UNDEFINED = -3;
	
	/**
	 * Finds description based in a ID by using the model default interface.
	 * 
	 * @param mixed  $mixCode     ID
	 * @param string $strModel    Model
	 * @param string $strProperty Property name
	 * @return string
	 */
	public static function getBDRegisterProperty($mixCode, $strModel, $strProperty = 'DSC_NAME')
	{
		if ( ! is_array($mixCode))
			$mixCode = array($mixCode);
		$objDAO = new $strModel;
		
		$strReturn = $strSep = '';
		foreach ($mixCode as $mixValue) {
			$arrModel = $objDAO->get($mixValue);
			if (isset($arrModel[$strProperty])) {
				$strReturn .= $strSep.$arrModel[$strProperty];
				$strSep = ', ';
			}
		}
		return $strReturn;
	}
	
	/**
	 * Transforms a result set into a matrix indexed by a specified key
	 * 
	 * @param Database_Result $objRS    Result Set
	 * @param string          $strIndex Column index
	 * @return array
	 */
	static public function createListByParameter(Database_Result $objRS, $strIndex)
	{
		$arrMatrix = array();
		foreach ($objRS as $arrDS)
			$arrMatrix[$arrDS[$strIndex]] = $arrDS;
		return $arrMatrix;
	}
	
	/**
	 * Escape values
	 * 
	 * @param mixed $mixValue Value
	 * @return string
	 */
	static public function escape($mixValue)
	{
		$objDB = Database::instance();
		return $objDB->escape($mixValue);
	}

}