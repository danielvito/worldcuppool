		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">
					<button type="button" class="btn btn-navbar" data-toggle="collapse"
						data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="brand" href="/"><?php echo Util_App::config('application.project'); ?></a>
					<div class="nav-collapse collapse">
<?php
	if ( ! Util_App::isLoggedIn()):
		echo Form::open('/system/login', array('class' => 'navbar-form pull-right'));
?>
		<?php echo Form::input('login', NULL, array('class' => 'span2', 'placeholder' => ___('labels.email'))); ?>
		<?php echo Form::password('password', NULL, array('class' => 'span2', 'placeholder' => ___('labels.password'))); ?>
		<?php echo Form::submit(NULL, ___('labels.signin')); ?>
<?php
		echo Form::close();
	else:
		$strName = 'btn_logout';
?>

						<ul class="nav pull-right">
							<li>
								
								<div class="btn-group">
									<a class="btn btn-primary" href="/user/mydata"><i class="icon-user icon-white"></i>
										<?php echo Util_App::session('USR.nickname'); ?>
									</a>
									<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
									<ul class="dropdown-menu">
										<!-- 
										<li><a href="#"><i class="icon-align-justify"></i><?php echo ___('labels.reports'); ?></a></li>
										<li><a href="#"><i class="icon-envelope"></i><?php echo ___('labels.news'); ?></a></li>
										<li class="divider"></li>
										 -->
										<li><a href="/user/mydata"><i class="icon-wrench"></i><?php echo ___('labels.configuration'); ?></a></li>
									</ul>
								</div>
								
							</li>
							<li class="divider-vertical"></li>
							<li>
								<?php echo Form::button($strName, ___('labels.logout'), array('class' => '', 'id' => $strName)); ?>
							</li>
						</ul>
<?php
	endif;
?>
						<ul class="nav">
<?php
	foreach ($arrMenus as $arrMenu):
		$strClass = ('/'.$strController.'/'.$strAction == $arrMenu[0])? 'active' : '';
?>
							<li class="<?php echo $strClass; ?>"><a href="<?php echo $arrMenu[0]; ?>"><?php echo $arrMenu[1]; ?> </a></li>
<?php
	endforeach;
	
	
?>
						</ul>
					</div>
					<!--/.nav-collapse -->
				</div>
			</div>
		</div>