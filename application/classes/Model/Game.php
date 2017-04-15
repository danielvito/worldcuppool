<?php
/**
 * Game Model
 *
 * @package Classes/model
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
class Model_Game extends Model_App {
	
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 0;
	const STATUS_FINISHED = 2;
	
	/**
	 * Return the table name.
	 * 
	 * @return string
	 */
	public function getTable()
	{
		return 'worldcuppool.game';	
	}
	
	/**
	 * Custom search.
	 *
	 * @param integer $intType    Util_DB::RESULT_COUNT -> Total of rows, Util_DB::RESULT_ROWS -> Registers
	 * @param array   $arrWhere   WHERE clause
	 * @param mixed   $arrOrderBy ORDER BY clause
	 * @param string  $strLimit   LIMIT clause
	 * @return mixed Database_Result or integer
	 */
	public function search($intType = Util_DB::RESULT_ROWS, array $arrWhere = array(), $arrOrderBy = '', $strLimit = '')
	{
		$strSelect = '*';
		
		$strFrom = '
			`bolao`.`game` G
		';
		
		$arrWhere[1] = 1;
		$strWhere = Util_Convert::arrayToSQL($arrWhere);
		
		if ($intType == Util_DB::RESULT_COUNT) {
			$strSQL = '
				SELECT COUNT(*) AS TOTAL
				FROM '.$strFrom.'
				WHERE '.$strWhere;
			$arrDS = $this->_db->query(Database::SELECT, $strSQL)->current();
			return $arrDS['TOTAL'];
		}
		
		if ( ! is_array($arrOrderBy))
			$arrOrderBy = array($arrOrderBy);
		
		$strOrderBy = implode(', ', $arrOrderBy);
		
		if ($strOrderBy)
			$strOrderBy = 'ORDER BY '.$strOrderBy;
		
		if ($strLimit)
			$strLimit = 'LIMIT '.$strLimit;
		
		$strSQL = '
			SELECT '.$strSelect.'
			FROM '.$strFrom.'
			WHERE '.$strWhere.'
			'.$strOrderBy.'
			'.$strLimit;
		return $this->_db->query(Database::SELECT, $strSQL);
	}
	
	public function getGames() {
		$strSQL = '
			SELECT
				G.`id`, G.`status`, G.`description`, G.`place`, G.`id_team_a`, G.`id_team_b`,
				G.`game_date`, G.`score_a`, G.`score_b`,
				TA.`name` AS name_a, TA.`icon` AS icon_a,
				TB.`name` AS name_b, TB.`icon` AS icon_b
			FROM
				worldcuppool.`game` G
				LEFT JOIN worldcuppool.`team` TA ON TA.`id` = G.`id_team_a`
				LEFT JOIN worldcuppool.`team` TB ON TB.`id` = G.`id_team_b`
			WHERE
				1 = 1
				#G.`status` = 1
			ORDER BY G.`game_date`
			;
		';
		return $this->_db->query(Database::SELECT, $strSQL);
	}
	
	public function insertResult(array $arrResult) {
		$strSQL = '
			INSERT INTO worldcuppool.`game`
				(`status`, `id`, `score_a`, `score_b`)
				VALUES
				(
					'.Util_DB::escape($arrResult['status']).',
					'.Util_DB::escape($arrResult['id_game']).',
					'.Util_DB::escape($arrResult['score_a']).',
					'.Util_DB::escape($arrResult['score_b']).'
				)
				ON DUPLICATE KEY UPDATE
					`status` = VALUES(status),
					`score_a` = VALUES(score_a),
					`score_b` = VALUES(score_b)
				;
		';
		$this->_db->query(Database::UPDATE, $strSQL);
		$this->_db->query(Database::UPDATE, 'COMMIT;');
	}
	
}