<?php
/**
 * Generics Model
 *
 * @package Classes/model
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
abstract class Model_App extends Model_Database {

	protected static $arrID = array(); // Primary key
	protected static $arrColumns = array(); // Columns
	
	/**
	 * Obtem o nome da tabela a partir do model
	 * 
	 * @return string
	 */
	abstract public function getTable();

	/**
	 * Get primary key
	 *
	 * @return array
	 */
	public function getId()
	{
		$strClass = get_class($this);
		if ( ! isset(self::$arrID[$strClass]))
		{
			$objDB = Database::instance();
			self::$arrID[$strClass] = array();
			$objResult = $objDB->query(Database::SELECT, 'SHOW INDEX FROM '.$this->getTable());
			if ($objResult->count())
			{
				foreach ($objResult as $arrRow)
				{
					if ($arrRow['Key_name'] == 'PRIMARY') {
						self::$arrID[$strClass][] = $arrRow['Column_name'];
					}
				}
			}
		}
		return self::$arrID[$strClass];
	}

	/**
	 * Get columns
	 *
	 * @return array
	 */
	public function getColumns()
	{
		$strClass = get_class($this);
		if ( ! isset(self::$arrColumns[$strClass]))
		{
			$objDB = Database::instance();
			self::$arrColumns[$strClass] = array();
			$objResult = $objDB->query(Database::SELECT, 'DESC '.$this->getTable());
			if ($objResult->count())
			{
				foreach ($objResult as $arrRow)
				{
					self::$arrColumns[$strClass][] = $arrRow['Field'];
				}
			}
		}
		return self::$arrColumns[$strClass];
	}

	/**
	 * Builds clause WHERE to primary key
	 *
	 * @param array   $arrId             Primary key and values
	 * @param boolean $boolReturnAsArray Return as Array
	 * @return array|string Clause WHERE for Id
	 */
	protected function buildSQLId(array $arrId, $boolReturnAsArray = FALSE)
	{
		$arrFieldsId = $this->getId();
		$arrFieldsIdNew = array();
		foreach ($arrFieldsId as $intIndex => $strField)
		{
			$strValue = NULL;
			if (isset($arrId[$strField]))
			{
				$strValue = $arrId[$strField];
			} elseif (isset($arrId[$intIndex])) {
				$strValue = $arrId[$intIndex];
			}
			$arrFieldsIdNew[$strField] = is_null($strValue)? 'IS NULL' : $strValue;
		}
		if ($boolReturnAsArray)
			return $arrFieldsIdNew;

		$strSQL = $strSep = '';
		foreach ($arrFieldsIdNew as $strField => $strValue)
		{
			if ($strValue === 'IS NULL')
			{
				$strValue = 'IS NULL';
				$strSQL .= $strSep.' '.$strField.' '.$strValue.' ';
			} else
			{
				$strValue = $this->_db->escape($strValue);
				$strSQL .= $strSep.' '.$strField.' = '.$strValue.' ';
			}
			$strSep = 'AND ';
		}
		return $strSQL;
	}

	/**
	 * Gets a row based on primary key. If the key is not found, returns false.
	 *
	 * @param mixed $arrId Primary key
	 * @return stdClass
	 */
	public function get($arrId)
	{
		if ( ! is_array($arrId))
		{
			$arrId = array($arrId);
		}
		return $this->getList($this->buildSQLId($arrId, TRUE))->current();
	}

	/**
	 * Count rows
	 *
	 * @param mixed $arrWhere WHERE clause
	 * @return integer Total of rows
	 */
	public function count($arrWhere = '')
	{
		if ( ! is_array($arrWhere))
		{
			$arrWhere = array($arrWhere);
		}
		$strWhere = implode(' AND ', $arrWhere);
		if ($strWhere == '')
		{
			$strWhere = '1 = 1';
		}

		$strSQL = 'SELECT COUNT(*) AS TOTAL FROM '.$this->getTable().' WHERE '.$strWhere;
		return $this->_db->query($strSQL)->current()->TOTAL;
	}

	/**
	 * Default search
	 *
	 * @param array  $arrWhere   WHERE clause
	 * @param array  $arrFields  SELECT clause
	 * @param mixed  $arrOrderBy ORDER BY clause
	 * @param string $strLimit   LIMIT clause
	 * @return Database_Result Resource
	 */
	final public function getList(array $arrWhere, array $arrFields = array(), $arrOrderBy = '', $strLimit = '')
	{
		$strFields = implode(', ', $arrFields);
		if ($strFields == '')
		{
			$strFields = '*';
		}

		$strWhere = Util_Convert::arrayToSQL($arrWhere);
		if ( ! $strWhere)
			$strWhere = '1';

		if ( ! is_array($arrOrderBy))
		{
			$arrOrderBy = array($arrOrderBy);
		}
		$strOrderBy = implode(', ', $arrOrderBy);
		if ($strOrderBy == '')
		{
			$strOrderBy = '1';
		}

		if ($strLimit != '')
		{
			$strLimit = 'LIMIT '.$strLimit;
		}

		$strSQL = '
			SELECT
				'.$strFields.'
			FROM
				'.$this->getTable().'
			WHERE
				'.$strWhere.'
			ORDER BY
				'.$strOrderBy.'
				'.$strLimit;
		return $this->_db->query(Database::SELECT, $strSQL);
	}
	
	/**
	 * Custom search.
	 *
	 * @param integer $intType    Util_DB::RESULT_COUNT -> Total of rows, Util_DB::RESULT_ROWS -> Registers
	 * @param array   $arrWhere   WHERE clause
	 * @param mixed   $arrOrderBy ORDER BY clause
	 * @param string  $strLimit   LIMIT clause
	 * @return mixed Database_Result or integer
	 */
	public function search($intType = Util_DB::RESULT_ROWS, array $arrWhere = array(), $arrOrderBy = '', $strLimit = '')
	{
		$objRS = $this->getList($arrWhere, array(), $arrOrderBy, $strLimit);
		if ($intType == Util_DB::RESULT_ROWS)
			return $objRS;
		return $objRS->getFoundRows();
	}

	/**
	 * Inserts a row
	 *
	 * @param mixed    $arrValues   Values
	 * @param Database $objResource Database source
	 * @return Database_Result Result
	 * @throws Kohana_Exception Data type error
	 */
	public function insert($arrValues, Database $objResource = NULL)
	{
		if ( ! ($arrValues instanceof stdClass OR is_array($arrValues)))
		{
			throw new Kohana_Exception('model->insert('.$arrValues.')', 'deve ser StdClass ou Array!');
		}

		if ($arrValues instanceof stdClass)
		{
			$arrValues = Util_Convert::objectToArray($arrValues);
		}

		if ($objResource === NULL)
		{
			$objResource = $this->_db;
		}
		
		$arrKeys = array_keys($arrValues);
		$arrKeysNew = array();
		foreach ($arrKeys as $strValues) {
			if ( ! strpos($strValues, '`'))
				$strValues = '`'.$strValues.'`';
			
			$arrKeysNew[] = $strValues;
		}
		
		$strSQL = 'INSERT INTO '.$this->getTable().' ('.implode(', ', $arrKeysNew).')
		VALUES (';

		$strSep = '';
		foreach (array_values($arrValues) as $mixValue)
		{
			$strSQL .= $strSep.$this->_db->escape($mixValue);
			$strSep = ', ';
		}
		$strSQL .= ');';
		return $objResource->query(Database::INSERT, $strSQL);
	}

	/**
	 * Returns id from the last insert
	 *
	 * @param Database_Result $objResource Database resource
	 * @return integer Last id
	 */
	public function insertId(Database_Result $objResource = NULL)
	{
		if ($objResource === NULL)
		{
			$objResource = $this->_db;
		}
		return $objResource->insert_id();
	}

	/**
	 * Returns default object
	 *
	 * @param mixed $mixDefault Value
	 * @return array
	 */
	public function getModel($mixDefault = NULL)
	{
		$arrModel = array();
		foreach ($this->getColumns() as $strColumn)
		{
			$arrModel[$strColumn] = $mixDefault;
		}
		return $arrModel;
	}

	/**
	 * Updates row
	 *
	 * @param mixed    $arrValues   Values
	 * @param Database $objResource Database source
	 * @return Database_Result Result
	 * @throws Kohana_Exception Data type error
	 */
	public function update($arrValues, Database $objResource = NULL)
	{
		if ( ! ($arrValues instanceof stdClass OR is_array($arrValues)))
		{
			throw new Kohana_Exception('model->update('.$arrValues.')', 'deve ser StdClass ou Array!');
		}

		if ($arrValues instanceof stdClass)
		{
			$arrValues = convert::objectToArray($arrValues);
		}

		if ($objResource === NULL)
		{
			$objResource = $this->_db;
		}

		$arrWhere = array();
		foreach ($this->getId() as $strFieldName)
		{
			$strFieldValue = '';
			if (isset($arrValues[$strFieldName]))
			{
				$strFieldValue = $arrValues[$strFieldName];
				unset($arrValues[$strFieldName]); // remove PK from array
			}
			$arrWhere[$strFieldName] = $strFieldValue;
		}

		if ( ! count($arrWhere))
		{
			throw new Kohana_Exception('Chave não encontrada para o comando de update.');
		}

		$strSQL = 'UPDATE '.$this->getTable().'
			SET ';

		$strSep = '';
		foreach ($arrValues as $strFieldName => $mixValue)
		{
			$strSQL .= $strSep.$strFieldName.' = '.$this->_db->escape($mixValue);
			$strSep = ', ';
		}
		$strSQL .= ' WHERE ';

		$strSep = '';
		foreach ($arrWhere as $strFieldName => $mixValue)
		{
			$strSQL .= $strSep.$strFieldName.' = '.$this->_db->escape($mixValue);
			$strSep = ' AND ';
		}

		return $objResource->query(Database::UPDATE, $strSQL);
	}

	/**
	 * Removes row
	 *
	 * Ex.
	 * <code>
	 * Ex de $arrID
	 *
	 *	$arrID = array(
	 *		'field_name' => 'field_value',
	 *		'field_name' => 'field_value'
	 *	)
	 * </code>
	 *
	 * @param mixed    $arrId       Primary key value
	 * @param Database $objResource Database resource
	 * @return Database_Result Result
	 * @throws Kohana_Exception Data type error
	 */
	public function delete($arrId, Database $objResource = NULL)
	{
		if ($arrId instanceof stdClass)
		{
			$arrId = convert::objectToArray($arrId);
		}

		if ( ! is_array($arrId))
		{
			$arrId = array($arrId);
		}
		$strWhere = $this->buildSQLId($arrId);

		if ($objResource === NULL)
		{
			$objResource = $this->_db;
		}

		return $objResource->delete($this->getTable(), $strWhere);
	}

	/**
	 * Last query
	 *
	 * @return string
	 */
	public function getSQL()
	{
		return $this->_db->last_query();
	}

	/**
	 * Escapes any input value.
	 *
	 * @param mixed $mixValue Value to escape
	 * @return string
	 */
	public function escape($mixValue)
	{
		return $this->_db->escape($mixValue);
	}

	/**
	 * Merges row
	 *
	 * @param mixed    $arrValues   Values
	 * @param Database $objResource Database resource
	 * @return Database_Result Result
	 * @throws Kohana_Exception Data type error
	 */
	public function merge($arrValues, Database $objResource = NULL)
	{
		if ( ! ($arrValues instanceof stdClass OR is_array($arrValues)))
		{
			throw new Kohana_Exception('model->update('.$arrValues.')', 'deve ser StdClass ou Array!');
		}

		if ($arrValues instanceof stdClass)
		{
			$arrValues = convert::objectToArray($arrValues);
		}

		if ($objResource === NULL)
		{
			$objResource = $this->_db;
		}
		return $objResource->merge($this->getTable(), $arrValues);
	}

}