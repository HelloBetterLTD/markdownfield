<?php
/**
 * Created by PhpStorm.
 * User: Nivanka Fonseka
 * Date: 24/09/2017
 * Time: 00:02
 */

namespace SilverStripers\markdown\extensions;


use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripers\markdown\db\MarkdownText;
use SilverStripers\markdown\forms\MarkdownEditorField;

class MarkdownSiteTreeExtension extends DataExtension
{

    public function updateCMSFields(FieldList $fields)
    {
        if(Config::inst()->get(MarkdownText::class, 'markdown_as_base')) {
            $fields->replaceField('Content', MarkdownEditorField::create('Content'));
        }
    }

}