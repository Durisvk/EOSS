<?php
	class ModuleGlobal {
		//FOR CSIAnalyze
		public static function genFilesForCsi($dir) {
			$rf = file_get_contents ($dir);
			$elements=self::getElements($rf);
			$requires="<?php\n";
			$gencsi="\nclass genCSI {\n\n";
			$gencsi.="\tprivate $"."eoss;\n";
			$gencsi.="\tprivate $"."file;\n\n";
			$csivi="";
			$csic = "\tpublic function __construct($"."eoss) {\n";
			$csic .= "\t\t$"."this->eoss="."$"."eoss;\n";
			$csic .= "\t\t$"."this->file='".$dir."';\n";
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
				self::genElement($element->id, $file);
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
			self::genCSI($gencsi);
		}
		private static function genElement($name,$file) {
			$genel=fopen(DIR_DATA . "genElements/".$name.".php", "w") or die("Check out your permissions on file libs/data/!");
			fwrite($genel, $file);
			fclose($genel);
		}
		private static function genCSI($file) {
			$genCSI=fopen(DIR_DATA . "genCSI.php", "w") or die("Check out your permissions on file libs/data/!");
			fwrite($genCSI, $file);
			fclose($genCSI);
		}
		public static function getElements($str) {
			$ids=self::getIds($str);
			$dom=new DOMDocument();
			$dom->loadHTML($str);
			$elements=array();
			foreach ($ids as $id) {
				array_push($elements,self::getElementById($id,$dom));
			}
			$attributes = array();
			$json='{';
			foreach ($elements as $el) {
				$json.='"'.$el->getAttribute("id").'": {';
				foreach ($el->attributes as $name => $attr) {
					$json.= '"'.$name.'": "'.$attr->value.'"';
					$json.=', ';
				}
				$innerHtml=self::getInnerHTML($el);
				$innerHtml=str_replace('"', '\"', $innerHtml);
				$innerHtml != "" ?  $json.='"html": "'.$innerHtml.'"' : $json=rtrim($json, ', ');
				$json.='}';
				$json.=', ';
			}
			$json=rtrim($json, ', ');
			$json.='}';
			return $json;
		}
		static function getIds($str) {
			$ids=array();
			preg_match_all('(id=\"[ a-zA-Z0-9_-]*\")',$str,$matches);
			foreach ($matches[0] as $match) {
				$id=explode("=",$match);
				array_push($ids,str_replace('"','',$id[1]));
			}
			return $ids;
		}
		static function getElementById($id,$dom)
		{
			$xpath = new DOMXPath($dom);
			return $xpath->query("//*[@id='$id']")->item(0);
		}
		static function getInnerHTML(DOMNode $element) 
			{ 
				$innerHTML = ""; 
				$children = $element->childNodes; 
				foreach ($children as $child) 
				{ 
					$tmp_dom = new DOMDocument(); 
					$tmp_dom->appendChild($tmp_dom->importNode($child, true)); 
					$innerHTML.=trim($tmp_dom->saveHTML()); 
				} 
				return $innerHTML; 
			} 

	
		//FOR RequestDealer
		public static function getClassVariables($eoss,$name) {
			setcookie($name, "", time()-3600, '/', $_SERVER['SERVER_NAME']);
			setcookie($name,json_encode(get_object_vars($eoss)), time()+99999, '/', $_SERVER['SERVER_NAME']);
			
		}
		public static function setClassVariables(&$eoss,$name) {
			$json=$_COOKIE[$name];
			$eoss=self::jsonToEoss($eoss,json_decode($json));
		}
		static function jsonToEoss ($eoss,$json) {
		print_r($json);
			foreach ($json as $key=>$val) {
				if(is_object($val)) {
					foreach ($val as $elkey=>$elval) {
						foreach($elval as $attkey=>$attval) {
						echo "<br>";print_r($attkey);
							$eoss->$key->$elkey->$attkey=$attval;
						}
					}
				} else {
					$eoss->$key=$val;
				}
			}
			return $eoss;
		}
		
		public static function writeJsResponse($eoss,$fname) {
			$listOfAttr=json_decode(file_get_contents(DIR_LIBS."eoss/attributeList.dat"));
			$js="function ".$fname."() {";
			if(!isset($eoss->redirect)) {
				foreach (get_object_vars($eoss->csi) as $element) {
					foreach($listOfAttr as $key=>$attr) {
						if(property_exists($element,$key)) {
							$js.="$( '#".$element->id."' ).".$attr."(\n'";
							$js.=preg_replace( "/\r|\n/", "", $element->$key);
							print_r($key);
							$js.="');\n";
						}
					}
				}
			} else {
				$js.="location.reload();";
			}
			$js.="}";
			$genjs=fopen(DIR_DATA . "genJs/genFunctions.js", "w") or die("Check out your permissions on file libs/data/!");
			fwrite($genjs, $js);
			fclose($genjs);
		}
		
		//FOR GenJs
		public static function checkForEvents($attr,$class) {
			$listOfProp=json_decode(file_get_contents(DIR_LIBS."eoss/eventList.dat"));
			$js="";
			foreach ($listOfProp as $key=>$prop) {
				if(property_exists($attr,$key)) {
					$e=false;
					if(strpos($prop,":")!=false) {
						$e=true;
						$s=explode(":",$prop);
						$prop=$s[0];
						$param=$s[1];
					}
						$js.="$( '#".$attr->id."' ).bind('".$prop."',function (";
						$e ? $js.="event" : $js.="";
						$js.=") {";
						$js.="$.get('libs/eoss/RequestDealer.php',{'eoss':'".$class."','id':'".$attr->id."','event':'".$key."','values':createJSON()";
						$e ? $js.=",'param': event.".$param.", curValue:$(this).val()+String.fromCharCode(event.keyCode)" : $js.="";
						$js.="}, function (data) {
								$( '#jsRefresh' ).html('<script src=\"libs/data/genJs/genFunctions.js\">');
								".$attr->id.$key."(data);
							});
						});";
				}
			}
			return $js;
		}
		//FOR Linda
		public static function getLine($search,$file) {
			for ($i=0; $i<count($file);$i++) {
				$line=$file[$i];
				if (strpos($line, $search)!==FALSE) {
					$line_number=$i;
				}
			}
			return $line_number;
		}
}