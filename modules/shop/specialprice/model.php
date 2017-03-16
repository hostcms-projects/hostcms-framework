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
class Shop_Specialprice_Model extends Core_Entity
{
	/**
	 * Disable markDeleted()
	 * @var mixed
	 */
	protected $_marksDeleted = NULL;

	/**
	 * Belongs to relations
	 * @var array
	 */
	protected $_belongsTo = array(
		'shop_item' => array(),
	);

	/**
	 * Forbidden tags. If list of tags is empty, all tags will be shown.
	 * @var array
	 */
	protected $_forbiddenTags = array(
		'price',
	);

	/**
	 * Get XML for entity and children entities
	 * @return string
	 * @hostcms-event shop_specialprice_model.onBeforeRedeclaredGetXml
	 */
	public function getXml()
	{
		Core_Event::notify($this->_modelName . '.onBeforeRedeclaredGetXml', $this);

		$oShop_Item_Controller = new Shop_Item_Controller();

		// > 0, т.к. $this->price может быть строкой 0.00
		if ($this->price > 0)
		{
			$price = $this->price;
		}
		else
		{
			Core::moduleIsActive('siteuser') && $oShop_Item_Controller->siteuser(
				Core_Entity::factory('Siteuser')->getCurrent()
			);

			$price = $oShop_Item_Controller->getPrice($this->Shop_Item) * $this->percent / 100;
		}

		$aPrices = $oShop_Item_Controller->calculatePrice($price, $this->Shop_Item);

		$this->clearXmlTags()
			->addXmlTag('price', $aPrices['price_discount']);

		return parent::getXml();
	}
}