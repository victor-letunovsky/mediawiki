<?php
$wgExtensionCredits['other'][] = array (
	'name' => 'PreToClip',
	'version' => '0.2.0',
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
$wgClpbImg = $GLOBALS['wgScriptPath'] . '/extensions/PreToClip/clpb.png';
$nlpObj = new PreToClip;
$clpbTagIdx = 0;
$clpbTagFirst = true;
$clpbTagIdxFirst = true;
$clpbStyleFirst = true;
$wgHooks['BeforePageDisplay'][] = array ($nlpObj,'hPreToClip');
$co2cliScript = "<script src='" . $GLOBALS['wgScriptPath'] . "/extensions/PreToClip/co2cliScript.js'></script>";
$cp2clpbStyle = "<link rel='stylesheet' href='" . $GLOBALS['wgScriptPath'] . "/extensions/PreToClip/cp2clpb.css'/>";

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
		$mBodytext = $out->mBodytext;
		$inhaltende = "";
		$isPre = strpos($mBodytext, "<pre>") !== false;
		if ($isPre) {
			$inhaltende = utf8_decode($mBodytext);
			//preg_match_all("/<(pre[^>]*)>(.*)<\/pre>/siU", $inhaltende, $treffer);
			preg_match_all("/<(pre)>(.*)<\/pre>/siU", $inhaltende, $treffer);
			foreach ($treffer[0] as $key => $value) {
				$text1 = "<div style=\"text-align:right; margin-bottom:-35px;\"><button id=\"cp-btn".$key."\" onclick=\"co2cli('preid".$key."')\">cp</button></div>\n";
				$text1 .= "<".$treffer[1][$key]." id=\"preid".$key."\">".$treffer[2][$key]."</pre>\n";
				$inhaltende = str_replace($treffer[0][$key], $text1, $inhaltende);
			}
			global $co2cliScript, $cp2clpbStyle;
			$out->addHeadItem( 'co2cli-script', $co2cliScript );
			$out->addHeadItem( 'cp2clpb-style', $cp2clpbStyle );
		}
		if ($inhaltende != '') {
			$inhaltende = utf8_encode($inhaltende);
			$out->clearHTML();
			$out->addHTML($inhaltende);
		}
		$this->completed = true;
		return true;
	}
}

$wgHooks['ParserFirstCallInit'][] = 'wfClpbInit';

function wfClpbInit( Parser $parser ) {
	$parser->setHook( 'clpb', 'wfAddClpbTag' );
	return true;
}

function wfAddClpbTag( $input, array $args, Parser $parser, PPFrame $frame ) {
	global $wgClpbImg;

	global $co2cliScript, $cp2clpbStyle;
	static $clpbScriptFirst = true;
	if ( $clpbScriptFirst ) {
		$parser->mOutput->addHeadItem( $co2cliScript, 'co2cli-script' );
		$parser->mOutput->addHeadItem( $cp2clpbStyle, 'cp2clpb-style' );
		$clpbScriptFirst = false;
	}

	$html = '';
	if (isset($args['show']) && $args['show']) {
		global $clpbTagIdx;
		$spnid = 'spnid' . $clpbTagIdx;
		$escapedInput = htmlspecialchars( $input, ENT_QUOTES, 'UTF-8' );
		$html .= '<span id="' . $spnid . '">' . $escapedInput . '</span> <button onclick="co2cli(\'' . $spnid . '\')" class="cp2clpb"></button>';
		$clpbTagIdx += 1;
	} else {
		$base64Input = base64_encode( $input );
		$html .= '<button data-clipboard-b64="' . $base64Input . '" onclick="cp2clpb(decodeBase64(this.getAttribute(\'data-clipboard-b64\')))" class="cp2clpb"></button>';
	}
	return $html;
}
