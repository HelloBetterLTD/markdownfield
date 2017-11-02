# MarkdownField

This module introduces a new DB field type Markdown & Markdown Editor. It uses github style Markdown style. And uses the simple markdown
editor.

https://github.com/sparksuite/simplemde-markdown-editor

https://github.com/cebe/markdown

The module is still under development, but soon will be ready, with link popups and image selectors.

## Installation

Use composer

```
composer require silverstripers/markdown dev-master
```

## Basic Usage

To use the markdown DB field in your data objects the basic code would look like

```

class MyDataClass extends DataObject
{

    private static $db = [
		'MarkdownContent'		=> 'MarkdownText'
	];

}

```

MarkdownText knows to add a markdown editor for your fields, but if you need to manually specify the type of field use

```
public function getCMSFields()
{
    $fields = parent::getCMSFields();

    $fields->addFieldsToTab('Root.Sidebar', [
        \SilverStripers\markdown\forms\MarkdownEditorField::create('MarkdownContent', 'Content'),
    ]);

    return $fields;
}
```

## Force all the fields to use Markdown

If you are looking to replace all the fields of HTMLText to markdown use the following configs in the config.yml.

This should override any instances of the HTMLText replacements with MarkdownText

```
---
Name: myconfigs
After:
  - '#corefieldtypes'
---
SilverStripe\Core\Injector\Injector:
  HTMLText:
    class: SilverStripers\markdown\db\MarkdownText
```

