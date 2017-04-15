<?php
/**
 * App Media
 *
 * @package Classes/util
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
class Util_Media {

	private static $arrScripts = array();
	private static $arrStyles = array();

	/**
	 * Adds JS file
	 * 
	 * @param string  $strFile         File name
	 * @param array   $arrAttributes   Attributes
	 * @param string  $strProtocol     Protocol
	 * @param string  $strIndex        Index
	 * @param boolean $boolDefaultPath Is default path
	 * @return void
	 */
	public static function script($strFile, array $arrAttributes = NULL, $strProtocol = NULL, $strIndex = FALSE, $boolDefaultPath = TRUE)
	{
		if ($boolDefaultPath)
			$strFile = 'media/js/'.$strFile.'.js';
		self::$arrScripts[] = HTML::script($strFile, $arrAttributes, $strProtocol, $strIndex);
	}

	/**
	 * Adds CSS file
	 *
	 * @param string  $strFile         File name
	 * @param array   $arrAttributes   Attributes
	 * @param string  $strProtocol     Protocol
	 * @param string  $strIndex        Index
	 * @param boolean $boolDefaultPath Is default path
	 * @return void
	 */
	public static function style($strFile, array $arrAttributes = NULL, $strProtocol = NULL, $strIndex = FALSE, $boolDefaultPath = TRUE)
	{
		if ($boolDefaultPath)
			$strFile = 'media/css/'.$strFile.'.css';
		self::$arrStyles[] = HTML::style($strFile, $arrAttributes, $strProtocol, $strIndex);
	}

	/**
	 * Return list of JS scripts
	 * 
	 * @return array
	 */
	public static function getScripts()
	{
		return self::$arrScripts;
	}

	/**
	 * Return list of CSS scripts
	 *
	 * @return array
	 */
	public static function getStyles()
	{
		return self::$arrStyles;
	}

}