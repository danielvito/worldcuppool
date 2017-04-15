<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Form helper class. Unless otherwise noted, all generated HTML will be made
 * safe using the [HTML::chars] method. This prevents against simple XSS
 * attacks that could otherwise be trigged by inserting HTML characters into
 * form fields.
 *
 * @package Classes
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
class Form extends Kohana_Form
{
	
	const INFO = 1;
	const ALERT = 2;
	const ERROR = 3;
	const SUCCESS = 4;
	
	private static $objPost;
	private static $arrMessages = array();

	/**
	 * Adds validation to session
	 * 
	 * @param Validation $objPost Validation object
	 * @return void
	 */
	public static function setValidation(Validation $objPost)
	{
		if ( ! $objPost->check())
			self::addMessage(self::ERROR, 'Existem problemas no preenchimento do formulário.');
		$objSession = Session::instance();
		$objSession->set('post', $objPost);
	}

	/**
	 * Retrieves validation object
	 * 
	 * @return Validation
	 */
	private static function getValidation()
	{
		if ( ! self::$objPost)
		{
			$objSession = Session::instance();
			self::$objPost = $objSession->get_once('post');
		}
		return self::$objPost;
	}
	
	/**
	 * Gets validation data by index
	 * 
	 * @param string $strIndex Index value
	 * @return mixed
	 */
	public static function getValidationData($strIndex = NULL)
	{
		$objPost = self::getValidation();
		if ($objPost instanceof Validation) {
			$arrData = self::$objPost->data();
			if (isset($arrData[$strIndex]))
				return $arrData[$strIndex];
			if ($strIndex)
				return NULL;
			return $arrData;
		}
		if ($strIndex)
			return NULL;		
		return array();
	}
	
	/**
	 * Get erro message
	 * 
	 * @param string $strIndex Index message
	 * @return string
	 */
	public static function getErrorMessage($strIndex)
	{
		$objPost = self::getValidation();
		if ($objPost)
		{
			$arrErrors = $objPost->errors('errors');
			
			if (isset($arrErrors[$strIndex]))
				return $arrErrors[$strIndex];
		}
		return '';
	}
	
	/**
	 * Add message
	 * 
	 * @param integer $intType    Message type
	 * @param string  $strMessage Message
	 * @return void
	 */
	public static function addMessage($intType, $strMessage)
	{
		self::$arrMessages[$intType][] = $strMessage;
		$objSession = Session::instance();
		$objSession->set('messages', self::$arrMessages);
	}
	
	/**
	 * Render all messages
	 * 
	 * @return string
	 */
	public static function showMessages()
	{
		$objSession = Session::instance();
		$arrSessionMessages = $objSession->get('messages', array());
		$objSession->delete('messages');

		$objPost = $objSession->get('post');
		if ($objPost) {
			$arrErrors = (array) $objPost->errors('errors');
			foreach ($arrErrors as $strError)
				$arrSessionMessages[self::ERROR][] = $strError;
		}
		
		$strMessages = '';		
		if (count($arrSessionMessages))
		{
			$strMessages .= '';
			foreach ($arrSessionMessages as $intType => $arrMessages)
			{
				switch ($intType)
				{
					case self::INFO:
						$strClass = 'alert alert-info';
					break;
					case self::ERROR:
						$strClass = 'alert alert-error';
					break;
					case self::ALERT:
						$strClass = 'alert alert-warning';
					break;
					case self::SUCCESS:
						$strClass = 'alert alert-success';
					break;
					default:
						$strClass = 'alert alert-info';
					break;
				}
				$strMessages .= '';
				foreach ($arrMessages as $strMessage)
					$strMessages .= '
							<div class="'.$strClass.'">
							 	<button type="button" class="close" data-dismiss="alert">&times;</button>
								'.$strMessage.'
							</div>
					';
				$strMessages .= '';
			}
			$strMessages .= '';
		}
		return $strMessages;
	}
	
	/**
	 * Creates a form input. If no type is specified, a "text" type input will be returned.
	 *
	 * Sample:
	 *     echo Form::input('username', $username);
	 *
	 * @param string $strName       Input name
	 * @param mixed  $mixValue      Input value
	 * @param array  $arrAttributes Html attributes
	 * @return string
	 */
	public static function input($strName, $mixValue = NULL, array $arrAttributes = NULL)
	{
		$arrData = self::getValidationData();

		if ($mixValue === NULL AND isset($arrData[$strName]))
			$mixValue = $arrData[$strName];
		
		if ( ! empty($arrAttributes['type']) AND $arrAttributes['type'] == 'password')
			$mixValue = '';
		
		$strErro = '';		
		if (empty($arrAttributes['type']) OR $arrAttributes['type'] != 'radio')
		{
			$objPost = self::getValidation();
			if ($objPost) {
				$arrErrors = $objPost->errors('errors');
				if (isset($arrErrors[$strName]))
					$strErro = $arrErrors[$strName];
				
			}
		}
		
		if (empty($arrAttributes['id']) AND $strName)
			$arrAttributes['id'] = $strName;
		
		$strErroComplemento = '';
		if ($strErro) {
			$arrAttributes['class'] = 'input_error';
			// $strErroComplemento = '<br /><span class="input_error_message">'.$strErro.'</span>';
		}

		return parent::input($strName, $mixValue, $arrAttributes).$strErroComplemento;
	}
	
