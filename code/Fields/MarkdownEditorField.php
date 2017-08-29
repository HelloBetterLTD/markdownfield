<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 4/18/15
 * Time: 11:12 AM
 * To change this template use File | Settings | File Templates.
 */

class MarkdownEditorField extends TextareaField
{

    private static $allowed_actions = array(
        'preview'
    );

    protected $rows = 30;

    public function Field($properties = array())
    {
        Requirements::css(MARKDOWN_BASE . '/css/MarkdownEditorField.css');

        Requirements::javascript(MARKDOWN_BASE . '/thirdparty/ace/src-min-noconflict/ace.js');
        Requirements::javascript(MARKDOWN_BASE . '/js/MarkdownEditorField.js');

        return parent::Field($properties);
    }


    public function preview(SS_HTTPRequest $request)
    {
        $strValue = $request->requestVar('markdown');
        if ($strValue) {
            $shortCodeParser = ShortcodeParser::get_active();
            $strValue = $shortCodeParser->parse($strValue);

            $parseDown = new Parsedown();
            $strValue  = $parseDown->text($strValue);
        }
        return $strValue;
    }
}
