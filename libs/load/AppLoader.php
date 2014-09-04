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
		public function eossInit(&$eosscont,$refresh) {
			$matches=preg_grep("/([a-zA-Z])+EOSS\.php/",$this->app);
			foreach($matches as $match) {
				include DIR_APP.$match;
				$cls=explode('.',$match);
				$eoss=new $cls[0];
				array_push($eosscont,$eoss);
				if($refresh==true) {
					setcookie($cls[0], "", time()-3600, '/', $_SERVER['SERVER_NAME']);
				}
			}
		}
	}
?>