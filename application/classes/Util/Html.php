<?php
/**
 * App Html
 *
 * @package Classes/util
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
class Util_Html {
	
	/**
	 * Arrow icon based on the integer value.
	 * 
	 * @param integer $intValue       Value
	 * @param float   $floatPrecision Precision
	 * @return string
	 */
	public static function arrow($intValue, $floatPrecision = 0.05)
	{
		$intBaseValue = 0;
		$strImage = 'arrow_mid.gif';
		if ($intValue > $intBaseValue + $floatPrecision)
			$strImage = 'arrow_up.gif';
		elseif ($intValue < ($intBaseValue + $floatPrecision) * -1)
			$strImage = 'arrow_down.gif';
		
		return HTML::image('media/images/icons/'.$strImage);
	}

	/**
	 * Prints a title
	 * 
	 * @param string  $strText       Text
	 * @param integer $intLevel      Level
	 * @param array   $arrAttributes Html attributes
	 * @return string
	 */
	public static function title($strText, $intLevel = 4, array $arrAttributes = NULL)
	{
		if ( ! isset($arrAttributes['align']))
			$arrAttributes['align'] = 'center';
		$strAttributes = HTML::attributes($arrAttributes);
		return '<h'.$intLevel.' '.$strAttributes.'>'.$strText.'</h'.$intLevel.'>';
	}

	/**
	 * Prints a paragraph
	 *
	 * @param string $strText       Text
	 * @param array  $arrAttributes Html attributes
	 * @return string
	 */
	public static function paragraph($strText, array $arrAttributes = NULL)
	{
		if ( ! isset($arrAttributes['align']))
			$arrAttributes['align'] = 'center';
		$strAttributes = HTML::attributes($arrAttributes);
		return '<p '.$strAttributes.'>'.$strText.'</p>';
	}

	/**
	 * Prints a alert
	 *
	 * @param string $strText       Text
	 * @param array  $arrAttributes Html attributes
	 * @return string
	 */
	public static function alert($strText, array $arrAttributes = NULL)
	{
		if (is_null($arrAttributes))
			$arrAttributes = array('style' => 'color:#FF0000;');
		return self::paragraph($strText, $arrAttributes);
	}

	/**
	 * Prints a error
	 *
	 * @param string $strText       Text
	 * @param array  $arrAttributes Html attributes
	 * @return string
	 */
	public static function error($strText, array $arrAttributes = NULL)
	{
		if (is_null($arrAttributes))
			$arrAttributes = array('style' => 'color:#FF0000;font-weight:bold;');
		return self::paragraph($strText, $arrAttributes);
	}

	/**
	 * Prints a info message
	 *
	 * @param string $strText       Text
	 * @param array  $arrAttributes Html attributes
	 * @return string
	 */
	public static function info($strText, array $arrAttributes = NULL)
	{
		if (is_null($arrAttributes))
			$arrAttributes = array('style' => 'color:#000000;');
		return self::paragraph($strText, $arrAttributes);
	}

	/**
	 * Prints a success message
	 *
	 * @param string $strText       Text
	 * @param array  $arrAttributes Html attributes
	 * @return string
	 */	
	public static function success($strText, array $arrAttributes = NULL)
	{
		if (is_null($arrAttributes))
			$arrAttributes = array('style' => 'color:#0000CC;');
		return self::paragraph($strText, $arrAttributes);
	}

	/**
	 * Prints a message
	 *
	 * @param integer $intType    Type
	 * @param string  $strMessage Message
	 * @return string
	 */
	public static function showMessage($intType, $strMessage)
	{
		$strClass = '';
		switch ($intType)
		{
			case Form::INFO:
				$strClass = 'alert alert-info';
				break;
			case Form::ALERT:
				$strClass = 'alert alert-block';
				break;
			case Form::ERROR:
				$strClass = 'alert alert-error';
				break;
			case Form::SUCCESS:
				$strClass = 'alert alert-success';
				break;
			default:
				$strClass = 'alert';
			break;
		}
		return '<div class="'.$strClass.'">'.$strMessage.'</div>';
	}

	/**
	 * Fill string
	 * 
	 * @param string $strValue   Value
	 * @param string $strDefault Value default
	 * @return string
	 */
	public static function fill($strValue = NULL, $strDefault = NULL)
	{
		if ($strValue OR $strValue === 0)
			return $strValue;
		if ($strDefault !== NULL)
			return $strDefault;
		return '&nbsp;';
	}

	/**
	 * Prints gender
	 * 
	 * @param string $strValue Value
	 * @return string
	 */
	public static function gender($strValue)
	{
		if ($strValue == 'M')
			return 'Masculino';
		if ($strValue == 'F')
			return 'Feminino';
		return Util_Html::fill($strValue);
	}

}