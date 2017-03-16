<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Выбор платежной системы.
 *
 * @package HostCMS 6\Shop
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2013 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Shop_Payment_System_Controller_Show extends Core_Controller
{
	/**
	 * Constructor.
	 * @param Shop_Model $oShop shop
	 */
	public function __construct(Shop_Model $oShop)
	{
		parent::__construct($oShop->clearEntities());

		if (Core::moduleIsActive('siteuser'))
		{
			// Если есть модуль пользователей сайта, $siteuser_id равен 0 или ID авторизованного
			$oSiteuser = Core_Entity::factory('Siteuser')->getCurrent();
			if ($oSiteuser)
			{
				$this->addEntity($oSiteuser->clearEntities());
			}
		}
	}

	/**
	 * Show built data
	 * @return self
	 */
	public function show()
	{
		$oShop = $this->getEntity();

		$aShop_Payment_Systems = $oShop->Shop_Payment_Systems->getAllByActive(1);
		foreach ($aShop_Payment_Systems as $oShop_Payment_System)
		{
			$this->addEntity($oShop_Payment_System->clearEntities());
		}

		return parent::show();
	}
}