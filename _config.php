<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 4/18/15
 * Time: 11:21 AM
 * To change this template use File | Settings | File Templates.
 */

$strMarkDownPath = dirname(__FILE__);

if(!defined('MARKDOWN_BASE')){
	$strBase = substr(str_replace(BASE_PATH, '', $strMarkDownPath), 1);
	define('MARKDOWN_BASE', $strBase);
}