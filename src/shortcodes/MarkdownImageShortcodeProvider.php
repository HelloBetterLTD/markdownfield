<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 10/17/17
 * Time: 3:12 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SilverStripers\markdown\shortcodes;

use SilverStripe\Assets\Image;
use SilverStripe\Core\Convert;
use SilverStripe\View\Parsers\ShortcodeHandler;
use SilverStripe\View\Parsers\ShortcodeParser;
use SilverStripe\Assets\Shortcodes\FileShortcodeProvider;

class MarkdownImageShortcodeProvider extends FileShortcodeProvider implements ShortcodeHandler
{
    public static function get_shortcodes()
    {
        return ['image_link'];
    }

    public static function handle_shortcode($args, $content, $parser, $shortcode, $extra = [])
    {
        // Find appropriate record, with fallback for error handlers
        $record = static::find_shortcode_record($args, $errorCode);
        if ($errorCode) {
            $record = static::find_error_record($errorCode);
        }
        if (!$record) {
            return null;
        }

        $src = $record->Link();
        if ($record instanceof Image) {
            $width = isset($args['width']) ? $args['width'] : null;
            $height = isset($args['height']) ? $args['height'] : null;
            $hasCustomDimensions = ($width && $height);
            if ($hasCustomDimensions && (($width != $record->getWidth()) || ($height != $record->getHeight()))) {
                $resized = $record->ResizedImage($width, $height);
                // Make sure that the resized image actually returns an image
                if ($resized) {
                    $src = $resized->getURL();
                }
            }
        }
        return $src;
    }

    public static function regenerate_shortcode($args, $content, $parser, $shortcode, $extra = [])
    {
        // Check if there is a suitable record
        $record = static::find_shortcode_record($args);
        if ($record) {
            $args['src'] = $record->getURL();
        }

        // Rebuild shortcode
        $parts = [];
        foreach ($args as $name => $value) {
            $htmlValue = Convert::raw2att($value ?: $name);
            $parts[] = sprintf('%s="%s"', $name, $htmlValue);
        }
        return sprintf("[%s %s]", $shortcode, implode(' ', $parts));
    }

    /**
     * Helper method to regenerate all shortcode links.
     *
     * @param string $value HTML value
     * @return string value with links resampled
     */
    public static function regenerate_html_links($value)
    {
        $regenerator = ShortcodeParser::get('regenerator');
        return $regenerator->parse($value);
    }
}
