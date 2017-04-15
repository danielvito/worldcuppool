<?php
/**
 * Game User Model
 *
 * @package Classes/model
 * @author  Daniel de Vito <daniel_vito@yahoo.com.br>
 */
class Model_GameUser extends Model_App {
	
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 0;
	
	/**
	 * Return the table name.
	 * 
	 * @return string
	 */
	public function getTable()
	{
		return 'worldcuppool.game_user';	
	}
	
	public function getGamesByUser($COD_USER) {
		$strSQL = '
			SELECT
				G.`id`, G.`status`, G.`description`, G.`place`, G.`id_team_a`, G.`id_team_b`,
				G.`game_date`, G.`score_a`, G.`score_b`,
				GU.`score_a` AS score_a_user, GU.`score_b` AS score_b_user, GU.`status` AS user_status,
				TA.`name` AS name_a, TA.`icon` AS icon_a,
				TB.`name` AS name_b, TB.`icon` AS icon_b
			FROM
				worldcuppool.`game` G
				LEFT JOIN worldcuppool.`game_user` GU ON GU.`id_game` = G.`id` AND GU.`id_user` = '.Util_DB::escape($COD_USER).'
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
	
	public function insertBet(array $arrBet) {
		$strSQL = '
			INSERT INTO worldcuppool.`game_user`
				(`create`, `status`, `id_user`, `id_game`, `score_a`, `score_b`)
				VALUES
				(
					NOW(),
					'.Util_DB::escape($arrBet['status']).',
					'.Util_DB::escape($arrBet['id_user']).',
					'.Util_DB::escape($arrBet['id_game']).',
					'.Util_DB::escape($arrBet['score_a']).',
					'.Util_DB::escape($arrBet['score_b']).'
				)
				ON DUPLICATE KEY UPDATE
					`score_a` = VALUES(score_a),
					`score_b` = VALUES(score_b)
				;
		';

		$this->_db->query(Database::UPDATE, $strSQL);
		$this->_db->query(Database::UPDATE, 'COMMIT;');
	}

}