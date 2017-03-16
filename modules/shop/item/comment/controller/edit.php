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
class Shop_Item_Comment_Controller_Edit extends Comment_Controller_Edit{
	/**
	 * Processing of the form. Apply object fields.
	 */	protected function _applyObjectProperty()	{		parent::_applyObjectProperty();		$Comment_Shop_Item = $this->_object->Comment_Shop_Item;		if (is_null($Comment_Shop_Item->id))		{			$Comment_Shop_Item->shop_item_id = intval(Core_Array::getRequest('shop_item_id'));			$Comment_Shop_Item->save();		}	}}