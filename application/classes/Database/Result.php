<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Database_Result
 *
 * @package Classes/database
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
abstract class Database_Result extends Kohana_Database_Result
{
	protected $intFoundRows = 0;

	/**
	 * Sets the total of rows without considering LIMIT clause
	 *
	 * @param integer $intFoundRows Found rows
	 * @return void
	 */
	public function setFoundRows($intFoundRows)
	{
		$this->intFoundRows = $intFoundRows;
	}
	
	/**
	 * Returns the total of rows without considering LIMIT clause
	 *
	 * @return integer
	 */
	public function getFoundRows()
	{
		return $this->intFoundRows;
	}
	
}