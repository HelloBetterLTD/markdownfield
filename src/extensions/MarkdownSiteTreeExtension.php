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
use SilverStripe\Core\Injector\Injector;
use SilverStripers\markdown\db\MarkdownText;
use SilverStripers\markdown\forms\MarkdownEditorField;

class MarkdownSiteTreeExtension extends DataExtension
{
    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        $injectorConfig = Config::inst()->get(Injector::class, 'HTMLText');
        if ($injectorConfig && isset($injectorConfig['class']) && $injectorConfig['class'] == MarkdownText::class) {
            $fields->replaceField('Content', MarkdownEditorField::create('Content'));
        }
    }
}
