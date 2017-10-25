<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 4/18/15
 * Time: 11:21 AM
 * To change this template use File | Settings | File Templates.
 */

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Config\Config;
use SilverStripers\markdown\db\MarkdownText;
use SilverStripers\markdown\shortcodes\MarkdownImageShortcodeProvider;
use SilverStripe\Assets\Shortcodes\FileShortcodeProvider;
use SilverStripe\Assets\Shortcodes\ImageShortcodeProvider;
use SilverStripe\View\Parsers\ShortcodeParser;

$asBase = Config::inst()->get(MarkdownText::class, 'markdown_as_base');
if ($asBase) {
    $siteTreeDB = Config::inst()->get(SiteTree::class, 'db');
    $siteTreeDB['Content'] = MarkdownText::class;
    Config::modify()
        ->set(SiteTree::class, 'db', $siteTreeDB);
}


ShortcodeParser::get('default')
    ->register('image', [ImageShortcodeProvider::class, 'handle_shortcode'])
    ->register('image_link', [MarkdownImageShortcodeProvider::class, 'handle_shortcode'])
    ->register('file_link', [FileShortcodeProvider::class, 'handle_shortcode']);
