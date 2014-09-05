<?php
	$ex=false;
	echo '<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>';
	foreach (new DirectoryIterator(DIR_DATA."genJs/") as $file) {
		if(!$file->isDot()) {
			if($file->getFilename()!="genFunctions.js") {
				if(isset($_COOKIE['curEOSS']) && $file->getBasename()==$_COOKIE['curEOSS'].".js") {
					echo "<script src='libs/data/genJs/".$file->getFilename()."'></script>";
				} else if(Config::getParam("home_eoss").".js"==$file->getBasename()) {
					echo "<script src='libs/data/genJs/".$file->getFilename()."'></script>";
				}
			} else {
				$ex=true;
				echo "<div id='jsRefresh'><script src='libs/data/genJs/".$file->getFilename()."'></script></div>";
			}
		}
	}
	if($ex==false) echo "<div id='jsRefresh'></div>";
	echo "<script src='libs/eoss/functions.js'></script>";
?>