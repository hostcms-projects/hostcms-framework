<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Properties.
 *
 * @package HostCMS 6\Shop
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2013 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Shop_Item_Property_Controller_Edit extends Property_Controller_Edit
{
	/**
	 * Set object
	 * @param object $object object
	 * @return self
	 */
	public function setObject($object)
	{
		parent::setObject($object);

		$modelName = $this->_object->getModelName();

		$oMainTab = $this->getTab('main');
		$oAdditionalTab = $this->getTab('additional');

		// Создаем вкладку
		$oShopItemTabExportImport = Admin_Form_Entity::factory('Tab')
			->caption(Core::_('Shop_Item.tab_export'))
			->name('ExportImport');

		// Добавляем вкладку
		$this
			->addTabAfter($oShopItemTabExportImport, $oMainTab);

		switch($modelName)
		{
			case 'property':

				// Создаем экземпляр контроллера магазина
				$Shop_Controller_Edit = new Shop_Controller_Edit($this->_Admin_Form_Action);

				//Переносим GUID на "Экспорт/Импорт"
				$oAdditionalTab->move($this->getField('guid'), $oShopItemTabExportImport);

				// Создаем поле единиц измерения как выпадающий список
				$oShopMeasuresSelect = Admin_Form_Entity::factory('Select');

				$oShopMeasuresSelect
					->caption(Core::_("Shop_Item.shop_measure_id"))
					->style("width: 100px")
					->options(
						$Shop_Controller_Edit->fillMeasures()
					)
					->name('shop_measure_id')
					->value($this->_object->Shop_Item_Property->shop_measure_id)
					->divAttr(array('style' => 'float: left'));

				$oMainTab
					->add($oShopMeasuresSelect);

				// Префикс
				$oShopPrefixInput = Admin_Form_Entity::factory('Input');
				$oShopPrefixInput
					->caption(Core::_('Shop_Item.property_prefix'))
					->style('width: 100px')
					->name('prefix')
					->value($this->_object->Shop_Item_Property->prefix)
					->divAttr(array('style' => 'float: left'));

				$oMainTab
					->add($oShopPrefixInput);

				// Способ отображения в фильтре
				$oShopFilterSelect = Admin_Form_Entity::factory('Select');
				$oShopFilterSelect
					->caption(Core::_('Shop_Item.property_filter'))
					->style('width: 250px')
					->options(
						array(0 => Core::_('Shop_Item.properties_show_kind_none'),
						1 => Core::_('Shop_Item.properties_show_kind_text'),
						2 => Core::_('Shop_Item.properties_show_kind_list'),
						3 => Core::_('Shop_Item.properties_show_kind_radio'),
						4 => Core::_('Shop_Item.properties_show_kind_checkbox'),
						5 => Core::_('Shop_Item.properties_show_kind_checkbox_one'),
						6 => Core::_('Shop_Item.properties_show_kind_from_to'),
						7 => Core::_('Shop_Item.properties_show_kind_listbox'))
					)
					->name('filter')
					->value($this->_object->Shop_Item_Property->filter);
					//->divAttr(array('style' => 'float: left'));

				$oMainTab
					->add($oShopFilterSelect);

				$oShopShowInGroupCheckbox = Admin_Form_Entity::factory('Checkbox');
				$oShopShowInGroupCheckbox
					->value($this->_object->Shop_Item_Property->show_in_group)
					->caption(Core::_("Shop_Item.show_in_group"))
					->name("show_in_group");
					//->divAttr(array('style' => 'float: left'));

				$oMainTab
					->add($oShopShowInGroupCheckbox);

				$oShopShowInItemCheckbox = Admin_Form_Entity::factory('Checkbox');
				$oShopShowInItemCheckbox
					->value($this->_object->Shop_Item_Property->show_in_item)
					->caption(Core::_("Shop_Item.show_in_item"))
					->name("show_in_item");
					//->divAttr(array('style' => 'float: left'));

				$oMainTab
					->add($oShopShowInItemCheckbox);

			break;
			case 'property_dir':
			default:
			break;
		}

		return $this;
	}

	/**
	 * Processing of the form. Apply object fields.
	 */
	protected function _applyObjectProperty()
	{
		parent::_applyObjectProperty();

		$modelName = $this->_object->getModelName();

		switch($modelName)
		{
			case 'property':
				$Shop_Item_Property = $this->_object->Shop_Item_Property;
				$Shop_Item_Property->shop_measure_id = intval(Core_Array::getPost('shop_measure_id'));
				$Shop_Item_Property->prefix = Core_Array::getPost('prefix');
				$Shop_Item_Property->filter = intval(Core_Array::getPost('filter'));
				$Shop_Item_Property->show_in_group = intval(Core_Array::getPost('show_in_group'));
				$Shop_Item_Property->show_in_item = intval(Core_Array::getPost('show_in_item'));
				$Shop_Item_Property->save();
			break;
			case 'property_dir':
			break;
		}
	}
}