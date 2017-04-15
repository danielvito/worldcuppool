<?php
	$strPadding = 'padding:3px;';
?>
<div class="row">
	<div class="span2">
	</div>
	<div class="span8">
	
		<table class="" style="border:1px solid #dddddd;margin-bottom:8px;" border="1" align="center">
			<thead>
				<tr style="background-color:#CCCCCC;">
					<th style="text-align:center;<?php echo $strPadding; ?>">#</th>
					<th style="text-align:center;<?php echo $strPadding; ?>">Participante</th>
					<th style="text-align:center;<?php echo $strPadding; ?>">Pontos</th>
					<th style="text-align:center;<?php echo $strPadding; ?>">Aprov.</th>
					<th style="text-align:center;<?php echo $strPadding; ?>">Campeão</th>
					<th style="text-align:center;<?php echo $strPadding; ?>">Pts</th>
					<th style="text-align:center;<?php echo $strPadding; ?>">Vice</th>
					<th style="text-align:center;<?php echo $strPadding; ?>">Pts</th>
					<th style="text-align:center;<?php echo $strPadding; ?>">Terceiro</th>
					<th style="text-align:center;<?php echo $strPadding; ?>">Pts</th>
					<th style="text-align:center;<?php echo $strPadding; ?>">Artilheiro</th>
					<th style="text-align:center;<?php echo $strPadding; ?>">Pts</th>
				</tr>
			</thead>
<?php
		$strBG = '';
		$intPosition = 0;
		foreach ($arrPointsUser as $intID => $intPoints):
			$intPosition ++;
			$strBG = ($strBG == '#ffffff')? '#f9f9f9' : '#ffffff';
			$arrBets = $arrUserBets[$intID];
			$strNickName = $arrUsers[$intID];
			$strTeam1 = isset($arrTeams[$arrBets['id_team1']])? $arrTeams[$arrBets['id_team1']] : '-';
			$strTeam2 = isset($arrTeams[$arrBets['id_team2']])? $arrTeams[$arrBets['id_team2']] : '-';
			$strTeam3 = isset($arrTeams[$arrBets['id_team3']])? $arrTeams[$arrBets['id_team3']] : '-';
			$strStriker = isset($arrBets['striker'])? $arrBets['striker'] : '-';
			$intPercentage = $intPoints / ($intTotalPlayed * 18) * 100;
			
?>
			<tr style="background-color:<?php echo $strBG; ?>;">
				<td style="text-align:right;<?php echo $strPadding; ?>"><?php echo $intPosition; ?>°</td>
				<td style="text-align:left;<?php echo $strPadding; ?>"><?php echo $strNickName; ?></td>
				<td style="text-align:right;<?php echo $strPadding; ?>"><?php echo $intPoints; ?></td>
				<td style="text-align:right;<?php echo $strPadding; ?>"><?php echo Util_Format::decimal($intPercentage); ?>%</td>
				<td style="text-align:center;<?php echo $strPadding; ?>"><?php echo $strTeam1; ?></td>
				<td style="text-align:right;<?php echo $strPadding; ?>"><?php echo $arrUserBets[$intID]['team1_points']; ?></td>
				<td style="text-align:center;<?php echo $strPadding; ?>"><?php echo $strTeam2; ?></td>
				<td style="text-align:right;<?php echo $strPadding; ?>"><?php echo $arrUserBets[$intID]['team2_points']; ?></td>
				<td style="text-align:center;<?php echo $strPadding; ?>"><?php echo $strTeam3; ?></td>
				<td style="text-align:right;<?php echo $strPadding; ?>"><?php echo $arrUserBets[$intID]['team3_points']; ?></td>
				<td style="text-align:center;<?php echo $strPadding; ?>"><?php echo $strStriker; ?></td>
				<td style="text-align:right;<?php echo $strPadding; ?>"><?php echo $arrUserBets[$intID]['striker_points']; ?></td>
			</tr>
<?php
		endforeach;
?>
		</table>
	</div>
	<div class="span2">
	</div>
</div>
<?php
	if ($boolPalpites):
		echo Util_Html::showMessage(Form::ALERT, ___('messages.info_not_available'));
	elseif ( ! $objRSGames->count()):
		echo Util_Html::showMessage(Form::ERROR, ___('errors.no_found_rows'));
	else:
