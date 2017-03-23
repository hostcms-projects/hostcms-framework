<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Online shop.
 *
 * @package HostCMS 6\Shop
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Shop_Payment_System_Controller_Edit extends Admin_Form_Action_Controller_Type_Edit
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
			$object->shop_id = Core_Array::getGet('shop_id');
		}

		parent::setObject($object);

		$this->addMessage(
			Core_Message::get(Core::_('Shop_Payment_System.attention'), 'error')
		);

		$oMainTab = $this->getTab('main');

		$oAdditionalTab = $this->getTab('additional');

		$oAdditionalTab->delete($this->getField('shop_id'));

		$oMainTab
			->add($oMainRow1 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow2 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow3 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow4 = Admin_Form_Entity::factory('Div')->class('row'))
		;

		$oShopField = Admin_Form_Entity::factory('Select')
			->name('shop_id')
			->caption(Core::_('Shop_Payment_System.shop_id'))
			->divAttr(array('class' => 'form-group col-lg-4 col-md-4 col-sm-4'))
			->options(
				$this->_fillShops()
			)
			->value($this->_object->shop_id);

		$oMainRow1->add($oShopField);

		$oAdditionalTab->delete($this->getField('shop_currency_id'));

		$Shop_Controller_Edit = new Shop_Controller_Edit($this->_Admin_Form_Action);

		$oCurrencyField = Admin_Form_Entity::factory('Select')
			->name('shop_currency_id')
			->caption(Core::_('Shop_Payment_System.shop_currency_id'))
			->divAttr(array('class' => 'form-group col-lg-4 col-md-4 col-sm-4'))
			->options($Shop_Controller_Edit->fillCurrencies())
			->value($this->_object->shop_currency_id);

		$oMainRow1->add($oCurrencyField);

		$oMainTab->move($this->getField('sorting')->divAttr(array('class' => 'form-group col-lg-4 col-md-4 col-sm-4')), $oMainRow1);
		$oMainTab->move($this->getField('description')->divAttr(array('class' => 'form-group col-lg-12 col-md-12 col-sm-12')), $oMainRow2);
		$oMainTab->move($this->getField('active')->divAttr(array('class' => 'form-group col-lg-12 col-md-12 col-sm-12')), $oMainRow3);

		$Admin_Form_Entity_Textarea = Admin_Form_Entity::factory('Textarea');

		$oTmpOptions = $Admin_Form_Entity_Textarea->syntaxHighlighterOptions;
		$oTmpOptions['mode'] = 'application/x-httpd-php';

		$Admin_Form_Entity_Textarea
			->value(
				$this->_object->loadPaymentSystemFile()
			)
			->rows(30)
			->divAttr(array('class' => 'form-group col-lg-12 col-md-12 col-sm-12'))
			->caption(Core::_('Shop_Payment_System.system_of_pay_add_form_handler'))
			->name('system_of_pay_add_form_handler')
			->syntaxHighlighter(defined('SYNTAX_HIGHLIGHTING') ? SYNTAX_HIGHLIGHTING : TRUE)
			->syntaxHighlighterOptions($oTmpOptions);

		$oMainRow4->add($Admin_Form_Entity_Textarea);

		$title = $this->_object->id
			? Core::_('Shop_Payment_System.system_of_pay_edit_form_title')
			: Core::_('Shop_Payment_System.system_of_pay_add_form_title');

		$this->title($title);

		return $this;
	}

	/**
	 * Fill shop list
	 * @return array
	 */
	protected function _fillShops()
	{
		$oObject = Core_Entity::factory('Site', CURRENT_SITE);

		$aObjects = $oObject->Shops->findAll();

		$aResult = array(' … ');

		foreach($aObjects as $oObject)
		{
			$aResult[$oObject->id] = $oObject->name;
		}

		return $aResult;
	}

	/**
	 * Processing of the form. Apply object fields.
	 * @hostcms-event Shop_Payment_System_Controller_Edit.onAfterRedeclaredApplyObjectProperty
	 */
	protected function _applyObjectProperty()
	{
		parent::_applyObjectProperty();

		$this->_object->savePaymentSystemFile(Core_Array::getRequest('system_of_pay_add_form_handler'));

		Core_Event::notify(get_class($this) . '.onAfterRedeclaredApplyObjectProperty', $this, array($this->_Admin_Form_Controller));
	}
}