<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Выбор способа доставки.
 *
 * @package HostCMS 6\Shop
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2013 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Shop_Delivery_Controller_Show extends Core_Controller
{
	/**
	 * Allowed object properties
	 * @var array
	 */
	protected $_allowedProperties = array(
		'shop_country_id',
		'shop_country_location_id',
		'shop_country_location_city_id',
		'shop_country_location_city_area_id',
		'totalWeight',
		'totalAmount',
		'couponText'
	);

	/**
	 * Calculate total amount and weight
	 * @return self
	 */
	public function setUp()
	{
		$oShop = $this->getEntity();

		$Shop_Cart_Controller = Shop_Cart_Controller::instance();

		$amount = 0;
		$quantity = 0;
		$weight = 0;

		$aShop_Cart = $Shop_Cart_Controller->getAll($oShop);
		foreach ($aShop_Cart as $oShop_Cart)
		{
			if ($oShop_Cart->Shop_Item->id)
			{
				if ($oShop_Cart->postpone == 0)
				{
					// Prices
					$oShop_Item_Controller = new Shop_Item_Controller();
					if (Core::moduleIsActive('siteuser'))
					{
						$oSiteuser = Core_Entity::factory('Siteuser')->getCurrent();
						$oSiteuser && $oShop_Item_Controller->siteuser($oSiteuser);
					}
					//$oShop_Item = Core_Entity::factory('Shop_Item', $oShop_Cart->shop_item_id);

					$oShop_Item_Controller->count($oShop_Cart->quantity);
					$aPrices = $oShop_Item_Controller->getPrices($oShop_Cart->Shop_Item);
					$amount += $aPrices['price_discount'] * $oShop_Cart->quantity;
					$quantity += $oShop_Cart->quantity;
					$weight += $oShop_Cart->Shop_Item->weight * $oShop_Cart->quantity;
				}
			}
		}

		// Скидки от суммы заказа
		$oShop_Purchase_Discount_Controller = new Shop_Purchase_Discount_Controller($oShop);
		$oShop_Purchase_Discount_Controller
			->amount($amount)
			->quantity($quantity)
			->couponText($this->couponText)
			;
		$totalDiscount = 0;
		$aShop_Purchase_Discounts = $oShop_Purchase_Discount_Controller->getDiscounts();
		foreach ($aShop_Purchase_Discounts as $oShop_Purchase_Discount)
		{
			$totalDiscount += $oShop_Purchase_Discount->getDiscountAmount();
		}

		$this->totalWeight = $weight;
		$this->totalAmount = $amount - $totalDiscount;

		//echo $this->totalWeight, ' === ', $this->totalAmount;

		return $this;
	}

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

		$this->addEntity(
			Core::factory('Core_Xml_Entity')
				->name('total_weight')
				->value($this->totalWeight)
		)->addEntity(
			Core::factory('Core_Xml_Entity')
				->name('total_amount')
				->value($this->totalAmount)
		);

		// Выбираем все типы доставки для данного магазина
		$aShop_Deliveries = $oShop->Shop_Deliveries->findAll();

		foreach ($aShop_Deliveries as $oShop_Delivery)
		{
			$oShop_Delivery_Condition_Controller = new Shop_Delivery_Condition_Controller();
			$oShop_Delivery_Condition_Controller
				->shop_country_id($this->shop_country_id)
				->shop_country_location_id($this->shop_country_location_id)
				->shop_country_location_city_id($this->shop_country_location_city_id)
				->shop_country_location_city_area_id($this->shop_country_location_city_area_id)
				->totalWeight($this->totalWeight)
				->totalAmount($this->totalAmount);

			// Условие доставки, подходящее под ограничения
			$oShop_Delivery_Condition = $oShop_Delivery_Condition_Controller->getShopDeliveryCondition($oShop_Delivery);

			if (!is_null($oShop_Delivery_Condition))
			{
				$this->addEntity($oShop_Delivery->clearEntities());
				$oShop_Delivery->addEntity($oShop_Delivery_Condition);
			}
		}

		return parent::show();
	}
}