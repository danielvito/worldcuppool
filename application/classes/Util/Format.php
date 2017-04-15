<?php
/**
 * App format
 *
 * @package Classes/util
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
class Util_Format {

	/**
	 * Return only numbers
	 *
	 * @param string $strInput String value
	 * @return integer
	 */
	public static function number($strInput)
	{
		return preg_replace('![^0-9]+!', '', $strInput);
	}

	/**
	 * Return only alpha characters
	 *
	 * @param string $strInput String value 
	 * @return string
	 */
	public static function text($strInput)
	{
		return preg_replace('/\p{N}/', '', $strInput);
	}

	/**
	 * Return alpha and numbers characters
	 *
	 * @param string $strInput String value
	 * @return string
	 */
	public static function standardText($strInput)
	{
		return preg_replace('![^0-9A-Za-z/]+!', '', $strInput);
	}

	/**
	 * Find a substring in a main string
	 *
	 * @param string $strExpr    Part
	 * @param string $strSubject All
	 * @return string
	 */
	public static function match($strExpr, $strSubject)
	{
		$strFound = array();
		preg_match('!'.$strExpr.'!i', $strSubject, $strFound);
		return $strFound[0];
	}

	/**
	 * Fits the string to the size
	 *
	 * @param string  $strSubject            Original text
	 * @param integer $intSize               Size
	 * @param boolean $boolRemoveSpecialChar If true, remove special chars
	 * @return string	String formatada
	 */
	public static function fit($strSubject, $intSize, $boolRemoveSpecialChar = FALSE)
	{
		if ($boolRemoveSpecialChar)
			$strSubject = utf8::transliterate_to_ascii($strSubject);
		if (utf8::strlen($strSubject) > $intSize)
			$strSubject = utf8::substr($strSubject, 0, $intSize - 1);
			$strSubject = preg_replace('![ ]+$!', '', $strSubject).'…';
		if ($intSize < 0)
			$strSubject = utf8::str_pad($strSubject, -$intSize, ' ', STR_PAD_LEFT);
		else
			$strSubject = utf8::str_pad($strSubject, $intSize, ' ', STR_PAD_RIGHT);
		return $strSubject;
	}

	/**
	 * Format as cellphone number
	 *
	 * @param integer $intCellphone Cellphone
	 * @return string
	 */
	public static function cellphone($intCellphone)
	{
		$intCellphone = self::numbers($intCellphone);
		
		if (strlen($intCellphone) == 11)
			return self::mask($intCellphone, '(00) 00000-0000');
		if (strlen($intCellphone) == 10)
			return self::mask($intCellphone, '(00) 0000-0000');
		if (strlen($intCellphone) == 9)
			return self::mask($intCellphone, '00000-0000');
		if (strlen($intCellphone) == 8)
			return self::mask($intCellphone, '0000-0000');
		return $intCellphone;
	}

	/**
	 * Format as CPF
	 *
	 * @param integer $intCPF CPF
	 * @return string
	 */
	public static function CPF($intCPF)
	{
		return self::mask(self::numbers($intCPF), '000.000.000-00');
	}

	/**
	 * Format as CNPJ
	 *
	 * @param integer $intCNPJ CNPJ
	 * @return string
	 */
	public static function CNPJ($intCNPJ)
	{
		return self::mask(self::numbers($intCNPJ), '00.000.000/0000-00');
	}

	/**
	 * Format according to the mask
	 *
	 * Mask:
	 * 0 - accept numbers and fill up width zero
	 * 9 - accept one number and fill up width space
	 * a - accept one char and fill up width space
	 * x - accept one char or number and fill up width space
	 * ? - accept one optional char
	 * <code>Samples:
	 * $str = format::mask($var, '+99 (999) 9999-9999');
	 * $str = format::mask($var, '000.000.000/0000-00');
	 * $str = format::mask($var, 'aaa-0000');</code>
	 *
	 * @param string $strValue Value
	 * @param string $strMask  Mask
	 * @return string
	 */
	public static function mask($strValue, $strMask)
	{
		settype($strValue, 'string');
		if (strlen($strValue) < strlen($strMask)) {
			$strValue = str_pad(trim($strValue), strlen($strMask), ' ', STR_PAD_LEFT);
		}
		$strReturn = '';
		$intLoop = strlen($strMask) - 1;
		$intLoop2 = strlen($strValue) - 1;
		while ($intLoop >= 0)
		{
			if ($intLoop2 >= 0)
				$strChar = $strValue[$intLoop2];
			else
				$strChar = '';
			switch (strtolower($strMask[$intLoop]))
			{
				case '0':
					if (preg_match('![0-9]!', $strChar)) {
						$strReturn = $strChar.$strReturn;
						$intLoop--;
						$intLoop2--;
					} else
						$intLoop2--;
					if ($intLoop2 < 0) {
						$strReturn = '0'.$strReturn;
						$intLoop--;
					}
					break;
				case '9':
					if (preg_match('![0-9]!', $strChar)) {
						$strReturn = $strChar.$strReturn;
						$intLoop--;
						$intLoop2--;
					} else
						$intLoop2--;
					if ($intLoop2 < 0) {
						$strReturn = ' '.$strReturn;
						$intLoop--;
					}
					break;
				case 'a':
					if (preg_match('![A-Za-z]!', $strChar)) {
						$strReturn = $strChar.$strReturn;
						$intLoop--;
						$intLoop2--;
					} else
						$intLoop2--;
					if ($intLoop2 < 0) {
						$strReturn = ' '.$strReturn;
						$intLoop--;
					}
					break;
				case 'x':
					if (preg_match('![A-Za-z0-9]!', $strChar)) {
						$strReturn = $strChar.$strReturn;
						$intLoop--;
						$intLoop2--;
					} else
						$intLoop2--;
					if ($intLoop2 < 0) {
						$strReturn = ' '.$strReturn;
						$intLoop--;
					}
					break;
				case '?':
					if (preg_match('![A-Za-z0-9]!', $strChar)) {
						$strReturn = $strChar.$strReturn;
						$intLoop--;
						$intLoop2--;
					} else
						$intLoop--;
					break;
				default:
					$strReturn = $strMask[$intLoop].$strReturn;
					$intLoop--;
			}
		}
		if (strlen($strReturn) > strlen($strMask))
			$strReturn = substr($strReturn, -strlen($strMask));
		return $strReturn;
	}

	/**
	 * Return first and last part of the full name
	 *
	 * @param string $strFullName Full name
	 * @return string	Nome curto
	 */
	public static function shortName($strFullName)
	{
		$arrNomes = explode(' ', $strFullName);
		return utf8::ucwords(utf8::strtolower($arrNomes[0].' '.$arrNomes[count($arrNomes) - 1]));
	}

	/**
	 * Insere espaços ou outro caractere especificado para quebrar palavras longas.
	 *
	 * @param string  $strValue     Value
	 * @param integer $intWordLimit Max size of each word
	 * @param string  $strWordBreak Char for break word
	 * @param integer $intLineLimit Line limit
	 * @param string  $strLineBreak Char for break line
	 * @return string
	 */
	public static function breakLongWords($strValue, $intWordLimit = 30, $strWordBreak = ' ', $intLineLimit = NULL, $strLineBreak = PHP_EOL)
	{
		$strReturn = preg_replace('!([^ ]{'.$intWordLimit.'})!i', '\1'.$strWordBreak, $strValue);
		if ( ! is_null($intLineLimit))
			$strReturn = wordwrap($strReturn, $intLineLimit, $strLineBreak);
		return $strReturn;
	}

	/**
	 * Remove duplicated chars
	 *
	 * @param string $strValue  Value
	 * @param string $strNeedle Duplicated char to fix
	 * @return string
	 */
	public static function removeDuplicates($strValue, $strNeedle = ' ')
	{
		while (strstr($strValue, $strNeedle.$strNeedle)) {
			$strValue = str_replace($strNeedle.$strNeedle, $strNeedle, $strValue);
		}
		return $strValue;
	}

	/**
	 * Remove break lines
	 *
	 * @param string  $strText        Text
	 * @param boolean $boolRemoveTabs If true, remove tabs
	 * @return string
	 */
	public static function removeBreakLines($strText, $boolRemoveTabs = FALSE)
	{
		$strText = preg_replace("![\n\r]+!", ' ', $strText);
		if ($boolRemoveTabs)
			$strText = str_replace("\t", '', $strText);
		return $strText;
	}

	/**
	 * Remove special chars
	 *
	 * @param string $strValue Value
	 * @return string
	 */
	public static function removeSpecialChars($strValue)
	{
		return strtr($strValue, array('Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'À' => 'A', 'È' => 'E', 'Ì' => 'I', 'Ò' => 'O', 'Ù' => 'U', 'Â' => 'A', 'Ê' => 'E', 'Î' => 'I', 'Ô' => 'O', 'Û' => 'U', 'Ã' => 'A', 'Õ' => 'O', 'Ä' => 'A', 'Ë' => 'E', 'Ï' => 'I', 'Ö' => 'O', 'Ü' => 'U', 'Ç' => 'C', 'Ñ' => 'N', 'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u', 'â' => 'a', 'ê' => 'e', 'î' => 'i', 'ô' => 'o', 'û' => 'u', 'ã' => 'a', 'õ' => 'o', 'ä' => 'a', 'ë' => 'e', 'ï' => 'i', 'ö' => 'o', 'ü' => 'u', 'ç' => 'c', 'ñ' => 'n', 'ª' => 'a', 'º' => 'o', '²' => '2', '³' => '3', '¼' => '1/4', '½' => '1/2', '¾' => '3/4'));
	}
	
	/**
	 * Return decimal number
	 *
	 * @param integer $intValue Value
	 * @return string
	 */
	public static function decimal($intValue)
	{
		return number_format($intValue, 0, ',', '.');
	}

	/**
	 * Return decimal number
	 *
	 * @param integer $intValue Value
	 * @return string
	 */
	public static function decimal1($intValue)
	{
		return number_format($intValue, 1, ',', '.');
	}

	/**
	 * Return decimal number
	 *
	 * @param integer $intValue Value
	 * @return string
	 */
	public static function decimal2($intValue)
	{
		return number_format($intValue, 2, ',', '.');
	}

	/**
	 * Return percentage number
	 *
	 * @param integer $intValue Value
	 * @return string
	 */
	public static function percentual($intValue)
	{
		return number_format($intValue, 0, '', '.').'%';
	}

	/**
	 * Return percentage number
	 *
	 * @param integer $intValue Value
	 * @return string
	 */
	public static function percentual1($intValue)
	{
		return number_format($intValue * 100, 1, ',', '.').'%';
	}

	/**
	 * Return percentage number
	 *
	 * @param integer $intValue Value
	 * @return string
	 */
	public static function percentual2($intValue)
	{
		return number_format($intValue * 100, 2, ',', '.').'%';
	}

	/**
	 * Return percentage number
	 *
	 * @param integer $intValue Value
	 * @return string
	 */
	public static function percentual3($intValue)
	{
		return number_format($intValue * 100, 3, ',', '.').'%';
	}

	/**
	 * Return br currency format
	 *
	 * @param integer $intValue Value
	 * @return string
	 */
	public static function currency_br($intValue)
	{
		return 'R$ '.number_format($intValue, 2, ',', '.');
	}

	/**
	 * Return us currency format
	 *
	 * @param integer $intValue Value
	 * @return string
	 */
	public static function currency_us($intValue)
	{
		return '$ '.number_format($intValue, 2, '.', ',');
	}

	/**
	 * Formats a phone number according to the specified format.
	 *
	 * @param string $intNumber Phone number
	 * @param string $strFormat Format string
	 * @return string
	 */
	public static function phone($intNumber, $strFormat = '3-3-4')
	{
		// Get rid of all non-digit characters in number string
		$intNumberClean = preg_replace('/\D+/', '', (string) $intNumber);

		// Array of digits we need for a valid format
		$strFormatParts = preg_split('/[^1-9][^0-9]*/', $strFormat, -1, PREG_SPLIT_NO_EMPTY);

		// Number must match digit count of a valid format
		if (strlen($intNumberClean) !== array_sum($strFormatParts))
			return $intNumber;

		// Build regex
		$strRegex = '(\d{'.implode('})(\d{', $strFormatParts).'})';

		// Build replace string
		for ($intLoop = 1, $intCount = count($strFormatParts); $intLoop <= $intCount; $intLoop++)
			$strFormat = preg_replace('/(?<!\$)[1-9][0-9]*/', '\$'.$intLoop, $strFormat, 1);

		return preg_replace('/^'.$strRegex.'$/', $strFormat, $intNumberClean);
	}

	/**
	 * Formats a URL to contain a protocol at the beginning.
	 *
	 * @param string $strURL Possibly incomplete URL
	 * @return string
	 */
	public static function url($strURL = '')
	{
		// Clear protocol-only strings like "http://"
		if ($strURL === '' OR substr($strURL, -3) === '://')
			return '';

		// If no protocol given, prepend "http://" by default
		if (strpos($strURL, '://') === FALSE)
			return 'http://'.$strURL;

		// Return the original URL
		return $strURL;
	}

}