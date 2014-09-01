<?php
	class CSI {
		private $csiAnalyze;
		private $eoss;
		private $file;
		public function __construct($eoss) {
			$this->eoss=$eoss;
		}
		public function setFile($dir) {
			$this->file=$dir;
			$this->csiAnalyze=new CSIAnalyze($dir);
			$this->eoss->setGenCsi();
		}
		public function getFile() {
			return $this->file;
		}
	}