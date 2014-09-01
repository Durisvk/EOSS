<?php
	class EOSS {
		public $csi;
		public function __construct() {
			$this->csi = new CSI($this);
			$this->load();
		}
		public function setGenCsi() {
			$this->csi = new genCSI($this);
			$this->bind();
			$this->genJs();
		}
		private function genJs() {
			$js="";
			foreach($this->csi as $key=>$attr) {
				$js.=ModuleGlobal::checkForEvents($attr,get_class($this));
			}
			$genjs=fopen(DIR_DATA . "genJs/".get_class($this).".js", "w") or die("Check out your permissions on file libs/data/!");
			fwrite($genjs, $js);
			fclose($genjs);
		}
		public function load() {
			
		}
	}