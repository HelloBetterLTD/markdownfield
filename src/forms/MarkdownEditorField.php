<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 4/18/15
 * Time: 11:12 AM
 * To change this template use File | Settings | File Templates.
 */
namespace SilverStripers\markdown\forms;

use SilverStripe\Assets\Shortcodes\ImageShortcodeProvider;
use SilverStripe\Forms\TextareaField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DataObjectInterface;
use Exception;
use SilverStripe\View\Parsers\HTMLValue;
use SilverStripe\Forms\HTMLEditor\HTMLEditorSanitiser;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorConfig;

class MarkdownEditorField extends TextareaField
{

    private static $allowed_actions = array(
        'preview'
    );

    protected $rows = 30;

    public function Field($properties = array())
    {
        return parent::Field($properties);
    }

    public function saveInto(DataObjectInterface $record)
    {
        if ($record->hasField($this->name) && $record->escapeTypeForField($this->name) != 'xml') {
            throw new Exception(
                'HTMLEditorField->saveInto(): This field should save into a HTMLText or HTMLVarchar field.'
            );
        }

        // Sanitise if requested
        $htmlValue = HTMLValue::create($this->Value());
        if (HTMLEditorField::config()->sanitise_server_side) {
            $santiser = HTMLEditorSanitiser::create(HTMLEditorConfig::get_active());
            $santiser->sanitise($htmlValue);
        }

        // optionally manipulate the HTML after a TinyMCE edit and prior to a save
        $this->extend('processHTML', $htmlValue);

        // Store into record
        $record->{$this->name} = $htmlValue->getContent();
    }



}
