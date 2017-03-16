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
class Shop_Warehouse_Controller_Edit extends Admin_Form_Action_Controller_Type_Edit
{
	/**
	 * Set object
	 * @param object $object object
	 * @return self
	 */
	public function setObject($object)
	{
		if (is_null($object->id))
		{
			$object->shop_id = Core_Array::getGet('shop_id');

			if ($object->Shop->Shop_Warehouses->getCount() == 0)
			{
				$object->default = 1;
			}
		}

		parent::setObject($object);

		$oMainTab = $this->getTab('main');

		$oAdditionalTab = $this->getTab('additional');

		// Удаляем типы доставок
		$oAdditionalTab
			->delete($this->getField('shop_country_id'))
			->delete($this->getField('shop_country_location_id'))
			->delete($this->getField('shop_country_location_city_id'))
			->delete($this->getField('shop_country_location_city_area_id'));

		$Shop_Delivery_Condition_Controller_Edit = new Shop_Delivery_Condition_Controller_Edit($this->_Admin_Form_Action);

		$Shop_Delivery_Condition_Controller_Edit->controller($this->_Admin_Form_Controller);

		$Shop_Delivery_Condition_Controller_Edit->generateCountryFields($this, $oMainTab, $this->getField('default'));

		$title = $this->_object->id
			? Core::_('Shop_Warehouse.form_warehouses_edit')
			: Core::_('Shop_Warehouse.form_warehouses_add');

		$this->title($title);

		return $this;
	}

	/**
	 * Processing of the form. Apply object fields.
	 */
	protected function _applyObjectProperty()
	{
		parent::_applyObjectProperty();

		if($this->_object->default)
		{
			$this->_object->active = 1;
			$this->_object->changeDefaultStatus();
		}
	}
}