<?php
	class CSIAnalyze {
		protected $file;
		public function __construct($csi_file) {
		$dir=DIR_APP . Config::getParam("layout_dir").$csi_file;
			if (!file_exists($dir)) {
				Linda::showError("Error in setFile($dir). File doesn't exist");
			} else {
				$this->file=$dir;
				$this->analyzeCsi();
			}
		}
		
		private function analyzeCsi() {
			// ELEMENT IDs Register
			ModuleGlobal::genFilesForCsi($this->file);
		}
	}