<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/23/17
 * Time: 6:30 AM
 * To change this template use File | Settings | File Templates.
 */

namespace SilverStripers\markdown\extensions;

use SilverStripe\Core\Extension;
use SilverStripe\View\Requirements;

class MarkdownFieldExtension extends Extension
{
    public function init()
    {
        Requirements::javascript('silverstripers/markdown:client/dist/bundle.min.js');
        Requirements::css('silverstripers/markdown:client/dist/bundle.min.css');
    }
}
