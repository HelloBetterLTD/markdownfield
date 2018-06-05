# MarkdownField

[![CircleCI](https://circleci.com/gh/SilverStripers/markdownfield/tree/master.svg?style=svg)](https://circleci.com/gh/SilverStripers/markdownfield/tree/master)
[![codecov](https://codecov.io/gh/SilverStripers/markdownfield/branch/master/graph/badge.svg)](https://codecov.io/gh/SilverStripers/markdownfield)


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

## Add preview styles

You can add your own CSS styles to the editor previews. This would let the users to check how their content will be displayed before they save in.

To achived this create a css file in `mysite/css/` and name it as `editor.css`.

Your CSS rules have to be nested in a class so it wont affect other areas of the CMS.

```
.markdown-preview {
    background-color: white;
    padding: 20px;
    font-size: 20px;
}

.markdown-preview h1 {
    font-size: 30px;
}
````

If you are using a separate config and wanting to add styles to that EditorConfig you just add a new class name. This is possible because the fields adds
the EditorConfig's identifier on to the preview pane. The below is an example for the default configs.

```
.markdown-preview.default {
    background-color: white;
    padding: 20px;
    font-size: 14px;
    line-height: 20px;
}

.markdown-preview.default h1 {
    font-size: 24px;
    line-height: 30px;
}
````
