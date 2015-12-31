<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 4/18/15
 * Time: 11:11 AM
 * To change this template use File | Settings | File Templates.
 */

class MarkdownField extends Text
{

    private static $escape_type = 'xml';

    private static $casting = array(
        "AbsoluteLinks" => "HTMLText",
        "BigSummary" => "HTMLText",
        "ContextSummary" => "HTMLText",
        "FirstParagraph" => "HTMLText",
        "FirstSentence" => "HTMLText",
        "LimitCharacters" => "HTMLText",
        "LimitSentences" => "HTMLText",
        "Lower" => "HTMLText",
        "LowerCase" => "HTMLText",
        "Summary" => "HTMLText",
        "Upper" => "HTMLText",
        "UpperCase" => "HTMLText",
        'EscapeXML' => 'HTMLText',
        'LimitWordCount' => 'HTMLText',
        'LimitWordCountXML' => 'HTMLText',
        'NoHTML' => 'Text',
    );

    private $parsedContent;


    /**
     * @return string
     * parse contents of the markdown field to tempates
     */
    public function ParseMarkdown($bCache = true, $strValue = '')
    {
        if ($bCache && $this->parsedContent) {
            return $this->parsedContent;
        }

        $shortCodeParser = ShortcodeParser::get_active();
        $strParsed = $shortCodeParser->parse(!empty($strValue) ? $strValue : $this->value);

        $parseDown = new Parsedown();
        $strParsed  = $parseDown->text($strParsed);

        if ($bCache) {
            $this->parsedContent = $strParsed;
        }

        return $strParsed;
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
     * @param null $title
     * @param null $params
     * @return FormField|TextField
     */
    public function scaffoldSearchField($title = null, $params = null)
    {
        return new TextField($this->name, $title);
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
