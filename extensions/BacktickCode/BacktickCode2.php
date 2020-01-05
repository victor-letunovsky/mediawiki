<?php
/**
 * @author Joel Thornton <mediawiki@joelpt.net>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

if(!defined('MEDIAWIKI')) {
    die("This is an extension to the MediaWiki package and cannot be run standalone.");
}

// Register as an extention
$wgExtensionCredits['parserhook'][] = array(
        'name' => 'BacktickCode',
        'version' => '1.0',
        'url' => 'https://www.mediawiki.org/wiki/Extension:BacktickCode',
        'author' => 'Joel Thornton',
        'description' => 'Allows to show text as <code> between backticks (`)',
);

// Register hooks
$wgHooks['InternalParseBeforeLinks'][] = function( &$parser, &$text, &$stripState ) {
        // We replace '`...`' by '<code>...</code>' and '\`' by '`'.
        $text = preg_replace('/([^\\\\]|^)`([^`]*)`/', '$1<code>$2</code>', $text);
        $text = preg_replace('/\\\\\`/', '`', $text);

        return true;
};
