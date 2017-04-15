<?php
	echo Form::open('user/save', array('class' => 'form-horizontal', 'method' => 'post'));
?>
	<div class="control-group">
		<label class="control-label" for="inputEmail">Email:</label>
		<div class="controls" style="padding-top:5px;">
			<?php echo $arrUser['email']; ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="name">*<?php echo ___('labels.name'); ?>:</label>
		<div class="controls">
			<input type="text" name="name" id="name" placeholder="<?php echo ___('labels.name'); ?>" value="<?php echo $arrUser['name']; ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="nickname">*<?php echo ___('labels.nickname'); ?>:</label>
		<div class="controls">
			<input type="text" name="nickname" id="nickname" placeholder="<?php echo ___('labels.nickname'); ?>" value="<?php echo $arrUser['nickname']; ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="password"><?php echo ___('labels.password'); ?>:</label>
		<div class="controls">
			<input type="password" name="password" id="password" placeholder="<?php echo ___('labels.password'); ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="password2"><?php echo ___('labels.password2'); ?>:</label>
		<div class="controls">
			<input type="password" name="password2" id="password2" placeholder="<?php echo ___('labels.password2'); ?>">
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<!-- <label class="checkbox"> <input type="checkbox"> Remember me </label> -->
			<button type="submit" class="btn"><?php echo ___('labels.save'); ?></button>
		</div>
	</div>
<?php
	echo Form::close();
?>