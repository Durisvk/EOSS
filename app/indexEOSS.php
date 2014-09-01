<?php
	class indexEOSS extends EOSS {
		public function load() {
			$this->csi->setFile(DIR_APP . "layout.html");
		}
		public function bind() {
		}
	}