<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Online shop.
 *
 * @package HostCMS 6\Shop
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2013 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Shop_Item_Comment_Controller_Edit extends Comment_Controller_Edit
	/**
	 * Processing of the form. Apply object fields.
	 */
			$Comment_Shop_Item->shop_item_id = intval($this->_object->parent_id
				? Core_Entity::factory('Comment', $this->_object->parent_id)->Comment_Shop_Item->shop_item_id
				: Core_Array::getRequest('shop_item_id'));