?>
	<table class="" style="border:1px solid #dddddd;margin-bottom:8px;" border="1" align="center">
		<thead>
			<tr style="background-color:#CCCCCC;">
				<!-- <th style="text-align:center;">Data / Fase</th> -->
				<th style="text-align:center;" colspan="5">Resultado</th>
<?php
		foreach ($arrUsers as $intID => $strNickName):
?>
				<th style="text-align:center;"><?php echo $strNickName; ?></th>
				<th style="text-align:center;">Pts</th>
<?php
		endforeach;
?>
			</tr>
		</thead>
<?php
		$strBG = '';
		$intTotalPoints = 0;
		$arrTotalByUser = array();
		foreach ($objRSGames as $arrDS):
			$intGameID = $arrDS['id'];
			$objDate = Util_Date::factory($arrDS['game_date']);
			$strBG = ($strBG == '#ffffff')? '#f9f9f9' : '#ffffff';
			$strFont = ($arrDS['status'])? '#990000' : '#000000';
?>
		<tr style="background-color:<?php echo $strBG; ?>;">
			<td style="text-align:center;<?php echo $strPadding; ?>min-width:20px;"><?php echo HTML::image('/media/img/icons/'.$arrDS['icon_a']); ?></td>
			<td style="text-align:right;<?php echo $strPadding; ?>">
				<span style="color: <?php echo $strFont; ?>"><?php echo $arrDS['name_a']?></span>
			</td>
			<td style="text-align:center;<?php echo $strPadding; ?>" nowrap="nowrap">
				<span style="color: <?php echo $strFont; ?>">
				<?php if ($arrDS['status']): ?>
					<?php echo $arrDS['score_a']; ?>
					x
					<?php echo $arrDS['score_b']; ?>
				<?php else: ?>
					<?php echo '-'; ?>
				<?php endif; ?>
				</span>
			</td>
			<td style="text-align:left;<?php echo $strPadding; ?>">
				<span style="color: <?php echo $strFont; ?>"><?php echo $arrDS['name_b']?></span>
			</td>
			<td style="text-align:center;<?php echo $strPadding; ?>min-width:20px;"><?php echo HTML::image('/media/img/icons/'.$arrDS['icon_b']); ?></td>
<?php
			foreach ($arrUsers as $intID => $strNickName):
				if ( ! isset($arrTotalByUser[$intID]))
					$arrTotalByUser[$intID] = 0;
				$strResult = '-';
				if (isset($arrUserGames[$intID][$intGameID]) AND $arrUserGames[$intID][$intGameID]['user_status'] == 1):
					$strScoreA = $arrUserGames[$intID][$intGameID]['score_a_user'];
					$strScoreB = $arrUserGames[$intID][$intGameID]['score_b_user'];
					$strResult = $strScoreA.' x '.$strScoreB;
				endif;
				$intPoints = '-';
				if (isset($arrPointsUserGame[$intID][$intGameID]) AND $arrDS['status'] == 1):
					$intPoints = $arrPointsUserGame[$intID][$intGameID];
					$arrTotalByUser[$intID] += $intPoints;
				endif;
?>
			<td rowspan="2" style="text-align:center;<?php echo $strPadding; ?>"><?php echo $strResult; ?><br /><span style="font-size:10px;font-style:italic;"><?php echo $strNickName; ?><span/></td>
			<td rowspan="2" style="text-align:right;<?php echo $strPadding; ?>"><?php echo $intPoints; ?></td>
<?php
			endforeach;
?>
		</tr>
		<tr style="background-color:<?php echo $strBG; ?>;">
			<td colspan="5" style="text-align:center;<?php echo $strPadding; ?>" nowrap="nowrap">
				<span style="color: <?php echo $strFont; ?>">
					<?php echo Util_Date::toBR($objDate).' '.$objDate->getHour().'hs';?> / <?php echo $arrDS['description']?>
				</span>
			</td>
		</tr>
<?php
		endforeach;
?>
		<tr style="font-weight:bold;">
			<td colspan="5" style="<?php echo $strPadding; ?>">Total</td>
<?php
		foreach ($arrUsers as $intID => $strNickName):
			$intPoints = 0;
			if (isset($arrTotalByUser[$intID])):
				$intPoints = $arrTotalByUser[$intID];
			endif;
?>
			<td style="text-align:center;<?php echo $strPadding; ?>"><?php echo $strNickName; ?></td>
			<td style="text-align:right;<?php echo $strPadding; ?>"><?php echo $intPoints; ?></td>
<?php
		endforeach;
?>
		</tr>
	</table>
<?php
	endif;
?>