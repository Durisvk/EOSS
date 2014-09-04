<?php
	$rf = "{".file_get_contents (DIR_APP."config.eoss")."}";
	$config=json_decode($rf);
?>