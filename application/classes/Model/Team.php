<?php
/**
 * Team Model
 *
 * @package Classes/model
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
class Model_Team extends Model_App {
	
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 0;
	
	/**
	 * Return the table name.
	 * 
	 * @return string
	 */
	public function getTable()
	{
		return 'worldcuppool.team';	
	}

}