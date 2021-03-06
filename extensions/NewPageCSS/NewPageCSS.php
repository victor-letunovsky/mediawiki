<?php
/**
 * NewPageCSS extension - Provides a parser hook to add per-page CSS to pages with the <css> tag
 * @version 1.1.0 - 2012/02/16
 *
 * @link https://www.mediawiki.org/wiki/Extension:NewPageCSS Documentation
 *
 * @file NewPageCSS.php
 * @ingroup Extensions
 * @package MediaWiki
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author Julian Porter <julian.porter@porternet.org>
 * @copyright © 2005 Ævar Arnfjörð Bjarmason
 * @copyright © 2010 Julian Porter
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

if (!defined('MEDIAWIKI')) die();

$wgExtensionCredits['parserhook'][] = array(
	'path' => __FILE__,
	'name' => 'New Page CSS',
        'version' => '1.1.0',
	'url' => 'https://www.mediawiki.org/wiki/Extension:NewPageCSS',
	'description' => 'Adds a parser hook to add per-page CSS using the <tt>&lt;css&gt;</tt> tag',
	'author' => array( 'Julian Porter', '&ampAElig;var Arnfj&ampouml;r&ampeth; Bjarmason' ),
);

$wgHooks['ParserFirstCallInit'][] = 'CSS_setup';

function CSS_setup(&$parser)
{
  $parser->setHook("css","CSS_include");
  return true;
}

function CSS_include($content)
{
  global $wgParser;
  $css = htmlspecialchars( trim( Sanitizer::checkCss( $content ) ) );
  $wgParser->mOutput->addHeadItem( <<<EOT
<style type="text/css">
/*<![CDATA[*/      
{$css}
/*]]>*/       
</style>
EOT
  );
  return '';
}
