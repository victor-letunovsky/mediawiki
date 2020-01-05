<?php
$wgExtensionCredits['other'][] = array (
	'name' => 'PreToClip',
	'version' => '0.1',
	'author' => 'Thomas Candrian, based on the work of Jon Rohan, James M. Greene',
        'url'    => 'https://www.mediawiki.org/wiki/Extension:PreToClip',
	'description' => htmlentities('Adds a copy to clipboard button to every <pre> tag')
);
//
if ( !defined( 'MEDIAWIKI' ) ) {
        die( 'This file is an extension to MediaWiki and thus not a valid entry point.' );
}
//
//$ZeroClipboardFilesDir = dirname($_SERVER["SCRIPT_NAME"]).'/extensions/PreToClip/ZeroClipboard';
//
$nlpObj = new PreToClip;
$wgHooks['BeforePageDisplay'][] = array ($nlpObj,'hPreToClip');
class PreToClip {
	var $completed;	
	function PreToClip() {
		$this->completed = false;
	}
	function hPreToClip($out) {		
		if ($this->completed) {			
			return true;
		}		
		global $action;
		if ($action != 'view' and $action != '')
			return true;
		global $wgRequest;
		// global $ZeroClipboardFilesDir;
		global $GLOBALS;
		$mBodytext = $out->mBodytext;
		$inhaltende = "";
		if (strpos($mBodytext, "<pre>") !== false) {			
			$inhaltende = utf8_decode($mBodytext);
			//preg_match_all("/<(pre[^>]*)>(.*)<\/pre>/siU", $inhaltende, $treffer);
			preg_match_all("/<(pre)>(.*)<\/pre>/siU", $inhaltende, $treffer);
			foreach ($treffer[0] as $key => $value) {
				
				$text1 = "<div style=\"text-align:right; margin-bottom:-35px;\"><button id=\"cp-btn".$key."\" onclick=\"co2cli('preid".$key."')\">cp</button></div>\n";
				$text1 .= "<".$treffer[1][$key]." id=\"preid".$key."\">".$treffer[2][$key]."</pre>\n";
				$inhaltende = str_replace($treffer[0][$key], $text1, $inhaltende);
			}			
                        $text2 = "<script>function co2cli(cId){if(document.selection){var r=document.body.createTextRange();r.moveToElementText(document.getElementById(cId));r.select().createTextRange();document.execCommand('copy');}else if(window.getSelection){var r=document.createRange();r.selectNode(document.getElementById(cId));window.getSelection().addRange(r);document.execCommand('copy');}}</script>";
			$inhaltende = $text2 . $inhaltende;
		}
		//print_r($inhaltende);
		//die();		
		if ($inhaltende != '') {
			$inhaltende = utf8_encode($inhaltende);
			$out->clearHTML();
			$out->addHTML($inhaltende);
		}
		$this->completed = true;
		return true;
	}
}
