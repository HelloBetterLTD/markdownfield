<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 10/24/17
 * Time: 10:27 AM
 * To change this template use File | Settings | File Templates.
 */

namespace SilverStripers\markdown\forms;

use SilverStripe\Core\Config\Config_ForClass;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Convert;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Manifest\ModuleResourceLoader;
use SilverStripe\View\SSViewer;
use SilverStripe\View\ThemeResourceLoader;

class MarkdownEditorConfig
{

    use Configurable;
    use Injectable;


    protected static $configs = [];
    protected static $current;
    private static $editor_css = [];
    private static $default_config = 'default';
	private $identifier;

    protected $toolbar = [
        [
            'name'      => 'heading',
            'className' => 'fa fa-header',
            'title'     => 'Heading HTML',
            'action'    => 'toggleHeadingSmaller'
        ],
        [
            'name'      => 'bold',
            'className' => 'fa fa-bold',
            'title'     => 'Bold',
            'action'    => 'toggleBold'
        ],
        [
            'name'      => 'italic',
            'className' => 'fa fa-italic',
            'title'     => 'Italic',
            'action'    => 'toggleItalic'
        ],
        [
            'name'      => 'strikethrough',
            'className' => 'fa fa-strikethrough',
            'title'     => 'Strike Through',
            'action'    => 'toggleStrikethrough'
        ],
        '|',
        [
            'name'      => 'quote',
            'className' => 'fa fa-quote-left',
            'title'     => 'Quote',
            'action'    => 'toggleBlockquote'
        ],
        [
            'name'      => 'unordered-list',
            'action'    => 'toggleUnorderedList',
            'className' => 'fa fa-list-ul',
            'title'     => 'Generic List'
        ],
        [
            'name'      => 'ordered-list',
            'action'    => 'toggleOrderedList',
            'className' => 'fa fa-list-ol',
            'title'     => 'Ordered List'
        ],
        [
            'name'      => 'link',
            'action'    => 'drawLink',
            'className' => 'fa fa-link',
            'title'     => 'Create Link'
        ],
        [
            'name'      => 'embed',
            'action'    => 'ssEmbed',
            'className' => 'fa fa-play',
            'title'     => 'Embed Media'
        ],
        [
            'name'      => 'image',
            'action'    => 'ssImage',
            'className' => 'fa fa-picture-o',
            'title'     => 'Insert Image'
        ],
        '|',
        [
            'name'      => 'preview',
            'action'    => 'togglePreview',
            'className' => 'fa fa-eye no-disable',
            'title'     => 'Toggle Preview'
        ],
        [
            'name'      => 'side-by-side',
            'action'    => 'toggleSideBySide',
            'className' => 'fa fa-columns no-disable no-mobile',
            'title'     => 'Toggle Side by Side'
        ],
        [
            'name'      => 'fullscreen',
            'action'    => 'toggleFullScreen',
            'className' => 'fa fa-arrows-alt no-disable no-mobile',
            'title'     => 'Toggle Fullscreen'
        ],
        '|',
        [
            'name'      => 'guide',
            'action'    => 'https://simplemde.com/markdown-guide',
            'className' => 'fa fa-question-circle',
            'title'     => 'Help'
        ]
    ];

    /**
     * @param null $identifier
     * @return mixed
     */
    public static function get($identifier = null)
    {
        if (!$identifier) {
            return static::get_active();
        }
        // Create new instance if unconfigured
        if (!isset(self::$configs[$identifier])) {
            self::$configs[$identifier] = static::create()->setIdentifier($identifier);
        }

        return self::$configs[$identifier];
    }

    /**
     * @return mixed
     */
    public static function get_active_identifier()
    {
        return static::config()->get('current') ?: static::config()->get('default_config');
    }

	/**
	 * @param $identifier
	 * @return $this
	 */
	public function setIdentifier($identifier)
	{
		$this->identifier = $identifier;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getIdentifier()
	{
		return $this->identifier;
	}

    /**
     * @return mixed
     */
    public static function get_active()
    {
        return self::get(static::get_active_identifier());
    }

    /**
     * @param MarkdownEditorConfig $config
     * @return Config_ForClass
     */
    public static function set_active(MarkdownEditorConfig $config)
    {
        return static::config()->update('current', $config);
    }

    /**
     * @param $identifier
     * @param MarkdownEditorConfig|null $config
     * @return MarkdownEditorConfig
     */
    public static function set_config($identifier, MarkdownEditorConfig $config = null)
    {
        if ($config) {
            static::$configs[$identifier] = $config;
        } else {
            unset(static::$configs[$identifier]);
        }

        return $config;
    }


    /**
     * @return array
     */
    public function getEditorCSS()
    {
        $editor = array();

        // Add standard editor.css
        $editorCSSFiles = $this->config()->get('editor_css');
        if ($editorCSSFiles) {
            foreach ($editorCSSFiles as $editorCSS) {
                $path = ModuleResourceLoader::singleton()
                    ->resolveURL($editorCSS);
                $editor[$path] = $path;
            }
        }

        // Themed editor.css
        $themes = SSViewer::get_themes();
        $themedEditor = ThemeResourceLoader::inst()->findThemedCSS('editor', $themes);
        if ($themedEditor) {
            $editor[$themedEditor] = $themedEditor;
        }

        return $editor;
    }


    /**
     * @return array
     */
    public function getConfig()
    {
        return [
            'toolbar'       => $this->toolbar,
            'editor_css'    => $this->getEditorCSS(),
			'identifier'	=> $this->getIdentifier()
        ];
    }

	/**
	 * @return MarkdownEditorConfig
	 */
	public function addSeparator()
	{
		array_push($this->toolbar, '|');
		return $this;
	}

	/**
	 * @param $button
	 * @return MarkdownEditorConfig
	 */
	public function addButton($button)
	{
		array_push($this->toolbar, $button);
		return $this;
	}

	/**
     * @return array
     */
    public function getAttributes()
    {
        return [
            'data-editor' => 'markDown',
            'data-config' => Convert::array2json($this->getConfig()),
        ];
    }
}

