<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 10/24/17
 * Time: 10:27 AM
 * To change this template use File | Settings | File Templates.
 */

namespace SilverStripers\markdown\forms;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Convert;
use SilverStripe\Core\Injector\Injectable;

class MarkdownEditorConfig
{

	use Configurable;
	use Injectable;


	protected static $configs = array();
	protected static $current = null;
	private static $default_config = 'default';

	protected $settings = array(
		array(
			'name'		=> 'heading',
			'className'	=> 'fa fa-header',
			'title'		=> 'Heading HTML',
			'action'	=> 'toggleHeadingSmaller'
		),
		array(
			'name'		=> 'bold',
			'className'	=> 'fa fa-bold',
			'title'		=> 'Bold',
			'action'	=> 'toggleBold'
		),
		array(
			'name'		=> 'italic',
			'className'	=> 'fa fa-italic',
			'title'		=> 'Italic',
			'action'	=> 'toggleItalic'
		),
		array(
			'name'		=> 'strikethrough',
			'className'	=> 'fa fa-strikethrough',
			'title'		=> 'Strike Through',
			'action'	=> 'toggleStrikethrough'
		),
		"|",
		array(
			'name'		=> 'quote',
			'className'	=> 'fa fa-quote-left',
			'title'		=> 'Quote',
			'action'	=> 'toggleBlockquote'
		),
		array(
			'name'		=> 'unordered-list',
			'action'	=> 'toggleUnorderedList',
			'className'	=> 'fa fa-list-ul',
            'title'		=> 'Generic List'
		),
		array(
			'name'		=> 'ordered-list',
			'action'	=> 'toggleOrderedList',
			'className'	=> 'fa fa-list-ol',
            'title'		=> 'Ordered List'
		),
		array(
			'name'		=> 'link',
			'action'	=> 'drawLink',
			'className'	=> 'fa fa-link',
            'title'		=> 'Create Link'
		),
		array(
			'name'		=> 'embed',
			'action'	=> 'ssEmbed',
			'className'	=> 'fa fa-play',
            'title'		=> 'Embed Media'
		),
		array(
			'name'		=> 'image',
			'action'	=> 'ssImage',
			'className'	=> 'fa fa-picture-o',
            'title'		=> 'Insert Image'
		),
		'|',
		array(
			'name'		=> 'preview',
			'action'	=> 'togglePreview',
			'className'	=> 'fa fa-eye no-disable',
			'title'		=> 'Toggle Preview'
		),
		array(
			'name'		=> 'side-by-side',
			'action'	=> 'toggleSideBySide',
			'className'	=> 'fa fa-columns no-disable no-mobile',
			'title'		=> 'Toggle Side by Side'
		),
		array(
			'name'		=> 'fullscreen',
			'action'	=> 'toggleFullScreen',
			'className'	=> 'fa fa-arrows-alt no-disable no-mobile',
			'title'		=> 'Toggle Fullscreen'
		),
		'|',
		array(
			'name'		=> 'guide',
			'action'	=> 'https://simplemde.com/markdown-guide',
			'className'	=> 'fa fa-question-circle',
			'title'		=> 'Help'
		)
	);



	public static function get($identifier = null)
	{
		if (!$identifier) {
			return static::get_active();
		}
		// Create new instance if unconfigured
		if (!isset(self::$configs[$identifier])) {
			self::$configs[$identifier] = static::create();
		}
		return self::$configs[$identifier];
	}


	public static function get_active_identifier()
	{
		$identifier = self::$current ?: static::config()->get('default_config');
		return $identifier;
	}

	public static function get_active()
	{
		$identifier = self::get_active_identifier();
		return self::get($identifier);
	}

	public static function set_active(MarkdownEditorConfigs $config)
	{
		$identifier = static::get_active_identifier();
		return static::set_config($identifier, $config);
	}

	public static function set_config($identifier, MarkdownEditorConfigs $config = null)
	{
		if ($config) {
			self::$configs[$identifier] = $config;
		} else {
			unset(self::$configs[$identifier]);
		}
		return $config;
	}

	public function getConfig()
	{
		return $this->settings;
	}

	public function getAttributes()
	{
		return [
			'data-editor' => 'markDown',
			'data-config' => Convert::array2json($this->getConfig()),
		];
	}


}