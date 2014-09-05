<?php
	class Config {
		public static function getParam($param) {
			$rf = "{".file_get_contents (DIR_APP."config.eoss")."}";
			$config=json_decode($rf);
			return $config->$param;
		}
	}
?>