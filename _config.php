<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 4/18/15
 * Time: 11:21 AM
 * To change this template use File | Settings | File Templates.
 */

use SilverStripers\markdown\shortcodes\MarkdownImageShortcodeProvider;
use SilverStripe\Assets\Shortcodes\FileShortcodeProvider;
use SilverStripe\Assets\Shortcodes\ImageShortcodeProvider;
use SilverStripe\View\Parsers\ShortcodeParser;

$asBase = \SilverStripe\Core\Config\Config::inst()->get(\SilverStripers\markdown\db\MarkdownText::class, 'markdown_as_base');
if($asBase) {
	$siteTreeDB = \SilverStripe\Core\Config\Config::inst()->get(\SilverStripe\CMS\Model\SiteTree::class, 'db');
	$siteTreeDB['Content'] = \SilverStripers\markdown\db\MarkdownText::class;
	\SilverStripe\Core\Config\Config::modify()
		->set(\SilverStripe\CMS\Model\SiteTree::class, 'db', $siteTreeDB);
}


ShortcodeParser::get('default')
	->register('image', [ImageShortcodeProvider::class, 'handle_shortcode'])
	->register('image_link', [MarkdownImageShortcodeProvider::class, 'handle_shortcode'])
	->register('file_link', [FileShortcodeProvider::class, 'handle_shortcode']);

