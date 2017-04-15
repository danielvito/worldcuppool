<?php
/**
 * App convert
 *
 * @package Classes/util
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
class Util_Convert {

	// constantes do método convert::arrayToSQL()
	const WHERE  = 1;
	const SELECT = 2;
	const INSERT = 3;
	const UPDATE = 4;
	const MATRIZ = 5;

	/**
	 * Converts array to object
	 *
	 * @param mixed $arrIn Array
	 * @return stdClass
	 */
	static public function arrayToObject($arrIn)
	{
		if ( ! is_array($arrIn))
			return $arrIn;

		$objOut = new stdClass;
		foreach ($arrIn as $strKey => $mixValue)
		{
			$objOut->$strKey = self::arrayToObject($mixValue);
		}
		return $objOut;
	}

	/**
	 * Converts stdClass to array
	 *
	 * @param mixed $objIn Value
	 * @return array
	 */
	static public function objectToArray($objIn)
	{
		if ( ! ($objIn instanceof stdClass))
			return $objIn;

		foreach ($objIn as $strKey => $mixValue)
		{
			$arrOut[$strKey] = self::objectToArray($mixValue);
		}

		return $arrOut;
	}

	/**
	 * Converts array to string
	 *
	 * @param array   $arrIn      Array
	 * @param string  $strDel1    Delimiter 1
	 * @param string  $strDel2    Delimiter 2
	 * @param boolean $boolFinals If TRUE, delimiter 1 will be used on start and end of the string
	 * @return string String
	 * @throws Kohana_Exception Data type error
	 */
	public static function arrayToString(array $arrIn, $strDel1 = '|', $strDel2 = ':', $boolFinals = TRUE)
	{
		if ( ! is_array($arrIn))
			return $arrIn;
		if ( ! count($arrIn))
			return '';
		$strOut = $strDel1;
		foreach ($arrIn as $mixKey => $strValue)
		{
			if ( ! strstr($mixKey.$strValue, $strDel1) AND ! strstr($mixKey.$strValue, $strDel2))
			{
				$strOut .= trim($mixKey).$strDel2.trim($strValue).$strDel1;
			}
			else
			{
				throw new Kohana_Exception('Util_Convert::arrayToString()', 'Os caracteres "'.$strDel1.'" e "'.$strDel2.'" não são permitidos no array');
			}
		}
		if ( ! $boolFinals)
		{
			$strOut = substr($strOut, strlen($strDel1), strlen($strOut) - strlen($strDel1) * 2);
		}
		return $strOut;
	}

	/**
	 * Converts string to array
	 *
	 * @param string $strIn   String
	 * @param string $strDel1 Delimiter 1
	 * @param string $strDel2 Delimiter 2
	 * @return array Array
	 */
	public static function stringToArray($strIn, $strDel1 = '|', $strDel2 = ':')
	{
		$intTam = strlen($strDel1);
		if (substr($strIn, 0, $intTam) == $strDel1)
		{
			$strIn = substr($strIn, $intTam, strlen($strIn) - ($intTam * 2));
		}
		$arrMid = explode($strDel1, $strIn);
		$arrOut = array();
		foreach ($arrMid as $strValue)
		{
			if (strstr($strValue, $strDel2))
			{
				$arrItem = explode($strDel2, $strValue);
				$arrOut[$arrItem[0]] = $arrItem[1];
			}
		}
		return $arrOut;
	}

	/**
	 * Converts result to array
	 *
	 * @param Database_Result $objResult Result from a query
	 * @param string          $strIndex  Key name
	 * @param string          $strValue  Value name
	 * @param mixed	          $mixFirst  First value
	 * @return array
	 * @throws Kohana_Exception Data type error
	 */
	public static function dataBaseResultToArray(Database_Result $objResult, $strIndex = NULL, $strValue = NULL, $mixFirst = NULL)
	{
		if ($strIndex === NULL AND $strValue === NULL)
		{
			throw new Kohana_Exception('Util_Convert::dataBaseResultToArray()', 'É necessário passar o campo chave e/ou o campo valor');
		}

		if ($strValue === NULL AND $strIndex !== NULL)
		{
			$strValue = $strIndex;
		}

		$arrVetor = array();

		// adiciona o primeiro elemento ao vetor, se houver
		if ($mixFirst !== NULL AND is_string($mixFirst))
		{
			$arrVetor[] = $mixFirst;
		}
		elseif (is_array($mixFirst) AND count($mixFirst))
		{
			list($strChave, $strValue) = each($mixFirst);
			$arrVetor[$strChave] = $strValue;
		}

		// percorre o dataset
		if ($objResult->count()) {
			$boolIsArray = FALSE;
			if (is_array($objResult->current())) {
				$objResult->result(TRUE); // usa resultado como objeto
				$boolIsArray = TRUE;
			}
			$objResult->rewind();
			foreach ($objResult as $arrRow)
			{
				if ($strIndex !== NULL)
				{
					$arrVetor[$arrRow->$strIndex] = $arrRow->$strValue;
				}
				else
				{
					$arrVetor[] = $arrRow->$strValue;
				}
			}
			$objResult->rewind();
			if ($boolIsArray)
			{
				$objResult->result(FALSE);
			}
		}

		return $arrVetor;
	}

	/**
	 * Converts string XML to array.
	 *
	 * @param string $strXML XML
	 * @return array
	 */
	public static function xmlToArray($strXML)
	{
		// @TODO import Util_XML
		return Util_XML::toArray($strXML);
	}

	/**
	 * Converts array to XML
	 *
	 * @param array   $arrIn       Array to convert
	 * @param string  $strNodeName Prefix to numeric nodes
	 * @param boolean $boolNumeric If true include numbers to nodes
	 * @return string
	 */
	public static function arrayToXML(array $arrIn, $strNodeName = 'Item', $boolNumeric = TRUE)
	{
		// @TODO import Util_XML
		return xml::arrayToXML($arrIn, $strNodeName, $boolNumeric);
	}

	/**
	 * Converts array to a SQL clause
	 *
	 * @param array   $arrPairs     Pairs
	 * @param integer $intFormat    Sample:
	 *                WHERE:	chave1 = 'valor1' AND chave2 = 'valor2' AND ... AND chaveN = 'valorN' (também permite passar OR como separador).
	 *                OBS: permite usar os operadores: > < = !, IS NULL, IS NOT NULL, LIKE e BETWEEN nas chaves.
	 *                Se o valor for um array sem operador na chave, será convertido para "chave IN (valor1, valor2, ..., valorN)".
	 *                (para NOT IN adicione ' NOT IN' na chave desejada, ex: 'COD_AREA NOT IN', 'COD_UNNEGOCIO NOT IN').
	 *                (para BETWEEN adicione ' BETWEEN' na chave desejada, ex: 'COD_AREA BETWEEN', 'COD_UNNEGOCIO NOT BETWEEN').
	 *                SELECT:	'valor1', 'valor2', ..., 'valorN' (ignora as chaves do array original)
	 *                INSERT:	(chave1, chave2, ..., chaveN) VALUES ('valor1', 'valor2', ..., 'valorN')
	 *                UPDATE:	chave1 = 'valor1', chave2 = 'valor2', ..., chaveN = 'valorN'
	 *                MATRIZ:	array('keys'	=> "'chave1', 'chave2', ..., 'chaveN'", 'values'	=> "'valor1', 'valor2', ..., 'valorN'")
	 * @param string  $strAlias     Alias da tabela a ser anexado aos campos (opcional)
	 * @param string  $strSeparator Separador usado entre os pares no formato WHERE (AND ou OR)
	 * @return string String SQL
	 * @throws Kohana_Exception Data type error
	 */
	public static function arrayToSQL(array $arrPairs, $intFormat = self::WHERE, $strAlias = '', $strSeparator = 'AND')
	{
		$strRet = $strRet2 = '';
		if ($strAlias)
		{
			$strAlias .= '.';
		}

		switch ($intFormat)
		{
			case self::WHERE:
				foreach ($arrPairs as $mixKey => $mixValue)
				{
					if (is_object($mixValue))
					{
						continue;
					}
					if ($strRet)
					{
						$strRet .= ' '.$strSeparator.' ';
					}
					if (is_array($mixValue))
					{
						// IN OR BETWEEN
						$strOperador = ( ! preg_match('!( IN| BETWEEN)$!i', $mixKey))? 'IN' : '';
						$boolIsBetween = preg_match('!BETWEEN$!i', $mixKey);
						if ($boolIsBetween AND count($mixValue) != 2)
						{
							throw new Kohana_Exception('Util_Convert::arrayToSQL()', 'O array usado no BETWEEN deve conter exatamente 2 elementos.');
						}
						$strValue = ($boolIsBetween)? '' : '(';
						$strGlue = '';
						foreach ($mixValue as $strValueAux)
						{
							// not escape variables
							if ( ! preg_match('!^@!', $strValueAux))
							{
								$strValue .= $strGlue.Util_DB::escape($strValueAux);
							}
							else
							{
								$strValue .= $strGlue.$strValueAux;
							}
							$strGlue = ($boolIsBetween)? ' AND ' : ', ';
						}
						$strValue .= ($boolIsBetween)? '' : ')';
					} else {
						// processa valor escalar
						$strOperador = '= ';
						if (preg_match('/^NULL$/i', $mixValue))
						{
							$mixValue = 'IS NULL';
						}
						if (preg_match('/^NOT NULL$/i', $mixValue))
						{
							$mixValue = 'IS NOT NULL';
						}
						$strValue = $mixValue;
						preg_match('/^([<>!=]+|IS NOT NULL|IS NULL|NOT IN[ ]*\(|IN[ ]*\(|NOT BETWEEN |BETWEEN |NOT LIKE |LIKE )(.*)$/i', $mixValue, $arrMatches);
						if (isset($arrMatches[1]))
						{
							$strOperador = $arrMatches[1];
						}
						if (preg_match('/( IN| BETWEEN| LIKE| BINARY)$/i', $mixKey))
						{
							$strOperador = '';
						}
						if (preg_match('/^(IS NULL|IS NOT NULL)$/i', $strOperador))
						{
							$strValue = '';
						}
						else {
							if (isset($arrMatches[2]))
							{
								$strValue = trim($arrMatches[2]);
							}
							if ( ! preg_match('!^@!', $strValue) AND ! preg_match('/(IN|BETWEEN)/i', $strOperador))
							{
								$strValue = Util_DB::escape($strValue);
							}
						}
					}
					$strRet .= $strAlias.$mixKey.' '.$strOperador.''.$strValue;
				}
				return trim($strRet);
				break;
			case self::SELECT:
				foreach ($arrPairs as $mixKey => $mixValue) {
					// somente valores escalares
					if (is_array($mixValue) OR is_object($mixValue))
						continue;
					if ($strRet)
					{
						$strRet .= ', ';
					}
					$strRet .= $strAlias.'`'.$mixValue.'`';
				}
				return $strRet;
				break;
			case self::INSERT:
				foreach ($arrPairs as $mixKey => $mixValue) {
					if (is_array($mixValue) OR is_object($mixValue))
						continue;
					if ($strRet)
					{
						$strRet .= ', ';
					}
					if ($strRet2)
					{
						$strRet2 .= ', ';
					}
					$strRet .= ($strRet == '')? '('.$strAlias.$mixKey : $strAlias.$mixKey;
					// não escapa variaveis e nulos
					if ( ! preg_match('!(^@|^NULL$)!', $mixValue))
					{
						$strRet2 .= Util_DB::escape($mixValue);
					}
					else
						$strRet2 .= $mixValue;
				}
				return $strRet.') VALUES ('.$strRet2.')';
				break;
			case self::UPDATE:
				foreach ($arrPairs as $mixKey => $mixValue) {
					if (is_array($mixValue) OR is_object($mixValue))
						continue;
					if ($strRet)
					{
						$strRet .= ', ';
					}
					if ( ! preg_match('!(^@|^NULL$)!', $mixValue))
					{
						$strValue = Util_DB::escape($mixValue);
					}
					else
						$strValue = $mixValue;
					$strRet .= $strAlias.$mixKey.' = '.$strValue;
				}
				return $strRet;
				break;
			case self::MATRIZ:
				foreach ($arrPairs as $mixKey => $mixValue) {
					if (is_array($mixValue) OR is_object($mixValue))
						continue;
					if ($strRet)
					{
						$strRet .= ', ';
					}
					if ($strRet2)
					{
						$strRet2 .= ', ';
					}
					$strRet .= $strAlias.'`'.$mixKey.'`';
					// não escapa variaveis e nulos
					if ( ! preg_match('!(^@|^NULL$)!', $mixValue))
					{
						$strRet2 .= Util_DB::escape($mixValue);
					}
					else
						$strRet2 .= $mixValue;
				}
				return array('keys' => $strRet, 'values' => $strRet2);
				break;
			default:
				throw new Kohana_Exception('Util_Convert::arrayToSQL()', 'Formato "'.$intFormat.'" não suportado');
		}

	}

	/**
	 * Converts ResultSet to array
	 *
	 * @param Database_Result $objResult  Query Result
	 * @param boolean         $boolDecode If TRUE, convert string from UTF8 para ASCII
	 * @return array
	 */
	public static function resultToArray(Database_Result $objResult, $boolDecode = FALSE)
	{
		// @TODO use strict convert (convert if necessary)
		$arrResult = array();
		foreach ($objResult as $objRS) {
			$arrRS = array();
			foreach ($objRS as $strIndex => $strValue)
				$arrRS[$strIndex] = ($boolDecode)? utf8_decode($strValue) : $strValue;
			$arrResult[] = $arrRS;
		}
		return $arrResult;
	}

	/**
	 * Converts result to string tabulate as grid
	 *
	 * Useful to export CSV from a SQL Query
	 *
	 * @param array|Database_Result $arrResult    Data resource
	 * @param string                $strSeparator Character separator for columns
	 * @param string                $strBreakLine Break line character
	 * @param string                $strEnclosed  Character to enclosed columns value
	 * @param array                 $arrColumns   Columns and functions for array map
	 * @param boolean               $boolHeader   If true, includes header
	 * @param boolean               $boolDecode   If TRUE, convert string from UTF8 para ASCII
	 * @return string
	 */
	public static function resultToGrid($arrResult, $strSeparator = ';', $strBreakLine = PHP_EOL, $strEnclosed = '"', array $arrColumns = array(), $boolHeader = TRUE, $boolDecode = FALSE)
	{
		if ( ! is_array($arrResult))
		{
			$arrResult = self::resultToArray($arrResult);
		}
		$arrHeader = NULL;
		$strResult = '';
		foreach ($arrResult as $arrRS)
		{
			if ($arrRS instanceof stdClass)
			{
				$arrRS = self::objectToArray($arrRS);
			}
			if ($boolHeader)
			{
				if (count($arrColumns) > 0)
				{
					$arrHeader = array();
					foreach ($arrColumns as $strIndex => $arrInfo)
					{
						$arrHeader[] = ($boolDecode)? utf8_decode($arrInfo['ALIAS']) : $arrInfo['ALIAS'];
					}
				}
				else
				{
					$arrHeader = array_keys($arrRS);
					if ($boolDecode)
					{
						$arrHeader = array_map('utf8_decode', $arrHeader);
					}
				}
				$boolHeader = FALSE;
			}

			if (count($arrColumns) > 0)
			{
				$strSeparatorTmp = '';
				foreach ($arrColumns as $strIndex => $arrInfo)
				{
					$strAlias = $arrInfo['ALIAS'];
					$strValue = ($boolDecode)? utf8_decode($arrRS[$strIndex]) : $arrRS[$strIndex];
					if (isset($arrInfo['FUNC_FILTERS']))
					{
						if ( ! is_array($arrInfo['FUNC_FILTERS']))
						{
							$arrInfo['FUNC_FILTERS'] = array($arrInfo['FUNC_FILTERS']);
						}
						foreach ($arrInfo['FUNC_FILTERS'] as $strFunction)
						{
							$strValue = call_user_func($strFunction, $strValue);
						}
					}
					$strResult .= $strSeparatorTmp.$strEnclosed.$strValue.$strEnclosed;
					$strSeparatorTmp = $strSeparator;
				}
				$strResult .= $strBreakLine;
			}
			else
			{
				if ($boolDecode)
				{
					$arrRS = array_map('utf8_decode', $arrRS);
				}
				$strResult .= $strEnclosed.implode($strEnclosed.$strSeparator.$strEnclosed, $arrRS).$strEnclosed.$strBreakLine;
			}
		}
		if (is_array($arrHeader) AND count($arrHeader))
		{
			$strHeader = $strEnclosed.implode($strEnclosed.$strSeparator.$strEnclosed, $arrHeader).$strEnclosed.$strBreakLine;
			return $strHeader.$strResult;
		}

		return $strResult;
	}

	/**
	 * Convert result to XML
	 *
	 * Useful to export XML from a SQL Query
	 *
	 * @param array|Database_Result $arrResult  Data resource
	 * @param boolean               $boolHeader If true, include header
	 * @param boolean               $boolDecode If TRUE, convert string from UTF8 para ASCII
	 * @return string
	 */
	public static function resultToXML($arrResult, $boolHeader = TRUE, $boolDecode = FALSE)
	{
		$arrHeader = $arrLines = array();

		if ( ! is_array($arrResult))
		{
			$arrResult = self::resultToArray($arrResult, $boolDecode);
		}

		if ($boolHeader)
		{
			list($mixKey, $arrRegister) = each($arrResult);
			foreach ($arrRegister as $strHeader => $strValue)
				$arrHeader[] = ($boolDecode)? utf8_decode($strHeader) : $strHeader;
		}

		$intLine = 0;
		do {
			list($mixKey, $arrRegister) = each($arrResult);
			if ($arrRegister)
			{
				foreach ($arrRegister as $strValue)
					$arrLines[$intLine][] = ($boolDecode)? utf8_decode($strValue) : $strValue;
				$intLine++;
			}
		} while ($arrRegister);

		$strReturn = '<?xml version="1.0"?>
		<?mso-application progid="Excel.Sheet"?>
		<Workbook
		xmlns:x="urn:schemas-microsoft-com:office:excel"
		xmlns="urn:schemas-microsoft-com:office:spreadsheet"
		xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">

		<Styles>
		<Style ss:ID="Default" ss:Name="Normal">
		<Alignment ss:Vertical="Bottom"/>
		<Borders/>
		<Font/>
		<Interior/>
		<StringFormat/>
		<Protection/>
		</Style>
		<Style ss:ID="s27">
		<Font x:Family="Swiss" ss:Color="#0000FF" ss:Bold="1"/>
		</Style>
		</Styles>

		<Worksheet ss:Name="Sheet1">
		<ss:Table>';

		if (count($arrHeader))
		{
			$strReturn .= PHP_EOL."   <ss:Row>";
			foreach ($arrHeader as $strHeader)
				$strReturn .= PHP_EOL."    <ss:Cell ss:StyleID=\"s27\"><Data ss:Type=\"String\">".$strHeader.'</Data></ss:Cell>';
			$strReturn .= PHP_EOL."   </ss:Row>";
		}

		if (count($arrLines))
		{
			foreach ($arrLines as $arrValues)
			{
				$strReturn .= PHP_EOL."   <ss:Row>";
				foreach ($arrValues as $strValue)
					$strReturn .= PHP_EOL."    <ss:Cell><Data ss:Type=\"String\">".$strValue.'</Data></ss:Cell>';
				$strReturn .= PHP_EOL."   </ss:Row>";
			}
		}

		$strReturn .= '
		</ss:Table>
		</Worksheet>
		</Workbook>';

		return $strReturn;
	}

}