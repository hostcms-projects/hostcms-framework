<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Winter skin.
 *
 * @package HostCMS 6\Skin
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2012 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Skin_Winter extends Skin_Default
{
	/**
	 * Constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->addCss('/modules/skin/winter/css/style.css');
	}
}