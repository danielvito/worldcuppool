<?php
	$arrStyles = Util_Media::getStyles();
	$arrScripts = Util_Media::getScripts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo Util_App::config('application.title'); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<?php foreach ($arrStyles as $strHTML) echo $strHTML, PHP_EOL ?>

<!-- Le styles -->
<link href="/media/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="/media/vendor/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
	<script src="media/vendor/bootstrap/js/html5shiv.js"></script>
<![endif]-->

</head>

<body>

	<div id="wrap">

		<?php echo $objViewTop; ?>

		<!-- Begin page content -->
		<div class="container">
			<div class="page-header">
<?php
	echo Form::showMessages();
	if ($strHeader):
		echo Util_Html::title($strHeader, 2);
	endif;
?>
			</div>
			
			<?php echo $objViewContent; ?>

		</div>

		<div id="push"></div>
	</div>

	<?php echo $objViewBottom; ?>

	<!-- javascript -->
	<script src="/media/vendor/jquery/js/jquery.js"></script>
	<script src="/media/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="/media/vendor/bootstrap/js/html5shiv.js"></script>

<?php foreach ($arrScripts as $strHTML) echo $strHTML, PHP_EOL ?>

</body>
</html>