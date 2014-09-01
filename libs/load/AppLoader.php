<?php
	class AppLoader {
		private $app=array();
		public function __construct() {
			foreach (new DirectoryIterator(DIR_APP) as $file) {
				if(!$file->isDot()){
					array_push($this->app,$file->getFilename());
				}
			}
		}
		public function eossInit(&$eosscont) {
			$matches=preg_grep("/([a-zA-Z])+EOSS\.php/",$this->app);
			foreach($matches as $match) {
				include DIR_APP.$match;
				$cls=explode('.',$match);
				array_push($eosscont,new $cls[0]);
			}
		}
	}
?>