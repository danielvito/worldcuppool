<?php
/**
 * App Date
 *
 * @package Classes/util
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
class Util_Date
{
	const Y = 'Y'; // Year
	const M = 'M'; // Month
	const D = 'D'; // Day
	const H = 'H'; // Hour
	const I = 'I'; // Minute
	const S = 'S'; // Second

	private $objDateTime;

	/**
	 * Construct.
	 * 
	 * @param integer $intTimeStamp	Timestamp
	 */
	private function __construct($intTimeStamp = NULL)
	{
		$this->objDateTime = new DateTime;
		if ($intTimeStamp)
			$this->objDateTime->setTimestamp($intTimeStamp);
	}

	/**
	 * Create a Util_Date object
	 * 
	 * @param mixed $mixDate Date
	 * @return Util_Date
	 */
	public static function factory($mixDate = NULL)
	{
		if ($mixDate === '')
			return '';

		if ($mixDate instanceof Util_Date)
			return $mixDate;

		if ($mixDate == 'now')
		{
			$mixDate = NULL;
		}

		if ($mixDate === NULL)
			return new Util_Date;


		$strDate = $mixDate;
		if ( ! gettype($mixDate) == 'string') {
			$strDate = (string) $mixDate;
			$strDate = trim($strDate);
		}

		$strData = '';
		$strHour = '';
		if (strlen($strDate) == 6)
			$strData = substr($strDate, 0, 4).'-'.substr($strDate, 4).'-01';
		elseif (strstr($strDate, ' ')) {
			$arrParte = explode(' ', $strDate);
			if (strstr($arrParte[0], ':')) {
				$strHour = $arrParte[0];
				$strData = $arrParte[1];
			} else {
				$strData = $arrParte[0];
				$strHour = $arrParte[1];
			}
		} else {
			if (strstr($strDate, ':'))
				$strHour = $strDate;
			else
				$strData = $strDate;
		}
		
		$objDateTimeTmp = new DateTime;
		
		if ($strData == '')
			$strData = $objDateTimeTmp->format('Y-m-d');
		if ($strHour == '')
			$strHour = '00:00:00';

		$strSeparadorData = self::findSeparator($strData);

		$intAno = $objDateTimeTmp->format('Y');
		$intMes = $objDateTimeTmp->format('m');
		$intDia = $objDateTimeTmp->format('d');
		$intHora = 0;
		$intMinuto = 0;
		$intSegundo = 0;

		if ( ! $strSeparadorData) {
			if (is_numeric($strData) AND strlen($strData) >= 6) {
				$arrData = array();
				if (strlen($strData) == 6) {
					$arrData[] = substr($strData, 0, 2);
					$arrData[] = substr($strData, 2, 2);
					$arrData[] = substr($strData, 4);
				} else {
					$arrData[] = substr($strData, 0, 4);
					$arrData[] = substr($strData, 4, 2);
					$arrData[] = substr($strData, 6);
				}
			} else
				return FALSE;
		} else
			$arrData = explode($strSeparadorData, $strData);

		if (count($arrData) == 3) {
			$intMes = $arrData[1];
			if ($arrData[0] > 31) {
				$intAno = $arrData[0];
				$intDia = $arrData[2];
			} else {
				$intAno = $arrData[2];
				$intDia = $arrData[0];
			}
		} elseif (count($arrData) == 2) {
			$intMes = $arrData[0];
			$intAno = $arrData[1];
		} else
			$intDia = $arrData[0];

		if ($strHour != '') {
			$strSeparadorHora = self::findSeparator($strHour);

			if ( ! $strSeparadorHora) {
				if (is_numeric($strHour)) {
					$arrHora = array(0, 0, 0);
					if (strlen($strHour) >= 2)
						$arrHora[0] = substr($strHour, 0, 2);
					if (strlen($strHour) >= 4)
						$arrHora[1] = substr($strHour, 2, 2);
					if (strlen($strHour) == 6)
						$arrHora[2] = substr($strHour, 4);
				} else
					return FALSE;
			} else
				$arrHora = explode($strSeparadorHora, $strHour);

			$intHora = $arrHora[0];
			if (count($arrHora) > 1)
				$intMinuto = $arrHora[1];
			if (count($arrHora) > 2)
				$intSegundo = $arrHora[2];
		}
		$objDateTimeTmp->setTimezone(new DateTimeZone(Util_App::config('application.timezone_server')));
		$objDateTimeTmp->setDate($intAno, $intMes, $intDia);
		$objDateTimeTmp->setTime($intHora, $intMinuto, $intSegundo);

		return new Util_Date($objDateTimeTmp->getTimestamp());
	}

	/**
	 * Find the separator
	 * 
	 * @param string $strDate Date
	 * @return string
	 */
	private static function findSeparator($strDate)
	{
		$arrSeparadores = array('/', '-', '.', ':');
		foreach ($arrSeparadores as $strSeparador)
			if (strstr($strDate, $strSeparador))
				return $strSeparador;
		return FALSE;
	}
	
	/**
	 * Builds a matrix aliging months by week day.
	 * 
	 * @param array   $arrDates List of dates
	 * @param boolean $boolAsc  Asc or desc
	 * @return array
	 */
	public static function arrangeMonthsByWeekDay(array $arrDates, $boolAsc = TRUE)
	{
		$arrDates = self::orderDates($arrDates, $boolAsc);
		$arrMatrix = array();
		
		$strFirstWeekDay = '';
		if (count($arrDates)) {
			foreach ($arrDates as $mixDate) {
				$objDate = Util_Date::factory($mixDate);
				$objDate->setDay(1);
				
				if ( ! $strFirstWeekDay)
					$strFirstWeekDay = $objDate->getDayOfWeek();

				$intYearMonth = Util_Date::toYearMonth($objDate);
				$intMonth = $objDate->getMonth();
				while ($objDate->getMonth() == $intMonth) {
					$arrMatrix[$intYearMonth][] = array(Util_Date::toSQL($objDate), $objDate->getDayOfWeek());
					$objDate->next();
				}
			}

			$arrMatrixKeys = array_keys($arrMatrix);
			if (count($arrMatrixKeys) > 1) {
				$intTotalMonths = count($arrMatrixKeys);
				for ($intLoop = 1; $intLoop < $intTotalMonths; $intLoop++) {
					
					$arrTemp = array();
					$intMonth = $arrMatrixKeys[$intLoop];
					
					$arrCurrentWeekDay = current($arrMatrix[$intMonth]);
					$strCurrentWeekDay = $arrCurrentWeekDay[1];
					$objDateAux = Util_Date::factory();
					
					while ($objDateAux->getDayOfWeek() != $strFirstWeekDay)
						$objDateAux->next();
					
					while ($objDateAux->getDayOfWeek() != $strCurrentWeekDay) {
						$objDateAux->next();
						$arrTemp[] = NULL;
					}
					
					foreach ($arrMatrix[$intMonth] as $arrDay)
						$arrTemp[] = $arrDay;

					$arrMatrix[$intMonth] = $arrTemp;					
					
				}				
			}
			
		}
		return $arrMatrix;
	}
	
	/**
	 * Order dates.
	 * 
	 * @param array   $arrDates List of dates
	 * @param boolean $boolAsc  Asc or desc
	 * @return array
	 */
	public static function orderDates(array $arrDates, $boolAsc = TRUE)
	{
		// @TODO
		return $arrDates;
	}

	/**
	 * Returns timestamp
	 * 
	 * @return integer
	 */
	public function getTimeStamp()
	{
		return $this->objDateTime->getTimestamp();
	}
	
	/**
	 * Returns datetime object.
	 * 
	 * @return DateTime
	 */
	public function getDateTime()
	{
		return $this->objDateTime;
	}
	
	/**
	 * Return date in Ym format
	 * 
	 * @param mixed $mixDataHora Date time
	 * @return string
	 */
	public static function toYearMonth($mixDataHora = NULL)
	{
		$objDate = self::factory($mixDataHora);
		if ( ! $objDate)
			return '';

		return $objDate->getDateTime()->format('Ym');
	}
	
	/**
	 * Return date in m/Y format
	 * 
	 * @param mixed $mixDataHora Date time
	 * @return string
	 */
	public static function toMonthYear($mixDataHora = NULL)
	{
		$objDate = self::factory($mixDataHora);
		if ( ! $objDate)
			return '';

		return $objDate->getDateTime()->format('m/Y');
	}
	
	/**
	 * Return date in BR format
	 * 
	 * @param mixed   $mixDataHora  Date time
	 * @param boolean $boolHora     If true returns time
	 * @param boolean $boolSegundos If true return seconds
	 * @return string
	 */
	public static function toBR($mixDataHora = NULL, $boolHora = FALSE, $boolSegundos = FALSE)
	{
		$objDate = self::factory($mixDataHora);
		if ( ! $objDate)
			return '';

		if ($boolHora)
		{
			if ($boolSegundos)
				return $objDate->getDateTime()->format('d/m/Y H:i:s');
			return $objDate->getDateTime()->format('d/m/Y H:i');
		}
		return $objDate->getDateTime()->format('d/m/Y');
	}

	/**
	 * Return date in SQL format
	 * 
	 * @param mixed   $mixDataHora Date time
	 * @param boolean $boolHora    If true return time
	 * @return string
	 */
	public static function toSQL($mixDataHora = NULL, $boolHora = FALSE)
	{
		$objDate = self::factory($mixDataHora);
		if ( ! $objDate)
			return '';

		if ($boolHora)
			return $objDate->getDateTime()->format('Y-m-d H:i:s');
		return $objDate->getDateTime()->format('Y-m-d');
	}
	
	/**
	 * Return date in BR format
	 * 
	 * @param mixed   $mixDataHora  Date time
	 * @param boolean $boolHora     If true returns time
	 * @param boolean $boolSegundos If true return seconds
	 * @return string
	 */
	public static function toIntSQL($mixDataHora = NULL, $boolHora = FALSE, $boolSegundos = FALSE)
	{
		$objDate = self::factory($mixDataHora);
		if ( ! $objDate)
			return '';

		if ($boolHora)
		{
			if ($boolSegundos)
				return $objDate->getDateTime()->format('YmdHis');
			return $objDate->getDateTime()->format('YmdHi');
		}
		return $objDate->getDateTime()->format('Ymd');
	}
	
	/**
	 * Return date in BR format
	 * 
	 * @param mixed   $mixDataHora  Date time
	 * @param boolean $boolHora     If true returns time
	 * @param boolean $boolSegundos If true return seconds
	 * @return string
	 */
	public static function toIntBR($mixDataHora = NULL, $boolHora = FALSE, $boolSegundos = FALSE)
	{
		$objDate = self::factory($mixDataHora);
		if ( ! $objDate)
			return '';

		if ($boolHora)
		{
			if ($boolSegundos)
				return $objDate->getDateTime()->format('dmYdHis');
			return $objDate->getDateTime()->format('dmYHi');
		}
		return $objDate->getDateTime()->format('dmY');
	}

	/**
	 * Subtrai uma data de outra e retorna a diferenca
	 *
	 * Se $type == H, devolve resultado em horas
	 * Se $type == I, devolve resultado em minutos
	 * Se $type == S, devolve resultado em segundos
	 * Se $type == Y, devolve resultado em anos
	 * Se $type == M, devolve resultado em meses
	 * Se $type == D, devolve resultado em dias
	 *
	 * @param Util_Date $objDate Date
	 * @param string    $strType Tipo
	 * @return integer
	 */
	public function minus(Util_Date $objDate, $strType = self::S)
	{
		$intDiff = $this->getTimeStamp() - $objDate->getTimeStamp();
	
		$strType = strtoupper($strType);
		$intResult = 0;
		switch ($strType)
		{
			case self::H:
				$intResult = floor($intDiff / 3600);
			break;
			case self::I:
				$intResult = floor($intDiff / 60);
			break;
			case self::S:
				$intResult = floor($intDiff);
			break;
			case self::Y:
				$intResult = floor($intDiff / 31536000);
			break;
			case self::M:
				$intResult = floor($intDiff / 2628000);
			break;
			case self::D:
				$intResult = floor($intDiff / 86400);
			break;
		}
		return $intResult;
	}
	
	/**
	 * Compares two Dates.
	 * 
	 * @param Util_Date $objDate Date to compare
	 * @return integer
	 */
	public function compare(Util_Date $objDate)
	{
		if ($objDate->getTimestamp() == $this->getTimeStamp())
			return 0;
		return ($objDate->getTimestamp() < $this->getTimeStamp())? 1 : -1;
	}
	
	/**
	 * Set year
	 *
	 * @param integer $intYear Yera
	 * @return Util_Date
	 */
	public function setYear($intYear)
	{
		$this->objDateTime->setDate($intYear, $this->getMonth(FALSE), $this->getDay(FALSE));
		return $this;
	}

	/**
	 * Set month
	 *
	 * @param integer $intMonth Month
	 * @return Util_Date
	 */
	public function setMonth($intMonth)
	{
		$this->objDateTime->setDate($this->getYear(), $intMonth, $this->getDay(FALSE));
		return $this;
	}

	/**
	 * Set day
	 *
	 * @param integer $intDay Day
	 * @return Util_Date
	 */
	public function setDay($intDay)
	{
		$this->objDateTime->setDate($this->getYear(), $this->getMonth(FALSE), $intDay);
		return $this;
	}

	/**
	 * Set hour
	 *
	 * @param integer $intHour Hour
	 * @return Util_Date
	 */
	public function setHour($intHour)
	{
		$this->objDateTime->setTime($intHour, $this->getMinute());
		return $this;
	}

	/**
	 * Set minute
	 *
	 * @param integer $intMinute Minute
	 * @return Util_Date
	 */
	public function setMinute($intMinute)
	{
		$this->objDateTime->setTime($this->getHour(), $intMinute);
		return $this;
	}

	/**
	 * Set second
	 *
	 * @param integer $intSecond Second
	 * @return Util_Date
	 */
	public function setSegundo($intSecond)
	{
		$this->objDateTime->setTime($this->getHour(), $this->getMinute(), $intSecond);
		return $this;
	}

	/**
	 * Gos forward the date. Default: 1 day.
	 *
	 * @param integer $intQuantity Quantity
	 * @param string  $strPart     Part
	 * @return Util_Date
	 */
	public function next($intQuantity = 1, $strPart = self::D)
	{
		switch ($strPart)
		{
			case self::Y:
				$this->setYear($this->getYear() + $intQuantity);
				break;
			case self::M:
				$this->setMonth($this->getMonth() + $intQuantity);
				break;
			case self::D:
				$this->setDay($this->getDay() + $intQuantity);
				break;
			case self::H:
				$this->setHour($this->getHour() + $intQuantity);
				break;
			case self::I:
				$this->setMinute($this->getMinute() + $intQuantity);
				break;
			case self::S:
				$this->setSegundo($this->getSecond() + $intQuantity);
				break;
		}
		return $this;
	}

	/**
	 * Gos backward the date. Default: 1 day.
	 *
	 * @param integer $intQuantity Quantity
	 * @param string  $strPart     Part
	 * @return Util_Date
	 */
	public function prev($intQuantity = 1, $strPart = self::D)
	{
		return $this->next(($intQuantity * -1), $strPart);
	}
	
	/**
	 * Get year
	 *
	 * @param boolean $boolComp If true, returns 4 digits
	 * @return integer
	 */
	public function getYear($boolComp = TRUE)
	{
		$strYear = $this->objDateTime->format('Y');
		return ($boolComp)? $strYear : substr($strYear, 2);
	}

	/**
	 * Get month
	 *
	 * @param boolean $boolZero If true, complets the number zero at left
	 * @return integer
	 */
	public function getMonth($boolZero = TRUE)
	{
		$strMonth = $this->objDateTime->format('m');
		return ($boolZero)? $strMonth : (int) $strMonth;
	}

	/**
	 * Get day
	 * 
	 * @param boolean $boolZero If true, complets the number zero at left
	 * @return integer
	 */
	public function getDay($boolZero = TRUE)
	{
		$strDay = $this->objDateTime->format('d');
		return ($boolZero)? $strDay : (int) $strDay;
	}
	
	/**
	 * Get day of week
	 * 
	 * @return integer
	 */
	public function getDayOfWeek()
	{
		return $this->objDateTime->format('D');
	}
	
	/**
	 * Get week of month
	 * 
	 * @return integer
	 */
	public function getWeekOfMonth()
	{
		$intDay = $this->getDay();
		$intWeek = round(($this->getDay() / 7) + 0.49);
		if ($intWeek > 4)
			$intWeek = 4;
		return $intWeek;
	}
	
	/**
	 * Get week of year
	 * 
	 * @return integer
	 */
	public function getWeekOfYear()
	{
		return $this->objDateTime->format('W');
	}

	/**
	 * Get hour
	 *
	 * @param boolean $boolZero If true, complets the number zero at left
	 * @param boolean $boolF24  If true, uses the 24 format
	 * @return integer
	 */
	public function getHour($boolZero = TRUE, $boolF24 = TRUE)
	{
		$strHour = ($boolF24)? $this->objDateTime->format('H') : $this->objDateTime->format('h');
		return ($boolZero)? $strHour : (int) $strHour;
	}

	/**
	 * Get minute
	 *
	 * @param boolean $boolZero If true, complets the number zero at left
	 * @return integer
	 */
	public function getMinute($boolZero = TRUE)
	{
		$strMinute = $this->objDateTime->format('i');
		return ($boolZero)? $strMinute : (int) $strMinute;
	}

	/**
	 * Get second
	 *
	 * @param boolean $boolZero If true, complets the number zero at left
	 * @return integer
	 */
	public function getSecond($boolZero = TRUE)
	{
		$strSecond = $this->objDateTime->format('s');
		return ($boolZero)? $strSecond : (int) $strSecond;
	}
	
	/**
	 * Represents Date as string
	 * 
	 * @return string
	 */
	public function __toString()
	{
		 return self::toSQL($this, TRUE);
	}

}