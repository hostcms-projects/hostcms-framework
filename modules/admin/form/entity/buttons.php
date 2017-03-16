<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Admin forms.
 *
 * @package HostCMS 6\Admin
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2012 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Admin_Form_Entity_Buttons extends Admin_Form_Entity
{
	/**
	 * Executes the business logic.
	 */
	public function execute()
	{
		?><div id="ControlElements"><?php
		parent::execute();
		?></div><?php
	}
}