<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 4/18/15
 * Time: 11:21 AM
 * To change this template use File | Settings | File Templates.
 */


$asBase = \SilverStripe\Core\Config\Config::inst()->get(\SilverStripers\markdown\db\MarkdownText::class, 'markdown_as_base');
if($asBase) {
	$siteTreeDB = \SilverStripe\Core\Config\Config::inst()->get(\SilverStripe\CMS\Model\SiteTree::class, 'db');
	$siteTreeDB['Content'] = \SilverStripers\markdown\db\MarkdownText::class;
	\SilverStripe\Core\Config\Config::modify()
		->set(\SilverStripe\CMS\Model\SiteTree::class, 'db', $siteTreeDB);
}