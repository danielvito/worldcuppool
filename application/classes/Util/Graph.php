<?php
/**
 * App Html
 *
 * @package Classes/util
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
class Util_Graph {
	
	CONST TYPE_BARS = 'bars';
	CONST TYPE_LINES = 'lines';
	
	CONST LEGEND_POSITION_NONE = 'none';
	CONST LEGEND_POSITION_NE = 'ne';
	CONST LEGEND_POSITION_NW = 'nw';
	CONST LEGEND_POSITION_SE = 'se';
	CONST LEGEND_POSITION_SW = 'sw';
	
	protected $strType;
	protected $strID;
	protected $arrSeries = array();
	protected $arrTicks = array();
	
	protected $intWidth;
	protected $intHeight;
	
	protected $strLegendPosition = self::LEGEND_POSITION_NE;
	protected $boolLegendOutSide = TRUE;
	protected $intWidthLegend = 150;
	protected $intHeightLegend = 100;

	/**
	 * Construct.
	 * 
	 * @param string  $strID     Graph ID
	 * @param string  $strType   Graph type
	 * @param integer $intWidth  Graph width
	 * @param integer $intHeight Graph height
	 */
	private function __construct($strID, $strType, $intWidth, $intHeight)
	{
		$this->strID = $strID;
		$this->strType = $strType;
		$this->intWidth = $intWidth;
		$this->intHeight = $intHeight;
	}
	
	/**
	 * Creates a graph instance.
	 * 
	 * @param string  $strID     Graph ID
	 * @param string  $strType   Graph type
	 * @param integer $intWidth  Graph width
	 * @param integer $intHeight Graph height
	 * @return Util_Graph
	 */
	public static function factory($strID, $strType, $intWidth, $intHeight)
	{
		return new Util_Graph($strID, $strType, $intWidth, $intHeight);
	}
	
	/**
	 * Legend options.
	 * 
	 * @param string  $strLegendPosition Legend position
	 * @param string  $boolLegendOutSide Legend in or outside the graph
	 * @param integer $intWidthLegend    Legend width
	 * @param integer $intHeightLegend   Legend height
	 * @return void
	 */
	public function setLegendOptions($strLegendPosition, $boolLegendOutSide = NULL, $intWidthLegend = NULL, $intHeightLegend = NULL)
	{
		$this->strLegendPosition = $strLegendPosition;
		if ( ! is_null($boolLegendOutSide))
			$this->boolLegendOutSide = $boolLegendOutSide;
		if ($intWidthLegend)
			$this->intWidthLegend = $intWidthLegend;
		if ($intHeightLegend)
			$this->intHeightLegend = $intHeightLegend;
	}
	
	/**
	 * Adds series values.
	 * 
	 * @param string      $strLabel Serie label
	 * @param array|float $mixValue Value(s)
	 * @return void
	 */
	public function addSerie($strLabel, $mixValue)
	{
		if ( ! is_array($mixValue))
			$mixValue = array($mixValue);
		foreach ($mixValue as $floatValue)
			$this->arrSeries[$strLabel][] = $floatValue;
	}
	
	/**
	 * Adds ticks.
	 * 
	 * @param array|float $mixValue Value(s)
	 * @return void
	 */
	public function addTicks($mixValue)
	{
		if ( ! is_array($mixValue))
			$mixValue = array($mixValue);
		foreach ($mixValue as $floatValue)
			$this->arrTicks[] = $floatValue;
	}
	
	/**
	 * Prints the graph.
	 * 
	 * @return string
	 */
	public function show()
	{
		$arrDataLines = array();
		foreach ($this->arrSeries as $strLabel => $arrSerie) {
			$strSerie = $strSep = '';
			foreach ($arrSerie as $intLoop => $floatValue) {
				$strSerie .= $strSep.'['.$intLoop.', '.$floatValue.']';
				$strSep = ', ';
			}
			if (is_string($strLabel))
				$strLabel = '"'.$strLabel.'"';
			$arrDataLines[] = '{ label: '.$strLabel.', data: ['.$strSerie.'] }';
		}
		$strDataGraph = implode(', ', $arrDataLines);
		
		$strTicks = $strSep = '';
		foreach ($this->arrTicks as $intLoop => $mixValue) {
			if (is_string($mixValue))
				$mixValue = '"'.$mixValue.'"';
			$strTicks .=  $strSep.'['.$intLoop.', '.$mixValue.']';
			$strSep = ', ';
		}
		
		$arrLegendOptions = array();
		if (($this->strLegendPosition == self::LEGEND_POSITION_NONE))
			$arrLegendOptions['show'] =  'false';
		else {
			$arrLegendOptions['show'] =  'true';
			$arrLegendOptions['position'] =  '"'.$this->strLegendPosition.'"';
		}
		
		if ($this->boolLegendOutSide)
			$arrLegendOptions['container'] =  '$("#'.$this->strID.'_legend")';
		
		$strLegendOptions = $strSep = '';
		foreach ($arrLegendOptions as $strIndex => $strValue) {
			$strLegendOptions .= $strSep.$strIndex.':'.$strValue;
			$strSep = ', ';
		}
		
		$strLegend = '';
		if ($this->strLegendPosition != self::LEGEND_POSITION_NONE AND $this->boolLegendOutSide)
			$strLegend = '
					<td>
						<div id="'.$this->strID.'_legend" style="width:'.$this->intWidthLegend.'px;height:'.$this->intHeightLegend.'px;margin:auto;"></div>
					</td>
			';
		
		$strDivs = '
			<table align="center">
				<tr>
					<td>
						<div id="'.$this->strID.'" style="width:'.$this->intWidth.'px;height:'.$this->intHeight.'px;margin:auto;"></div>
					</td>
					'.$strLegend.'
				</tr>
			</table>
		';
		$strJS = '
			$.plot($("#'.$this->strID.'"), [
				'.$strDataGraph.'
			], {
				legend: {
					'.$strLegendOptions.'
				},
				series: {
					lines: { show: true },
					points: { show: true }
				},
				xaxis: {
					ticks: ['.$strTicks.']
				},
				yaxis: {
					tickFormatter: function (value) {
						return NumberFormatBR(value);
					}
				},
				grid: {
					backgroundColor: { colors: ["#fff", "#eee"] },
					hoverable: true
				}
			});
			$("#'.$this->strID.'").bind("plothover", ToolTipFunc);
		';

		$strReturn = $strDivs.'

			<script>
				$(document).ready(function() {
					'.$strJS.'
				});
			</script>
		';
		return $strReturn;
	}
	
}