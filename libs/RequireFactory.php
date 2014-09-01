<?php
	include DIR_APP."layout.html";
	$ex=0;
	echo '<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>';
	foreach (new DirectoryIterator(DIR_DATA."genJs/") as $file) {
		if(!$file->isDot()) {
			if($file->getFilename()!="genFunctions.js") {
				echo "<script src='libs/data/genJs/".$file->getFilename()."'></script>";
			} else {
				$ex=1;
				echo "<div id='jsRefresh'><script src='libs/data/genJs/".$file->getFilename()."'></script></div>";
			}
		}
	}
	if($ex==0) echo "<div id='jsRefresh'></div>";
	echo "<script src='libs/eoss/functions.js'></script>";
?>