<?php
/**
 * Config Model
 *
 * @package Classes/model
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
class Model_Config extends Model_App {
	
	/**
	 * Return the table name.
	 * 
	 * @return string
	 */
	public function getTable()
	{
		return 'worldcuppool.config';	
	}
	
	/**
	 * Get by description.
	 *
	 * @param string $strDescription Description
	 * @return array
	 */
	public function getByDescription($strDescription)
	{
		return $this->getList(array('description' => $strDescription))->current();
	}
	
}