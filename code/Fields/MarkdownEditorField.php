<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 4/18/15
 * Time: 11:12 AM
 * To change this template use File | Settings | File Templates.
 */

class MarkdownEditorField extends TextareaField {

	protected $rows = 30;

	function Field($properties = array()){
		Requirements::css(MARKDOWN_BASE . '/css/MarkdownEditorField.css');
		return parent::Field($properties);
	}

} 