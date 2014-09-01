<?php
	class ModuleGlobal {
		//FOR CSIAnalyze
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
		function getIds($str) {
			$ids=array();
			preg_match_all('(id=\"[ a-zA-Z0-9_-]*\")',$str,$matches);
			foreach ($matches[0] as $match) {
				$id=explode("=",$match);
				array_push($ids,str_replace('"','',$id[1]));
			}
			return $ids;
		}
		function getElementById($id,$dom)
		{
			$xpath = new DOMXPath($dom);
			return $xpath->query("//*[@id='$id']")->item(0);
		}
		function getInnerHTML(DOMNode $element) 
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
		public static function getClassVariables($eoss) {
			setcookie("current_state", "", time()-3600);
			setcookie("current_state",json_encode(get_object_vars($eoss)));
			
		}
		public static function setClassVariables(&$eoss) {
			$json=$_COOKIE['current_state'];
			$eoss=self::jsonToEoss($eoss,json_decode($json));
		}
		function jsonToEoss ($eoss,$json) {
			foreach ($json as $key=>$val) {
				if(is_object($val)) {
					foreach ($val as $elkey=>$elval) {
						foreach($elval as $attkey=>$attval) {
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
			foreach (get_object_vars($eoss->csi) as $element) {
				foreach($listOfAttr as $key=>$attr) {
					if(property_exists($element,$key)) {
						$js.="$( '#".$element->id."' ).".$attr."('".$element->$key."');";
					}
				}
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
					$js.="$( '#".$attr->id."' ).".$prop."(function () {
					$.get('libs/eoss/RequestDealer.php',{'eoss':'".$class."','id':'".$attr->id."','event':'".$key."','values':createJSON()}, function (data) {
							$( '#jsRefresh' ).html('<script src=\"libs/data/genJs/genFunctions.js\">');
							".$attr->id.$key."(data);
						});
					});";
				}
			}
			return $js;
		}
}