<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Shop_Order_Item Backend Editing Controller.
 *
 * @package HostCMS
 * @subpackage Shop
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2016 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Shop_Order_Item_Controller_Edit extends Admin_Form_Action_Controller_Type_Edit
{
	/**
	 * Set object
	 * @param object $object object
	 * @return self
	 */
	public function setObject($object)
	{
		if (!$object->id)
		{
			$object->shop_order_id = Core_Array::getGet('shop_order_id');
		}

		$this->addSkipColumn('hash');
		$this->addSkipColumn('shop_item_digital_id');

		parent::setObject($object);

		$oMainTab = $this->getTab('main');
		$oAdditionalTab = $this->getTab('additional');

		$oMainTab
			->add($oMainRow1 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow2 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow3 = Admin_Form_Entity::factory('Div')->class('row'));

		$oOrder = Core_Entity::factory('Shop_Order', intval(Core_Array::getGet('shop_order_id')));

		$oMainTab->move($this->getField('quantity')->divAttr(array('class' => 'form-group col-lg-3 col-md-3 col-sm-3 col-xs-12')), $oMainRow1);
		$oMainTab->move($this->getField('price')->divAttr(array('class' => 'form-group col-lg-3 col-md-3 col-sm-3 col-xs-5')), $oMainRow1);
		$oMainTab->move($this->getField('rate')->divAttr(array('class' => 'form-group col-lg-3 col-md-3 col-sm-3 col-xs-5')), $oMainRow1);

		$oMainRow1->add(Admin_Form_Entity::factory('Span')
			->value('%')
			->style("font-size: 200%")
			->divAttr(array('class' => 'form-group col-lg-3 col-md-3 col-sm-3 col-xs-2', 'style' => 'padding-top: 20px'))
			);

		$this->getField('name')->format(array('minlen' => array('value' => 0)));

		$oMainTab->moveAfter($this->getField('rate'), $this->getField('price'));

		$oAdditionalTab->delete($this->getField('shop_warehouse_id'));

		$oMainRow2->add(
			Admin_Form_Entity::factory('Select')
				->caption(Core::_('Shop_Order_Item.shop_warehouse_id'))
				->options(
					$this->_fillWarehousesList(Core_Array::getGet('shop_id'))
				)
				->name('shop_warehouse_id')
				->value($this->_object->shop_warehouse_id)
				->divAttr(array('class' => 'form-group col-lg-6 col-md-6 col-sm-6 col-xs-6'))
		);

		$oMainTab->delete($this->getField('type'));

		$oMainRow2->add(
			Admin_Form_Entity::factory('Select')
				->caption(Core::_('Shop_Order_Item.type'))
				->options(
					array(
						Core::_('Shop_Order_Item.order_item_type_caption0'),
						Core::_('Shop_Order_Item.order_item_type_caption1'),
						Core::_('Shop_Order_Item.order_item_type_caption2')
					)
				)
				->name('type')
				->value($this->_object->type)
				->divAttr(array('class' => 'form-group col-lg-6 col-md-6 col-sm-6 col-xs-6'))
		);

		$oMainTab->move($this->getField('marking')->divAttr(array('class' => 'form-group col-lg-6 col-md-6 col-sm-6 col-xs-6')), $oMainRow3);
		$oMainTab->move($this->getField('marking')->divAttr(array('class' => 'form-group col-lg-6 col-md-6 col-sm-6 col-xs-6')), $oMainRow3);

		$oAdditionalTab->move($this->getField('shop_item_id')/*->style("width: 200px")*/, $oMainTab);

		$oMainTab->move($this->getField('shop_item_id')->divAttr(array('class' => 'form-group col-lg-6 col-md-6 col-sm-6 col-xs-6')), $oMainRow3);

		$title = $this->_object->id
			? Core::_('Shop_Order_Item.order_items_edit_form_title', $oOrder->invoice)
			: Core::_('Shop_Order_Item.order_items_add_form_title', $oOrder->invoice);

		$this->title($title);

		return $this;
	}

	/**
	 * Processing of the form. Apply object fields.
	 * @return self
	 * @hostcms-event Shop_Order_Item_Controller_Edit.onAfterRedeclaredApplyObjectProperty
	 */
	protected function _applyObjectProperty()
	{
		// New order item
		if (!$this->_object->id)
		{
			$shop_item_id = Core_Array::get($this->_formValues, 'shop_item_id');

			if ($shop_item_id &&
				!is_null($oShop_Item = Core_Entity::factory('Shop_Item')->find($shop_item_id, FALSE)))
			{
				Core_Array::get($this->_formValues, 'name') == '' && $this->_formValues['name'] = $oShop_Item->name;
				floatval(Core_Array::get($this->_formValues, 'quantity')) == 0.0 && $this->_formValues['quantity'] = 1.0;
				floatval(Core_Array::get($this->_formValues, 'price')) == 0.0 && $this->_formValues['price'] = $oShop_Item->price;
				Core_Array::get($this->_formValues, 'marking') == '' && $this->_formValues['marking'] = $oShop_Item->marking;
			}
		}

		parent::_applyObjectProperty();

		// Reset `unloaded`
		$this->_object->Shop_Order
			->unloaded(0)
			->save();

		Core_Event::notify(get_class($this) . '.onAfterRedeclaredApplyObjectProperty', $this, array($this->_Admin_Form_Controller));

		return $this;
	}

	/**
	 * Fill warehouses list
	 * @param int $iShopId shop ID
	 * @return array
	 */
	protected function _fillWarehousesList($iShopId)
	{
		$oObject = Core_Entity::factory('Shop_Warehouse');

		$oObject->queryBuilder()
			->where("shop_id", "=", $iShopId)
			->orderBy("sorting")
			->orderBy("id");

		$aObjects = $oObject->findAll();

		$aReturn = array(" … ");

		foreach ($aObjects as $oObject)
		{
			$aReturn[$oObject->id] = $oObject->name;
		}

		return $aReturn;
	}
}