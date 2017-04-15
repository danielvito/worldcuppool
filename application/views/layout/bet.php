<?php
	if ( ! $objRSGames->count()):
		echo Util_Html::showMessage(Form::ERROR, ___('errors.no_found_rows'));
	else:

		if ($boolPalpites):
			echo Form::open('bet/save', array('method' => 'post'));
?>
	<div class="form-actions" style="margin:6px 0px;padding:6px;text-align:right;">
<?php
			echo Form::submit('BTN_SEND', ___('labels.save'), array('class' => 'btn btn-danger'));
?>
	</div>
<?php
		else:
			echo Util_Html::showMessage(Form::ALERT, ___('messages.bet_closed'));
		endif;
		
		$strTeam1 = isset($arrTeams[$arrDS['id_team1']])? $arrTeams[$arrDS['id_team1']] : '-';
		$strTeam2 = isset($arrTeams[$arrDS['id_team2']])? $arrTeams[$arrDS['id_team2']] : '-';
		$strTeam3 = isset($arrTeams[$arrDS['id_team3']])? $arrTeams[$arrDS['id_team3']] : '-';
		$strStriker = $arrDS['striker'];
		if ($boolPalpites AND $boolPalpitesAdicionais):
			$strTeam1 = Util_Filter::team(isset($arrDS['id_team1'])? $arrDS['id_team1'] : NULL, 'id_team1');
			$strTeam2 = Util_Filter::team(isset($arrDS['id_team2'])? $arrDS['id_team2'] : NULL, 'id_team2');
			$strTeam3 = Util_Filter::team(isset($arrDS['id_team3'])? $arrDS['id_team3'] : NULL, 'id_team3');
			$strStriker = Form::input('striker', isset($arrDS['striker'])? $arrDS['striker'] : NULL, array('style' => 'margin-bottom:0px;'));
		endif;
?>
	<div class="row">
		<div class="span3"></div>
		<div class="span6">
			<table class="table table-bordered table-striped table-hover table-condensed">
				<thead>
					<tr>
						<th style="text-align:center;">Critério</th>
						<th style="text-align:center;">Escolha</th>
						<th style="text-align:center;">Pontos</th>
					</tr>
				</thead>
				<tr>
					<td>1o Lugar (50 Pts)</td>
					<td><?php echo $strTeam1; ?></td>
					<td style="text-align:right;"><?php echo $arrDS['team1_points']; ?></td>
				</tr>
				<tr>
					<td>2o Lugar (40 Pts)</td>
					<td><?php echo $strTeam2; ?></td>
					<td style="text-align:right;"><?php echo $arrDS['team2_points']; ?></td>
				</tr>
				<tr>
					<td>3o Lugar (30 Pts)</td>
					<td><?php echo $strTeam3; ?></td>
					<td style="text-align:right;"><?php echo $arrDS['team3_points']; ?></td>
				</tr>
				<tr>
					<td>Artilheiro (30 Pts)</td>
					<td><?php echo $strStriker; ?></td>
					<td style="text-align:right;"><?php echo $arrDS['striker_points']; ?></td>
				</tr>
			</table>
		</div>
		<div class="span3"></div>
	</div>
	<table class="table table-bordered table-striped table-hover table-condensed">
		<thead>
			<tr>
				<th style="text-align:center;">Data</th>
				<th style="text-align:center;">Local</th>
				<th style="text-align:center;">Fase</th>
				<th style="text-align:center;" colspan="5">Jogo</th>
				<th style="text-align:center;">Resultado</th>
				<th style="text-align:center;">Pontos</th>
			</tr>
		</thead>
<?php
		$strDateNow = Util_Date::toSQL(NULL, TRUE);
		$intTotalPoints = 0;
		foreach ($objRSGames as $arrDS):
			$objDate = Util_Date::factory($arrDS['game_date']);
?>
	<tr>
		<td style="text-align:center;padding-top:8px;"><?php echo Util_Date::toBR($objDate).' '.$objDate->getHour().'hs';?></td>
		<td style="text-align:center;padding-top:8px;"><?php echo $arrDS['place']?></td>
		<td style="text-align:center;padding-top:8px;"><?php echo $arrDS['description']?></td>
		<td style="text-align:center;"><?php echo HTML::image('/media/img/icons/'.$arrDS['icon_a']); ?></td>
		<td style="text-align:right;padding-top:8px;">
			<?php echo $arrDS['name_a']?>
		</td>
		<td style="text-align:center;">
<?php
			if ($boolPalpites AND $arrDS['game_date'] > $strDateNow):
?>
			<?php echo Form::input('score_a['.$arrDS['id'].']', $arrDS['score_a_user'], array('class' => 'input-mini', 'style' => 'width:20px;padding-bottom:4px;margin-bottom:0px;', 'maxlength' => 2))?>
			<span>x</span>
			<?php echo Form::input('score_b['.$arrDS['id'].']', $arrDS['score_b_user'], array('class' => 'input-mini', 'style' => 'width:20px;padding-bottom:4px;margin-bottom:0px;', 'maxlength' => 2))?>
<?php
			else:
?>
			<?php echo $arrDS['score_a_user']; ?>
			<span>x</span>
			<?php echo $arrDS['score_b_user']; ?>
<?php			
			endif;
?>
		</td>
		<td style="text-align:left;padding-top:8px;">
			<span><?php echo $arrDS['name_b']?></span>
		</td>
		<td style="text-align:center;"><?php echo HTML::image('/media/img/icons/'.$arrDS['icon_b']); ?></td>
		<td style="text-align:center;padding-top:8px;">
			<?php if ($arrDS['status']): ?>
			<span><?php echo $arrDS['score_a']?> x <?php echo $arrDS['score_b']?></span>
			<?php else: ?>
			-
			<?php endif; ?>
		</td>
		<td style="text-align:right;padding-top:8px;">
			<?php if ($arrDS['status']):
				$intPoints = Util_Business::gamePoints($arrDS['score_a'], $arrDS['score_b'], $arrDS['score_a_user'], $arrDS['score_b_user']);
				$intTotalPoints += $intPoints;
			?>
			<span><?php echo $intPoints; ?></span>
			<?php else: ?>
			-
			<?php endif; ?>
		</td>
	</tr>
<?php
		endforeach;
?>
	<tr>
		<td style="text-align:left;padding-top:8px;font-weight:bold;" colspan="9">
			<?php echo ___('labels.total'); ?>
		</td>
		<td style="text-align:right;padding-top:8px;">
			<?php echo $intTotalPoints; ?>
		</td>
	</tr>
	</table>
<?php
		if ($boolPalpites):
?>
	<div class="form-actions" style="margin:6px 0px;padding:6px;text-align:right;">
<?php
			echo Form::submit('BTN_SEND', ___('labels.save'), array('class' => 'btn btn-danger'));
?>
	</div>
<?php
		else:
			echo Util_Html::showMessage(Form::ALERT, ___('messages.bet_closed'));
		endif;

		Form::close();	
	endif;
?>