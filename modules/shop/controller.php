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
class Shop_Controller
{
	/**
	 * The singleton instances.
	 * @var mixed
	 */
	static public $instance = NULL;

	/**
	 * Register an existing instance as a singleton.
	 * @return object
	 */
	static public function instance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Float digits format
	 * @var string
	 */
	protected $_floatFormat = "%.2f";

	/**
	 * Set float format
	 * @param string $floatFormat format
	 * @return self
	 */
	public function floatFormat($floatFormat)
	{
		$this->_floatFormat = $floatFormat;
		return $this;
	}

	/**
	 * Округление цен к формату, приведенного в $this->_floatFormat
	 *
	 * @param float $value цена
	 * @return string
	 */
	public function round($value)
	{
		return sprintf($this->_floatFormat, round($value, 2));
	}

	/**
	 * Convert price 
	 * @param string $price price 
	 * @param int $decimalPlaces precision
	 * @return mixed
	 */
	public function convertPrice($price, $decimalPlaces = 2)
	{
		$decimalPlaces = intval($decimalPlaces);
		$price = preg_replace('/[^0-9.,\-]/u', '', $price);
		$price = str_replace(array(',', '-'), '.', $price);

		preg_match("/((\d+(\.)\d{1,{$decimalPlaces}})|\d+)/u", $price, $array_price);
		return isset($array_price[1]) ? floatval($array_price[1]) : 0;
	}

	/**
	 * Определение коэффициента пересчета валюты $oItem_Currency в валюту $oShop_Currency
	 *
	 * @param Shop_Currency_Model $oItem_Currency исходная валюта
	 * @param Shop_Currency_Model $oShop_Currency требуемая валюта
	 * @return float
	 */
	public function getCurrencyCoefficientInShopCurrency(Shop_Currency_Model $oItem_Currency, Shop_Currency_Model $oShop_Currency)
	{
		// Определяем коэффициент пересчета в базовую валюту (НО НЕ В ВАЛЮТУ МАГАЗИНА)!
		$fItemExchangeRate = $oItem_Currency->exchange_rate;
		if ($fItemExchangeRate == 0)
		{
			throw new Core_Exception('Method getCurrencyCoefficientInShopCurrency(): Item currency %id exchange rate is 0.', array('%id' => $oItem_Currency->id));
		}

		// Определяем коэффициент пересчета в валюту магазина
		$fShopExchangeRate = $oShop_Currency->exchange_rate;
		if ($fShopExchangeRate == 0)
		{
			throw new Core_Exception('Method getCurrencyCoefficientInShopCurrency(): Shop currency %id exchange rate is 0.', array('%id' => $oShop_Currency->id));
		}

		// Без округления
		//return round($fItemExchangeRate / $fShopExchangeRate, 2);
		return $fItemExchangeRate / $fShopExchangeRate;
	}
}