<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Information systems.
 *
 * @package HostCMS 6\Informationsystem
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2013 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Informationsystem_Item_Comment_Controller_Edit extends Comment_Controller_Edit
	/**
	 * Processing of the form. Apply object fields.
	 */
				? Core_Entity::factory('Comment', $this->_object->parent_id)->Comment_Informationsystem_Item->informationsystem_item_id
				: Core_Array::getGet('informationsystem_item_id'));