<?php
/**
 * App filter
 *
 * @package Classes/util
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
class Util_Filter {

	/**
	 * Returns o datepicker filter.
	 *
	 * @param string $strFieldName    Field name
	 * @param string $strValueDefault Value default
	 * @param array  $arrExtra        Html parameters
	 * @return string
	 */
	public static function datepicker($strFieldName = NULL, $strValueDefault = NULL, array $arrExtra = array())
	{
		if ( ! isset($arrExtra['style']))
			$arrExtra['style'] = '';
		$arrExtra['style'] .= 'width:70px;';
		$arrExtra['maxlength'] = 10;

		$strHTML = Form::input($strFieldName, $strValueDefault, $arrExtra);
		
		$strJS = '
			<script>
				$(function() {
					$( "#'.$strFieldName.'" ).datepicker({
						changeMonth: true,
						changeYear: true,
						showOtherMonths: true,
						showWeek: true,
						numberOfMonths: 1,
						showButtonPanel: true
						/*,onSelect: function( selectedDate ) {
							$( "#to" ).datepicker( "option", "minDate", selectedDate );
						}*/
					});
				});
			</script>
		';

		return $strHTML.$strJS;
	}
	
	/**
	 * Retorna o combo de tipo de relação.
	 *
	 * @param string $strFieldName    Field name
	 * @param string $strValueDefault Value default
	 * @param string $strHasAllOption Sets option first position
	 * @param array  $arrExtra        Html parameters
	 * @return string
	 */
	public static function genero($strFieldName = NULL, $strValueDefault = NULL, $strHasAllOption = 'Todos', array $arrExtra = array())
	{
		$strIndex = 'DSC_GENERO';

		$arrOptions = array();
		if ($strHasAllOption)
		{
			$arrOptions[''] = $strHasAllOption;
		}
		$arrOptions['M'] = 'Masculino';
		$arrOptions['F'] = 'Feminino';

		if ( ! $strFieldName)
		{
			$strFieldName = $strIndex;
		}

		return Form::select($strFieldName, $arrOptions, $strValueDefault, $arrExtra);
	}
	
	/**
	 * Returns a team select.
	 *
	 * @param string $strValueDefault Value default
	 * @param string $strFieldName    Field name
	 * @param string $strHasAllOption Sets option first position
	 * @param array  $arrExtra        Html parameters
	 * @return string
	 */
	public static function team($strValueDefault = NULL, $strFieldName = NULL, $strHasAllOption = 'labels.all', array $arrExtra = array())
	{
		$arrWhere = array('status' => 1);
		$strIndex = 'id';
		$strValue = 'name';
		$strOrderBy = 'name';
		$strModel = 'Model_Team';
		return Util_Filter::generics($arrWhere, $strIndex, $strValue, $strOrderBy, $strModel, $strFieldName, $strHasAllOption, $arrExtra, $strValueDefault, FALSE);
	}
	
	/**
	 * Returns a custem select.
	 *
	 * @param array   $arrValues        List of values
	 * @param string  $strFieldName     Field name
	 * @param string  $strValueDefault  Value default
	 * @param string  $strHasAllOption  Sets option first position
	 * @param array   $arrExtra         Html parameters
	 * @param boolean $boolAutoComplete Includes JS code to transform select into autocomplete
	 * @return string
	 */
	public static function custom(array $arrValues = array(), $strFieldName, $strValueDefault = NULL, $strHasAllOption = 'Todos', array $arrExtra = array(), $boolAutoComplete = FALSE)
	{
		$arrOptions = array();
		if ($strHasAllOption)
			$arrOptions[''] = $strHasAllOption;
		foreach ($arrValues as $strValA => $strValB)
			$arrOptions[$strValA] = $strValB;
		
		$strJS = '';
		$strHTML = Form::select($strFieldName, $arrOptions, $strValueDefault, $arrExtra);

		if ($boolAutoComplete) {
			$strHTML = '<span class="ui-combobox">'.$strHTML.'</span>';
		
			$strJS = '
				<script>
					$(function() {
						$( "#'.$strFieldName.'" ).combobox();
					});
				</script>
			';
		}

		return $strHTML.$strJS;
	}
	
	/**
	 * Returns a checkbox html.
	 *
	 * @param array  $arrWhere        WHERE clause
	 * @param string $strIndex        Id field name
	 * @param string $strValue        Description field name
	 * @param string $strOrderBy      Order by clause
	 * @param string $strModel        Model name
	 * @param string $strFieldName    Field name ($_GET lookup)
	 * @param array  $arrExtra        Html parameters
	 * @param string $mixValueDefault Value Default
	 * @return string
	 */
	public static function generics_checkbox(array $arrWhere, $strIndex, $strValue, $strOrderBy, $strModel, $strFieldName, array $arrExtra = array(), $mixValueDefault)
	{
		$arrValueDefault = $mixValueDefault;
		if ( ! is_array($arrValueDefault))
			$arrValueDefault = array($arrValueDefault);
			
		if ( ! $strFieldName)
			$strFieldName = $strIndex;

		$objDao = new $strModel;
		$objResult = $objDao->getList($arrWhere, array($strIndex, $strValue), $strOrderBy);
		
		$strReturn = $strSep ='';
		foreach ($objResult as $arrRS) {
			$boolChecked = FALSE;
			if (in_array($arrRS[$strIndex], $arrValueDefault))
				$boolChecked = TRUE;
			$strReturn .= $strSep.Form::checkbox($strFieldName.'[]', $arrRS[$strIndex], $boolChecked, $arrExtra).' '.$arrRS[$strValue];
			$strSep = '<br />';
		}
			
		return $strReturn;
	}

	/**
	 * Returns a select html.
	 *
	 * @param array   $arrWhere         WHERE clause
	 * @param string  $strIndex         Id field name
	 * @param string  $strValue         Description field name
	 * @param string  $strOrderBy       Order by clause
	 * @param string  $strModel         Model name
	 * @param string  $strFieldName     Field name ($_GET lookup)
	 * @param string  $strHasAllOption  Sets option first position
	 * @param array   $arrExtra         Html parameters
	 * @param string  $strValueDefault  Value Default
	 * @param boolean $boolAutoComplete Includes JS code to transform select into autocomplete
	 * @return string
	 */
	public static function generics(array $arrWhere, $strIndex, $strValue, $strOrderBy, $strModel, $strFieldName, $strHasAllOption, array $arrExtra = array(), $strValueDefault, $boolAutoComplete = TRUE)
	{
		if (! isset($arrExtra['style']))
			$arrExtra['style'] = '';
		$arrExtra['style'] .= 'margin-bottom:0px;';
		
		$objDao = new $strModel;
		$objResult = $objDao->getList($arrWhere, array($strIndex, $strValue), $strOrderBy);
		$arrOptions = array();

		if ($strHasAllOption)
			$arrOptions[''] = ___($strHasAllOption);
		foreach ($objResult as $objModel)
			$arrOptions[$objModel[$strIndex]] = $objModel[$strValue];
		if ( ! $strFieldName)
			$strFieldName = $strIndex;
		
		$strJS = '';
		$strHTML = Form::select($strFieldName, $arrOptions, $strValueDefault, $arrExtra);

		if ($boolAutoComplete) {
			$strHTML = '<span class="ui-combobox">'.$strHTML.'</span>';
		
			$strJS = '
				<script>
					$(function() {
						$( "#'.$strFieldName.'" ).combobox();
					});
				</script>
			';
		}

		return $strHTML.$strJS;
	}

}