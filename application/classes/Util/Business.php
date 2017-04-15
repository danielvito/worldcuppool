<?php
/**
 * App business
 *
 * @package Classes/util
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
class Util_Business {
	
	public static function gamePoints($intScoreA, $intScoreB, $intScoreAUser, $intScoreBUser) {
		if ($intScoreA == $intScoreAUser AND $intScoreB == $intScoreBUser)
			return 18;
		if ($intScoreA == $intScoreB AND $intScoreAUser == $intScoreBUser)
			return 9;

		$intPoints = 0;
		if ($intScoreA > $intScoreB AND $intScoreAUser > $intScoreBUser)
			$intPoints += 9;
		elseif ($intScoreA < $intScoreB AND $intScoreAUser < $intScoreBUser)
			$intPoints += 9;
		
		if ($intScoreA == $intScoreAUser OR $intScoreB == $intScoreBUser)
			$intPoints += 3;
		
		return $intPoints;
	}
	
}