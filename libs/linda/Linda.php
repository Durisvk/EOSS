<?php
	class Linda {
		public $timeStart;
		public $timeEnd;
		public $showLinda;
		public static function showError($text,$eossfile,$key) {
			self::outputLinda($text,$eossfile,$key);
		}
		private function outputLinda($text,$errfile,$key) {
			$file=file_get_contents($errfile);
			$file=explode("\n",$file);
			$array=array ();
				$line=ModuleGlobal::getLine($key,$file);
			for ($i=$line-3;$i<=$line+3;$i++) {
				if($i!=$line){
					array_push($array,"<span style='font-size: 10px;padding-right:10px'>".$i."</span>".$file[$i]);
				} else {
					array_push($array,"<span style='font-size: 10px;padding-right:10px'>".$i."</span><b style='color:red;'>".$file[$i]."</b>");
				}
			}
			include DIR_LIBS."/linda/LindaLayout.php";
		}
		public static function outputLindaForPHPError($errstr,$errfile,$errline) {
			if(isset($errfile)) {
				$file=file_get_contents($errfile);
				$file=explode("\n",$file);
				$array=array ();
				$line=$errline-2;
				if(strpos($errstr,"unexpected")!=false) {
					if(!strpos($file[$line],"}")) {
						$errstr="syntax error, missing '}'";
					} else if(!strpos($file[$line],"{")) {
						$errstr="syntax error, missing '{'";
					} else if(!strpos($file[$line],";")) {
						$errstr="syntax error, missing ';'";
					}
				}
				$text=$errstr;
				for ($line-5 <= 0 ? $i=0 : $i=$line-5;$line+5>count($file) ? $i<count($file) : $i<=$line+5;$i++) {
					if($i!=$line){
						array_push($array,"<span style='font-size: 10px;padding-right:10px'>".$i."</span>".$file[$i]);
					} else {
						array_push($array,"<span style='font-size: 10px;padding-right:10px;color:red;'>".$i."</span><b style='color:red;'>".$file[$i]."</b>");
					}
				}
				include DIR_LIBS."/linda/LindaLayout.php";
			}
		}
}