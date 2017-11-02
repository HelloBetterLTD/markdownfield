<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 10/24/17
 * Time: 10:27 AM
 * To change this template use File | Settings | File Templates.
 */

namespace SilverStripers\markdown\forms;

use LogicException;
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


    /**
     * Set of Editor configs
     *
     * @var array|MarkdownEditorConfig[]
     */
    protected static $configs = [];

    /**
     * Current setting, if overridden, otherwise, default will be used
     * @var string
     */
    protected static $current;

    /**
     * List of css to embed with the editors
     * @var array
     */
    private static $editor_css = [];

    /**
     * Default identifier for config and settings
     * @var string
     */
    private static $default_config = 'default';

    /**
     * Identifier for the current config
     * @var string
     */
	private $identifier;
    /**
     * Settings for the MarkdownField
     * @var array
     */
    protected static $settings;

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
     * @return string
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
     * @return MarkdownEditorConfig
     */
    public static function get_active()
    {
        $identifier = static::get_active_identifier();

        return self::get($identifier);
    }

    /**
     * @param string $config
     * @return Config_ForClass
     * @throws LogicException
     */
    public static function set_active($config)
    {
        if (!is_string($config)) {
            throw new LogicException('String expected for config name');
        }

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
    public function getSettings()
    {
        $settings = static::config()->get('settings');
        $config = static::get_active_identifier();
        $toolbar = null;
        if (isset($settings[$config])) {
            $toolbar = $settings[$config];
        }
        else {
            // Config not found, return default
            $default = static::config()->get('default_config');
            $toolbar = $settings[$default];
        }

        return [
            'toolbar'       => $toolbar,
            'editor_css'    => $this->getEditorCSS(),
            'identifier'	=> $this->getIdentifier()
        ];
    }

    /**
     * @return MarkdownEditorConfig
     */
    public function addSeparator()
    {
        $settings = static::config()->get('settings');
        $active = static::get_active_identifier();
        if (is_array($settings[$active])) {
            $settings[$active][] = '|';
        } else {
            $default = static::config()->get('default_config');
            $settings[$default][] = '|';
        }
        static::config()->update('settings', $settings);
        return $this;
    }
    /**
     * @param $button
     * @return MarkdownEditorConfig
     */
    public function addButton($button)
    {
        $settings = static::config()->get('settings');
        $active = static::get_active_identifier();
        if (is_array($settings[$active])) {
            $settings[$active][] = $button;
        } else {
            $default = static::config()->get('default_config');
            $settings[$default][] = $button;
        }
        static::config()->update('settings', $settings);
        return $this;
    }


    /**
     * @return array
     */
    public function getAttributes()
    {
        return [
            'data-editor' => 'markDown',
            'data-config' => Convert::array2json($this->getSettings()),
        ];
    }
}

