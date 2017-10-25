<?php

namespace SilverStripers\markdown\tests;

use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\Debug;
use SilverStripe\Dev\SapphireTest;
use SilverStripers\markdown\db\MarkdownText;
use SilverStripers\markdown\forms\MarkdownEditorField;

class MarkdownTextTest extends SapphireTest
{

    protected $usesDatabase = false;
    /**
     * @var MarkdownText
     */
    protected $markdown;

    protected function setUp()
    {
        parent::setUp();
        $this->markdown = Injector::inst()->get(MarkdownText::class);
    }

    public function testParseMarkdown()
    {
        $content = "# Hello world\nThis is a test";
        $result = $this->markdown->ParseMarkdown(true, $content);
        $this->assertContains('<h1>Hello world</h1>', $result);
    }

    public function testForTemplate()
    {
        $content = "# Hello world\nThis is a test";
        $this->markdown->setValue($content);
        $result = $this->markdown->forTemplate();
        $this->assertContains('<h1>Hello world</h1>', $result);
    }

    public function testToString()
    {
        $content = "# Hello world\nThis is a test";
        $this->markdown->setValue($content);
        $this->assertEquals("# Hello world\nThis is a test", $this->markdown->__toString());
    }

    public function testScaffoldFormField()
    {
        $this->markdown->setName('Content');
        $result = $this->markdown->scaffoldFormField();
        $this->assertInstanceOf(MarkdownEditorField::class, $result);
    }

    public function testNoHTML()
    {
        $content = "# Hello world\nThis is a test";
        $this->markdown->setValue($content);
        $result = $this->markdown->NoHTML();
        $this->assertNotContains('<h1>', $result);
        $this->assertContains('Hello world', $result);
    }

    public function testUpper()
    {
        $content = "# Hello world\nThis is a test";
        $this->markdown->setValue($content);
        $result = $this->markdown->Upper();
        $this->assertNotContains('Hello world', $result);
        $this->assertContains('HELLO WORLD', $result);
    }

    public function testLower()
    {
        $content = "# Hello world\nThis is a test";
        $this->markdown->setValue($content);
        $result = $this->markdown->Lower();
        $this->assertNotContains('Hello world', $result);
        $this->assertContains('hello world', $result);

    }
}