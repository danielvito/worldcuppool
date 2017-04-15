<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Database_MySQL
 *
 * @package Classes/database
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
class Database_MySQL extends Kohana_Database_MySQL
{
	
	/**
	 * Perform an SQL query of the given type.
	 *
	 * Sample
	 *     // Make a SELECT query and use objects for results
	 *     $db->query(Database::SELECT, 'SELECT * FROM groups', TRUE);
	 *
	 *     // Make a SELECT query and use "Model_User" for the results
	 *     $db->query(Database::SELECT, 'SELECT * FROM users LIMIT 1', 'Model_User');
	 *
	 * @param integer $intType     Database::SELECT, Database::INSERT, etc
	 * @param string  $strSQL      SQL query
	 * @param mixed   $mixAsObject Result object class string, TRUE for stdClass, FALSE for assoc array
	 * @param array   $arrParams   Object construct parameters for result class
	 * @return mixed Database_Result Result for SELECT queries, array List (insert id, row count) for INSERT queries, integer Number of affected rows for all other queries
	 */
	public function query($intType, $strSQL, $mixAsObject = FALSE, array $arrParams = NULL)
	{
		if ($intType == self::SELECT AND preg_match('#\b(?:SELECT)\b#i', $strSQL) AND ! strstr($strSQL, 'SQL_CALC_FOUND_ROWS'))
				$strSQL = preg_replace('#\b(?:SELECT)\b#i',
						'SELECT SQL_CALC_FOUND_ROWS', $strSQL, 1);
		$objResult = parent::query($intType, $strSQL, $mixAsObject, $arrParams);
		
		if ($intType == self::SELECT) {
			$objRSFoundRows = mysql_query('SELECT FOUND_ROWS() AS FOUND_ROWS;', $this->_connection);
			$objDS = mysql_fetch_object($objRSFoundRows);
			if (isset($objDS->FOUND_ROWS) AND $objDS->FOUND_ROWS)
				$objResult->setFoundRows($objDS->FOUND_ROWS);
		}
		
		return $objResult;
	}
	
}