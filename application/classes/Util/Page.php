<?php
/**
 * Page Utils
 *
 * @package Classes/util
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
class Util_Page {

	private static $arrBreadCrumb = array();
	
	/**
	 * Add and show breadcrumb
	 * 
	 * @param string $strBreadCrumbSegment Breadcrumb segment
	 * @return string
	 */
	public static function breadcrumb($strBreadCrumbSegment = NULL)
	{
		if ($strBreadCrumbSegment)
			self::$arrBreadCrumb[] = $strBreadCrumbSegment;
		
		$strReturn = $strSep = '';
		foreach (self::$arrBreadCrumb as $strBreadCrumbSegment) {
			$strReturn .= $strSep.$strBreadCrumbSegment;
			$strSep = ' > ';
		}
		return $strReturn;
	}

}