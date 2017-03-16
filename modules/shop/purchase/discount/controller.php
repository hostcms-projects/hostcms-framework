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
class Shop_Purchase_Discount_Controller extends Core_Servant_Properties
{
	/**
	 * Allowed object properties
	 * @var array
	 */
	protected $_allowedProperties = array(
		'amount', // сумма заказа
		'quantity', // количество товаров в заказе
		'couponText' // текст купона, если есть
	);

	/**
	 * Shop
	 * @var Shop_Model
	 */
	protected $_shop = NULL;

	/**
	 * Constructor.
	 * @param Shop_Model $oShop shop
	 */
	public function __construct(Shop_Model $oShop)
	{
		$this->_shop = $oShop;

		parent::__construct();
	}

	/**
	 * Расчет скидки на сумму товара, в соответствии со списком скидок, доступных для указанного магазина
	 */
	function getDiscounts()
	{
		$amount = floatval($this->amount);
		$quantity = floatval($this->quantity);

		$aReturn = array();

		if ($amount <= 0 || $quantity <= 0)
		{
			return $aReturn;
		}

		// Идентификатор скидки по купону
		$shop_purchase_discount_id = 0;

		// Получаем данные о купоне
		if (strlen($this->couponText))
		{
			$oShop_Purchase_Discounts_For_Coupon = $this->_shop->Shop_Purchase_Discounts->getByCouponText($this->couponText);
			!is_null($oShop_Purchase_Discounts_For_Coupon) && $shop_purchase_discount_id = $oShop_Purchase_Discounts_For_Coupon->id;
		}

		// Извлекаем все активные скидки, доступные для текущей даты
		$oShop_Purchase_Discounts = $this->_shop->Shop_Purchase_Discounts;
		$oShop_Purchase_Discounts->queryBuilder()
			->where('active', '=', 1)
			//->where('coupon', '=', 0)
			->where('start_datetime', '<=', Core_Date::timestamp2sql(time()))
			->where('end_datetime', '>=', Core_Date::timestamp2sql(time()));

		$aShop_Purchase_Discounts = $oShop_Purchase_Discounts->findAll();

		$oShop_Controller = Shop_Controller::instance();

		foreach ($aShop_Purchase_Discounts as $oShop_Purchase_Discount)
		{
			// Определяем коэффициент пересчета
			$fCoefficient = $oShop_Purchase_Discount->shop_currency_id > 0 && $this->_shop->shop_currency_id > 0
				? $oShop_Controller->getCurrencyCoefficientInShopCurrency(
					$oShop_Purchase_Discount->Shop_Currency, $this->_shop->Shop_Currency
				)
				: 0;

			// Нижний предел скидки
			$min_amount = $fCoefficient * $oShop_Purchase_Discount->min_amount;

			// Верхний предел скидки
			$max_amount = $fCoefficient * $oShop_Purchase_Discount->max_amount;

			$bCheckAmount = $amount >= $min_amount
				&& ($amount < $max_amount || $max_amount == 0)
				&& (!$oShop_Purchase_Discount->coupon || $oShop_Purchase_Discount->id == $shop_purchase_discount_id);

			$bCheckQuantity = $quantity >= $oShop_Purchase_Discount->min_count
				&& ($quantity < $oShop_Purchase_Discount->max_count || $oShop_Purchase_Discount->max_count == 0)
				&& (!$oShop_Purchase_Discount->coupon || $oShop_Purchase_Discount->id == $shop_purchase_discount_id);

			// И
			if ($oShop_Purchase_Discount->type == 0 && $bCheckAmount && $bCheckQuantity
			// ИЛИ
			|| $oShop_Purchase_Discount->type == 1 && ($bCheckAmount || $bCheckQuantity))
			{
				// Учитываем перерасчет суммы скидки в валюту магазина
				$discount = $fCoefficient * $oShop_Purchase_Discount->type == 0
					// Процент
					? $amount * $oShop_Purchase_Discount->value / 100
					// Фиксированная скидка
					: $oShop_Purchase_Discount->value;

				$aReturn[] = $oShop_Purchase_Discount->discountAmount($discount);
			}
		}

		return $aReturn;
	}
}