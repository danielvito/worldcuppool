<?php
	if ( ! $objRSGames->count()):
		echo Util_Html::showMessage(Form::ERROR, ___('errors.no_found_rows'));
	else:
		echo Form::open('results/save', array('method' => 'post'));
?>
	<div class="form-actions" style="margin:6px 0px;padding:6px;text-align:right;">
<?php
		echo Form::submit('BTN_SEND', ___('labels.save'), array('class' => 'btn btn-danger'));
?>
	</div>
	<table class="table table-bordered table-striped table-hover table-condensed">
		<thead>
			<tr>
				<th style="text-align:center;">Data</th>
				<th style="text-align:center;">Local</th>
				<th style="text-align:center;">Fase</th>
				<th style="text-align:center;" colspan="5">Jogo</th>
				<th style="text-align:center;">Terminado</th>
			</tr>
		</thead>
<?php
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
			<?php echo Form::input('score_a['.$arrDS['id'].']', $arrDS['score_a'], array('class' => 'input-mini', 'style' => 'width:20px;padding-bottom:4px;margin-bottom:0px;', 'maxlength' => 2))?>
			<span>x</span>
			<?php echo Form::input('score_b['.$arrDS['id'].']', $arrDS['score_b'], array('class' => 'input-mini', 'style' => 'width:20px;padding-bottom:4px;margin-bottom:0px;', 'maxlength' => 2))?>
		</td>
		<td style="text-align:left;padding-top:8px;">
			<span><?php echo $arrDS['name_b']?></span>
		</td>
		<td style="text-align:center;"><?php echo HTML::image('/media/img/icons/'.$arrDS['icon_b']); ?></td>
		<td style="text-align:center;"><?php echo Form::checkbox('finished['.$arrDS['id'].']', 'on', ($arrDS['status'])? TRUE : FALSE); ?></td>
	</tr>
<?php
		endforeach;
?>
	</table>
	<div class="form-actions" style="margin:6px 0px;padding:6px;text-align:right;">
<?php
		echo Form::submit('BTN_SEND', ___('labels.save'), array('class' => 'btn btn-danger'));
?>
	</div>
<?php
		Form::close();	
	endif;
?>