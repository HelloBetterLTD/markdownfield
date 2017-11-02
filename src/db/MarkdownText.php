<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 4/18/15
 * Time: 11:11 AM
 * To change this template use File | Settings | File Templates.
 */

namespace SilverStripers\markdown\db;

use cebe\markdown\GithubMarkdown;
use SilverStripe\Forms\FormField;
use SilverStripe\Forms\NullableField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\FieldType\DBText;
use SilverStripe\View\Parsers\ShortcodeParser;
use SilverStripers\markdown\forms\MarkdownEditorField;

class MarkdownText extends DBText
{
    private static $escape_type = 'xml';

    private static $casting = [
        'AbsoluteLinks'     => 'HTMLText',
        'BigSummary'        => 'HTMLText',
        'ContextSummary'    => 'HTMLText',
        'FirstParagraph'    => 'HTMLText',
        'FirstSentence'     => 'HTMLText',
        'LimitCharacters'   => 'HTMLText',
        'LimitSentences'    => 'HTMLText',
        'Lower'             => 'HTMLText',
        'LowerCase'         => 'HTMLText',
        'Summary'           => 'HTMLText',
        'Upper'             => 'HTMLText',
        'UpperCase'         => 'HTMLText',
        'EscapeXML'         => 'HTMLText',
        'LimitWordCount'    => 'HTMLText',
        'LimitWordCountXML' => 'HTMLText',
        'NoHTML'            => 'Text',
    ];

    private $parsedContent;
    private $shortcodes = [];


    /**
     * @param bool $bCache
     * @param string $strValue
     * @return string parse contents of the markdown field to tempates
     * parse contents of the markdown field to tempates
     */
    public function ParseMarkdown($bCache = true, $strValue = '')
    {
        if ($bCache && $this->parsedContent) {
            return $this->parsedContent;
        }

        $parsed = !empty($strValue) ? $strValue : $this->value;

        $this->extend('onBeforeParseDown', $parsed);

        $this->shortcodes = [];

        // shortcodes
        $regexes = [
            '/\[image_link(.+?)\]/',
            '/\[file_link(.+?)\]/'
        ];

        foreach ($regexes as $pattern) {
            preg_match_all($pattern, $parsed, $matches);
            if (!empty($matches[0])) {
                foreach ($matches[0] as $attachment) {
                    $this->shortcodes[md5($attachment)] = $attachment;
                    $parsed = str_replace($attachment, md5($attachment), $parsed);
                }
            }
        }



        $parseDown = new GithubMarkdown();
        $parsed = $parseDown->parse($parsed);

        foreach ($this->shortcodes as $key => $shortcode) {
            $parsed = str_replace($key, $shortcode, $parsed);
        }


        $shortCodeParser = ShortcodeParser::get_active();
        $parsed = $shortCodeParser->parse($parsed);


        $this->extend('onAfterParseDown', $parsed);

        $this->parsedContent = $parsed;
        return $parsed;
    }


    /**
     * @return string
     */
    public function forTemplate()
    {
        return $this->ParseMarkdown();
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * @param null $title
     * @param null $params
     * @return FormField|MarkdownEditorField|NullableField|TextareaField
     */
    public function scaffoldFormField($title = null, $params = null)
    {
        return new MarkdownEditorField($this->name, $title);
    }

    /**
     * @return string
     */
    public function NoHTML()
    {
        return strip_tags($this->ParseMarkdown());
    }

    /**
     * @return string
     */
    public function Upper()
    {
        $strValue = strtoupper($this->__toString());

        return $this->ParseMarkdown(false, $strValue);
    }

    /**
     * @return string
     */
    public function UpperCase()
    {
        return $this->Upper();
    }


    /**
     * @return string
     */
    public function Lower()
    {
        $strValue = strtolower($this->__toString());

        return $this->ParseMarkdown(false, $strValue);
    }

    /**
     * @return string
     */
    public function LowerCase()
    {
        return $this->Lower();
    }
}
