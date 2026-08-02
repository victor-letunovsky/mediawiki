<?php
/**
 * CopyToClipboard - this extension lets you put a copy to clipboard button
 *
 * To activate this extension, add the following into your LocalSettings.php file:
 * require_once('$IP/extensions/CopyToClipboard/CopyToClipboard.php');
 *
 * @ingroup Extensions
 * @author Nischay Nahata <nischayn22@gmail.com>
 * @link https://www.mediawiki.org/wiki/Extension:CopyToClipboard
 */

/**
 * Protect against register_globals vulnerabilities.
 * This line must be present before any global variable is referenced.
 */
if( !defined( 'MEDIAWIKI' ) ) {
        echo( "This is an extension to the MediaWiki package and cannot be run standalone.\n" );
        die( -1 );
}

// Extension credits that will show up on Special:Version
$wgExtensionCredits['parser extensions'][] = array(
        'path'           => __FILE__,
        'name'           => 'CopyToClipboard',
        'version'        => '0.3.0',
        'author'         => 'Nischay Nahata',
        'url'            => 'https://www.mediawiki.org/wiki/Extension:CopyToClipboard',
        'descriptionmsg' => 'copytoclipboard-desc',
        'license-name'   => 'GPL-3.0-or-later' // GNU General Public License v3.0 or later
);
$wgExtensionCredits['parserhook'][] = array(
        'name'           => 'CopyToClipboard',
        'version'        => '0.3.0',
        'author'         => 'Nischay Nahata',
        'url'            => 'https://www.mediawiki.org/wiki/Extension:CopyToClipboard',
        'description'    => 'Adds a tag to show a "<tt>copy to clipboard</tt>" button on pages',
);

$wgMessagesDirs['CopyToClipboard'] = __DIR__ . '/i18n';

$wgHooks['ParserFirstCallInit'][] = 'wfCopyToClipboardInit';

function wfCopyToClipboardInit( Parser $parser ) {
    $parser->setHook( 'clippy', 'wfAddCopyToClipboardTag' );
    return true;
}

function wfAddCopyToClipboardTag( $input, array $args, Parser $parser, PPFrame $frame ) {
	global $copyToClipboardScriptFirst;
	if ( !isset( $copyToClipboardScriptFirst ) ) {
		$copyToClipboardScriptFirst = true;
	}

	$html = '';
	if ( $copyToClipboardScriptFirst ) {
		global $wgScriptPath;
		$scriptHtml = "<script src='" . $wgScriptPath . "/extensions/PreToClip/co2cliScript.js'></script>";
		$parser->mOutput->addHeadItem( $scriptHtml, 'co2cli-script' );
		$copyToClipboardScriptFirst = false;
	}

	$escapedInput = htmlspecialchars( $input, ENT_QUOTES, 'UTF-8' );
	$base64Input = base64_encode( $input );

	if ( isset( $args['show'] ) && $args['show'] == true ) {
		$html .= $escapedInput . ' ';
	}

	$html .= '<button type="button" class="copy-to-clipboard-btn" data-clipboard-b64="' . $base64Input . '" onclick="copyToClipboard(decodeBase64(this.getAttribute(\'data-clipboard-b64\')))">Copy</button>';

	return $html;
}