	/**
	 * Creates a select form input.
	 *
	 * Sample
	 *     echo Form::select('country', $countries, $country);
	 *
	 * [!!] Support for multiple selected options was added in v3.0.7.
	 *
	 * @param string $strName       Input name
	 * @param array  $arrOptions    Available options
	 * @param mixed  $mixSelected   Selected option string, or an array of selected options
	 * @param array  $arrAttributes Html attributes
	 * @return string
	 */
	public static function select($strName, array $arrOptions = NULL, $mixSelected = NULL, array $arrAttributes = NULL)
	{
		$arrData = self::getValidationData();
		if ( ! $mixSelected AND isset($arrData[$strName]))
			$mixSelected = $arrData[$strName];
		
		$strErro = '';
		$objPost = self::getValidation();
		if ($objPost) {
			$arrErrors = $objPost->errors('errors');
			if (isset($arrErrors[$strName]))
				$strErro = $arrErrors[$strName];
			
		}
		
		if (empty($arrAttributes['id']) AND $strName)
			$arrAttributes['id'] = $strName;
		
		$strErroComplemento = '';
		if ($strErro) {
			$arrAttributes['class'] = 'input_error';
			$strErroComplemento = '<br /><span class="input_error_message">'.$strErro.'</span>';
		}
		
		return parent::select($strName, $arrOptions, $mixSelected, $arrAttributes).$strErroComplemento;
	}
	
	/**
	 * Creates a radio form input.
	 *
	 * Sample
	 *     echo Form::radio('like_cats', 1, $cats);
	 *     echo Form::radio('like_cats', 0, ! $cats);
	 *
	 * @param string  $strName       Input name
	 * @param mixed   $mixValue      Input value
	 * @param boolean $boolChecked   Checked status
	 * @param array   $arrAttributes Html attributes
	 * @return string
	 */
	public static function radio($strName, $mixValue = NULL, $boolChecked = FALSE, array $arrAttributes = NULL)
	{
		$arrData = self::getValidationData();
		$boolChecked = FALSE;
		if (isset($arrData[$strName]) AND $arrData[$strName] === $mixValue)
			$boolChecked = TRUE;
		return parent::radio($strName, $mixValue, $boolChecked, $arrAttributes);
	}
	
	/**
	 * Creates a checkbox form input.
	 *
	 * Samples:
	 *     echo Form::checkbox('remember_me', 1, (bool) $remember);
	 *
	 * @param string  $strName       Input name
	 * @param mixed   $mixValue      Input value
	 * @param boolean $boolChecked   Checked status
	 * @param array   $arrAttributes Html attributes
	 * @return string
	 */
	public static function checkbox($strName, $mixValue = NULL, $boolChecked = FALSE, array $arrAttributes = NULL)
	{
		$arrData = self::getValidationData();
		
		$strCleanName = str_replace('[]', '', $strName);
		
		if (isset($arrData[$strCleanName]) AND (($arrData[$strCleanName] === $mixValue) OR (is_array($arrData[$strCleanName]) AND in_array($mixValue, $arrData[$strCleanName]))))
			$boolChecked = TRUE;
		
		if ( ! isset($arrAttributes['id']))
			$arrAttributes['id'] = $strCleanName.'_'.$mixValue;

		return parent::checkbox($strName, $mixValue, $boolChecked, $arrAttributes);
	}
	
	/**
	 * Creates a button form input. Note that the body of a button is NOT escaped,
	 * to allow images and other HTML to be used.
	 *
	 *     echo Form::button('save', 'Save Profile', array('type' => 'submit'));
	 *
	 * @param   string  $name       input name
	 * @param   string  $body       input value
	 * @param   array   $attributes html attributes
	 * @return  string
	 * @uses    HTML::attributes
	 */
	public static function button($strName, $strBody, array $arrAttributes = NULL)
	{
		if ( ! isset($arrAttributes['class']))
			$arrAttributes['class'] = '';
		$arrAttributes['class'] = 'btn '.$arrAttributes['class'];
		if ( ! isset($arrAttributes['type']))
			$arrAttributes['type'] = 'button';
		return parent::button($strName, $strBody, $arrAttributes);
	}
	
	/**
	 * Creates a submit form input.
	 *
	 *     echo Form::submit(NULL, 'Login');
	 *
	 * @param   string  $strName       input name
	 * @param   string  $strValue      input value
	 * @param   array   $arrAttributes html attributes
	 * @return  string
	 * @uses    Form::input
	 */
	public static function submit($strName, $strValue, array $arrAttributes = NULL)
	{
		$arrAttributes['type'] = 'submit';
		
		if ( ! isset($arrAttributes['class']))
			$arrAttributes['class'] = '';
		$arrAttributes['class'] = 'btn '.$arrAttributes['class'];

		return Form::input($strName, $strValue, $arrAttributes);
	}

}