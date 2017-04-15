<?php
	if ( ! $objRSCourses->count()):
	
	else:
?>

<ul id="MAIN_NAV" class="nav nav-tabs">
	<li class="active">
		<a href="#"><?php echo ___('labels.course_status.1'); ?></a>
	</li>
	<li>
		<a href="#"><?php echo ___('labels.course_status.2'); ?></a>
	</li>
</ul>
<?php
	endif;
?>