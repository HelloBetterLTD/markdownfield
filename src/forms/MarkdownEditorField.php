<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 4/18/15
 * Time: 11:12 AM
 * To change this template use File | Settings | File Templates.
 */
namespace SilverStripers\markdown\forms;

use SilverStripe\Forms\TextareaField;


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



}
