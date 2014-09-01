<?php
	class CSIAnalyze {
		protected $file;
		public function __construct($dir) {
			if (!file_exists($dir)) {
				Linda::showError("Error in setFile($dir). File doesn't exist");
			} else {
				$this->file=$dir;
				$this->analyzeCsi();
			}
		}
		
		private function analyzeCsi() {
			$rf = file_get_contents ($this->file);
			// ELEMENT IDs Register
			$elements=ModuleGlobal::getElements($rf);
			$requires="<?php\n";
			$gencsi="\nclass genCSI {\n\n";
			$gencsi.="\tprivate $"."eoss;\n";
			$gencsi.="\tprivate $"."file;\n\n";
			$csivi="";
			$csic = "\tpublic function __construct($"."eoss) {\n";
			$csic .= "\t\t$"."this->eoss="."$"."eoss;\n";
			$csic .= "\t\t$"."this->file='".$this->file."';\n";
			foreach (json_decode($elements) as $element) {
				$file = "<?php\nclass ".$element->id." { \n\n";
				$csivi .= "\tpublic $".$element->id.";\n";
				$requires .= "require_once '".DIR_DATA."genElements/".$element->id.".php';\n";
				$csic .= "\t\t$"."this->".$element->id."=new ".$element->id.";\n";
				foreach ($element as $key => $attribute) {
					$file .= "\tpublic $".$key.";\n";
				}
				$file .= "\n\tpublic function __construct() { \n";
				foreach ($element as $key => $attribute) {
					$attribute=str_replace('"', '\"', $attribute);
					$file .= "\t\t$"."this->".$key.'="'.$attribute.'"'.";\n";
				}
				$file .= "\t}\n\n";
				$file .= "}\n";
				$this->genElement($element->id, $file);
			}
			$gencsi = $requires.$gencsi;
			$gencsi .= $csivi."\n";
			$csic .= "\t}\n";
			$gencsi .= $csic;
			$gencsi .= "\tpublic function setFile($"."dir) {\n";
			$gencsi .= "\t\t$"."this->file="."$"."dir;\n";
			$gencsi .= "\t\t$"."this->csiAnalyze=new CSIAnalyze($"."dir);\n";
			$gencsi .= "\t\t$"."this->eoss->setGenCsi();\n";
			$gencsi .= "\t}\n";
			$gencsi .= "}\n";
			$this->genCSI($gencsi);
		}
		private function genElement($name,$file) {
			$genel=fopen(DIR_DATA . "genElements/".$name.".php", "w") or die("Check out your permissions on file libs/data/!");
			fwrite($genel, $file);
			fclose($genel);
		}
		private function genCSI($file) {
			$genCSI=fopen(DIR_DATA . "genCSI.php", "w") or die("Check out your permissions on file libs/data/!");
			fwrite($genCSI, $file);
			fclose($genCSI);
		}
	}