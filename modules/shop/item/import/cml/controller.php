<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Online shop.
 * 2.0.8 - http://v8.1c.ru/edi/edi_stnd/90/CML208.XSD
 *
 * @package HostCMS 6\Shop
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Shop_Item_Import_Cml_Controller extends Core_Servant_Properties
{
	/**
	 * Коды валют для МойСклад
	 * @var array
	 */
	protected $_aCurrencyCodes = array(
		'971' => 'AFN',
		'978' => 'EUR',
		'008' => 'ALL',
		'012' => 'DZD',
		'840' => 'USD',
		'978' => 'EUR',
		'973' => 'AOA',
		'951' => 'XCD',
		'951' => 'XCD',
		'032' => 'ARS',
		'051' => 'AMD',
		'533' => 'AWG',
		'036' => 'AUD',
		'978' => 'EUR',
		'944' => 'AZN',
		'044' => 'BSD',
		'048' => 'BHD',
		'050' => 'BDT',
		'052' => 'BBD',
		'974' => 'BYR',
		'978' => 'EUR',
		'084' => 'BZD',
		'952' => 'XOF',
		'060' => 'BMD',
		'064' => 'BTN',
		'356' => 'INR',
		'068' => 'BOB',
		'984' => 'BOV',
		'840' => 'USD',
		'977' => 'BAM',
		'072' => 'BWP',
		'578' => 'NOK',
		'986' => 'BRL',
		'840' => 'USD',
		'096' => 'BND',
		'975' => 'BGN',
		'952' => 'XOF',
		'108' => 'BIF',
		'116' => 'KHR',
		'950' => 'XAF',
		'124' => 'CAD',
		'132' => 'CVE',
		'136' => 'KYD',
		'950' => 'XAF',
		'950' => 'XAF',
		'990' => 'CLF',
		'152' => 'CLP',
		'156' => 'CNY',
		'036' => 'AUD',
		'036' => 'AUD',
		'170' => 'COP',
		'970' => 'COU',
		'174' => 'KMF',
		'950' => 'XAF',
		'976' => 'CDF',
		'554' => 'NZD',
		'188' => 'CRC',
		'952' => 'XOF',
		'191' => 'HRK',
		'931' => 'CUC',
		'192' => 'CUP',
		'532' => 'ANG',
		'978' => 'EUR',
		'203' => 'CZK',
		'208' => 'DKK',
		'262' => 'DJF',
		'951' => 'XCD',
		'214' => 'DOP',
		'840' => 'USD',
		'818' => 'EGP',
		'222' => 'SVC',
		'840' => 'USD',
		'950' => 'XAF',
		'232' => 'ERN',
		'978' => 'EUR',
		'230' => 'ETB',
		'978' => 'EUR',
		'238' => 'FKP',
		'208' => 'DKK',
		'242' => 'FJD',
		'978' => 'EUR',
		'978' => 'EUR',
		'978' => 'EUR',
		'953' => 'XPF',
		'978' => 'EUR',
		'950' => 'XAF',
		'270' => 'GMD',
		'981' => 'GEL',
		'978' => 'EUR',
		'936' => 'GHS',
		'292' => 'GIP',
		'978' => 'EUR',
		'208' => 'DKK',
		'951' => 'XCD',
		'978' => 'EUR',
		'840' => 'USD',
		'320' => 'GTQ',
		'826' => 'GBP',
		'324' => 'GNF',
		'952' => 'XOF',
		'328' => 'GYD',
		'332' => 'HTG',
		'840' => 'USD',
		'036' => 'AUD',
		'978' => 'EUR',
		'340' => 'HNL',
		'344' => 'HKD',
		'348' => 'HUF',
		'352' => 'ISK',
		'356' => 'INR',
		'360' => 'IDR',
		'960' => 'XDR',
		'364' => 'IRR',
		'368' => 'IQD',
		'978' => 'EUR',
		'826' => 'GBP',
		'376' => 'ILS',
		'978' => 'EUR',
		'388' => 'JMD',
		'392' => 'JPY',
		'826' => 'GBP',
		'400' => 'JOD',
		'398' => 'KZT',
		'404' => 'KES',
		'036' => 'AUD',
		'408' => 'KPW',
		'410' => 'KRW',
		'414' => 'KWD',
		'417' => 'KGS',
		'418' => 'LAK',
		'978' => 'EUR',
		'422' => 'LBP',
		'426' => 'LSL',
		'710' => 'ZAR',
		'430' => 'LRD',
		'434' => 'LYD',
		'756' => 'CHF',
		'978' => 'EUR',
		'978' => 'EUR',
		'446' => 'MOP',
		'807' => 'MKD',
		'969' => 'MGA',
		'454' => 'MWK',
		'458' => 'MYR',
		'462' => 'MVR',
		'952' => 'XOF',
		'978' => 'EUR',
		'840' => 'USD',
		'978' => 'EUR',
		'478' => 'MRO',
		'480' => 'MUR',
		'978' => 'EUR',
		'965' => 'XUA',
		'484' => 'MXN',
		'979' => 'MXV',
		'840' => 'USD',
		'498' => 'MDL',
		'978' => 'EUR',
		'496' => 'MNT',
		'978' => 'EUR',
		'951' => 'XCD',
		'504' => 'MAD',
		'943' => 'MZN',
		'104' => 'MMK',
		'516' => 'NAD',
		'710' => 'ZAR',
		'036' => 'AUD',
		'524' => 'NPR',
		'978' => 'EUR',
		'953' => 'XPF',
		'554' => 'NZD',
		'558' => 'NIO',
		'952' => 'XOF',
		'566' => 'NGN',
		'554' => 'NZD',
		'036' => 'AUD',
		'840' => 'USD',
		'578' => 'NOK',
		'512' => 'OMR',
		'586' => 'PKR',
		'840' => 'USD',
		'590' => 'PAB',
		'840' => 'USD',
		'598' => 'PGK',
		'600' => 'PYG',
		'604' => 'PEN',
		'608' => 'PHP',
		'554' => 'NZD',
		'985' => 'PLN',
		'978' => 'EUR',
		'840' => 'USD',
		'634' => 'QAR',
		'978' => 'EUR',
		'946' => 'RON',
		'643' => 'RUB',
		'810' => 'RUR',
		'646' => 'RWF',
		'978' => 'EUR',
		'654' => 'SHP',
		'951' => 'XCD',
		'951' => 'XCD',
		'978' => 'EUR',
		'978' => 'EUR',
		'951' => 'XCD',
		'882' => 'WST',
		'978' => 'EUR',
		'678' => 'STD',
		'682' => 'SAR',
		'952' => 'XOF',
		'941' => 'RSD',
		'690' => 'SCR',
		'694' => 'SLL',
		'702' => 'SGD',
		'532' => 'ANG',
		'994' => 'XSU',
		'978' => 'EUR',
		'978' => 'EUR',
		'090' => 'SBD',
		'706' => 'SOS',
		'710' => 'ZAR',
		'728' => 'SSP',
		'978' => 'EUR',
		'144' => 'LKR',
		'938' => 'SDG',
		'968' => 'SRD',
		'578' => 'NOK',
		'748' => 'SZL',
		'752' => 'SEK',
		'947' => 'CHE',
		'756' => 'CHF',
		'948' => 'CHW',
		'760' => 'SYP',
		'901' => 'TWD',
		'972' => 'TJS',
		'834' => 'TZS',
		'764' => 'THB',
		'840' => 'USD',
		'952' => 'XOF',
		'554' => 'NZD',
		'776' => 'TOP',
		'780' => 'TTD',
		'788' => 'TND',
		'949' => 'TRY',
		'934' => 'TMT',
		'840' => 'USD',
		'036' => 'AUD',
		'800' => 'UGX',
		'980' => 'UAH',
		'784' => 'AED',
		'826' => 'GBP',
		'840' => 'USD',
		'997' => 'USN',
		'840' => 'USD',
		'940' => 'UYI',
		'858' => 'UYU',
		'860' => 'UZS',
		'548' => 'VUV',
		'937' => 'VEF',
		'704' => 'VND',
		'840' => 'USD',
		'840' => 'USD',
		'953' => 'XPF',
		'504' => 'MAD',
		'886' => 'YER',
		'967' => 'ZMW',
		'932' => 'ZWL',
		'955' => 'XBA',
		'956' => 'XBB',
		'957' => 'XBC',
		'958' => 'XBD',
		'963' => 'XTS',
		'999' => 'XXX',
		'959' => 'XAU',
		'964' => 'XPD',
		'962' => 'XPT',
		'961' => 'XAG'
	);

	/**
	 * Return data
	 * @var array
	 */
	protected $_aReturn = array(
		'insertDirCount' => 0,
		'insertItemCount' => 0,
		'updateDirCount' => 0,
		'updateItemCount' => 0
	);

	/**
	 * XML
	 * @var SimpleXMLElement
	 */
	protected $_oSimpleXMLElement = NULL;

	/**
	 * List of predefined base properties
	 * @var array
	 */
	protected $aPredefinedBaseProperties = array(
		"HOSTCMS_TITLE",
		"HOSTCMS_DESCRIPTION",
		"HOSTCMS_KEYWORDS",
		"HOSTCMS_МЕТКИ",
		"YANDEX_MARKET",
		"ПРОДАВЕЦ",
		"ПРОИЗВОДИТЕЛЬ",
		"АКТИВНОСТЬ");

	/**
	 * List of predefined additional properties
	 * @var array
	 */
	protected $aPredefinedAdditionalProperties = array();

	/**
	 * List of base properties
	 * @var array
	 */
	protected $aBaseProperties = array();

	/**
	 * List of additional properties
	 * @var array
	 */
	protected $aAdditionalProperties = array();

	/**
	 * Tax for default price
	 * @var object
	 */
	protected $_oTaxForBasePrice = NULL;

	/**
	 * Allowed object properties
	 * @var array
	 */
	protected $_allowedProperties = array(
		'itemDescription',
		'iShopId',
		'iShopGroupId',
		'sShopDefaultPriceName',
		'sShopDefaultPriceGUID',
		'sPicturesPath',
		'importAction',
		'namespace',
		'debug'
	);

	/**
	 * Values of property
	 * @var array
	 */
	protected $_aPropertyValues = array();

	protected $_temporaryPropertyFile = '';

	/**
	 * Constructor.
	 * @param string $sXMLFilePath file path
	 */
	public function __construct($sXMLFilePath)
	{
		parent::__construct();

		$aConfig = Core_Config::instance()->get('shop_cml', array()) + array(
			'predefinedAdditionalProperties' => array()
		);

		$this->aPredefinedAdditionalProperties = is_array($aConfig['predefinedAdditionalProperties'])
			? $aConfig['predefinedAdditionalProperties']
			: array();

		$this->_oSimpleXMLElement = new SimpleXMLElement(
			// Delete  xmlns="urn:1C.ru:commerceml_2"
			/*str_replace(array(' xmlns="urn:1C.ru:commerceml_2"', ' xmlns="urn:1C.ru:commerceml_205"'), '',*/
				Core_File::read($sXMLFilePath)
			/*)*/
		);
		$this->sShopDefaultPriceName = 'РОЗНИЧНАЯ';
		$this->sShopDefaultPriceGUID = '';

		$this->itemDescription = 'text';

		$this->_temporaryPropertyFile = CMS_FOLDER . TMP_DIR . "1c_exchange_files/modproplist.tmp";
		Core_File::mkdir(dirname($this->_temporaryPropertyFile));
	}

	/**
	 * Import group
	 * @param SimpleXMLElement $oXMLNode node
	 * @param int $iParentId parent ID
	 * @return self
	 */
	protected function _importGroups($oXMLNode, $iParentId = 0)
	{
		foreach ($this->xpath($oXMLNode, 'Группа') as $oXMLGroupNode)
		{
			$oShopGroup = Core_Entity::factory('Shop', $this->iShopId)->Shop_Groups->getByGuid(strval($oXMLGroupNode->Ид), FALSE);
			is_null($oShopGroup) && $oShopGroup = Core_Entity::factory('Shop_Group');
			$oShopGroup->name = strval($oXMLGroupNode->Наименование);
			$oShopGroup->guid = strval($oXMLGroupNode->Ид);

			if (count($aDescriptionArray = $this->xpath($oXMLGroupNode, 'Описание')))
			{
				$oShopGroup->description = strval($aDescriptionArray[0]);
			}

			$oShopGroup->parent_id = $iParentId;
			$oShopGroup->shop_id = $this->iShopId;

			is_null($oShopGroup->id)
				? $this->_aReturn['insertDirCount']++
				: $this->_aReturn['updateDirCount']++;

			is_null($oShopGroup->path) && $oShopGroup->path= '';
			$oShopGroup->save();

			//$oSubGroups = $oXMLGroupNode->Группы;
			//is_object($oSubGroups) && $this->_importGroups($oSubGroups, $oShopGroup->id);
			foreach ($this->xpath($oXMLGroupNode, 'Группы') as $Groups)
			{
				$this->_importGroups($Groups, $oShopGroup->id);
			}

		}
		return $this;
	}

	/**
	 * Add characteristic to item
	 * @param object $oShopItem shop item
	 * @param object $oItemProperty item property
	 * @return self
	 */
	protected function _addCharacteristic($oShopItem, $oItemProperty)
	{
		$oTempShopItem = $oShopItem;

		// Проверяем, есть ли у характеристики идентификатор модификации
		if (count($aIdModificationArray = $this->xpath($oItemProperty, 'Ид')))
		{
			$oTempShopItem = NULL;
			$sModificationGuid = strval($aIdModificationArray[0]);

			// Модификация найдена - добавляем ей это свойство
			if (count($aShop_Items = $oShop->Shop_Items->getAllByguid($sModificationGuid)))
			{
				$oTempShopItem = $aShop_Items[0];
			}
			else
			{
				// модификация не найдена, возможно, она будет загружена из файла offers.xml, сохраняем свойства в массив
				$aModificationProperties[$sModificationGuid][] = array('name'=>strval($oItemProperty->Наименование),'value'=>strval($oItemProperty->Значение));
			}
		}

		// Характеристики загружаются безотносительно содержимого конфигурационного файла cml.php
		$this->_addPredefinedAdditionalProperty($oTempShopItem, strval($oItemProperty->Наименование), strval($oItemProperty->Значение), TRUE);

		return $this;
	}

	/**
	 * Check if property exists in array and save it if so
	 * @return self
	 */
	protected function _addPredefinedAdditionalProperty($oShopItem, $sPropertyGUID, $sValue, $bForcedAdd = FALSE)
	{
		if (is_null($oShopItem))
		{
			return $this;
		}
		if (mb_strtoupper($sPropertyGUID) == 'ВЕС')
		{
			$oShopItem->weight = Shop_Controller::instance()->convertPrice($sValue);
			$oShopItem->save();
			return $this;
		}
		/*// для совместимости с МойСклад
		// не импортируется, т.к. затирает значение поля "Описание"
		if (mb_strtoupper($sPropertyGUID) == 'ПОЛНОЕ НАИМЕНОВАНИЕ')
		{
			$oShopItem->description = $sValue;
			$oShopItem->save();
			return $this;
		}*/
		if ($bForcedAdd || (in_array(mb_strtoupper($sPropertyGUID), array_map('mb_strtoupper', $this->aPredefinedAdditionalProperties))!==FALSE))
		{
			$this->_addItemProperty($oShopItem, $sPropertyGUID, $sValue);
		}
		return $this;
	}

	/**
	 * Add tax info
	 * @param SimpleXMLElement $oTax tax
	 * @return Shop_Tax
	 */
	protected function _addTax($oTax)
	{
		$sTaxName = strval($oTax->Наименование);
		$sTaxRate = strval($oTax->Ставка);
		$sTaxGUID = md5(mb_strtoupper($sTaxName));

		$oShopTax = Core_Entity::factory('Shop_Tax')->getByGuid($sTaxGUID, FALSE);

		if (is_null($oShopTax))
		{
			$oShopTax = Core_Entity::factory('Shop_Tax');
			$oShopTax->name = $sTaxName;
			$oShopTax->guid = $sTaxGUID;
			$oShopTax->rate = ($sTaxRate == '' ? 0 : ($sTaxRate == 'Без налога' ? 0 : $sTaxRate));
			$oShopTax->tax_is_included = 0;
		}

		$oShopTax->save();

		return $oShopTax;
	}

	/**
	 * Add property to item
	 * @param Shop_Item_Model $oShopItem item
	 * @param string $sPropertyGUID property GUID
	 * @param string $sValue property value
	 */
	protected function _addItemProperty($oShopItem, $sPropertyGUID, $sValue)
	{
		$oShop_Item_Property_List = Core_Entity::factory('Shop_Item_Property_List', $this->iShopId);
		$oProperty = $oShop_Item_Property_List->Properties->getByGuid($sPropertyGUID, FALSE);

		$oShop = Core_Entity::factory('Shop', $this->iShopId);

		if (is_null($oProperty))
		{
			$oProperty = Core_Entity::factory('Property');
			$oProperty->name = isset($this->aAdditionalProperties[$sPropertyGUID]) ? $this->aAdditionalProperties[$sPropertyGUID] : $sPropertyGUID;
			$oProperty->type = 1;
			$oProperty->tag_name = Core_Str::transliteration(isset($this->aAdditionalProperties[$sPropertyGUID]) ? $this->aAdditionalProperties[$sPropertyGUID] : $sPropertyGUID);
			$oProperty->guid = $sPropertyGUID;

			// Для вновь создаваемого допсвойства размеры берем из магазина
			$oProperty->image_large_max_width = $oShop->image_large_max_width;
			$oProperty->image_large_max_height = $oShop->image_large_max_height;
			$oProperty->image_small_max_width = $oShop->image_small_max_width;
			$oProperty->image_small_max_height = $oShop->image_small_max_height;

			$oShop_Item_Property_List->add($oProperty);
		}

		$oShop->Shop_Item_Property_For_Groups->allowAccess($oProperty->Shop_Item_Property->id, ($oShopItem->modification_id == 0
			? intval($oShopItem->Shop_Group->id)
			: intval($oShopItem->Modification->Shop_Group->id)
		));

		$sPropertyValue = isset($this->_aPropertyValues[$sValue])
			? $this->_aPropertyValues[$sValue]
			: $sValue;

		$aPropertyValues = $oProperty->getValues($oShopItem->id, FALSE);

		$oProperty_Value = isset($aPropertyValues[0])
			? $aPropertyValues[0]
			: $oProperty->createNewValue($oShopItem->id);

		/*if ($oProperty->type == 7)
		{
			mb_strtoupper($sPropertyValue) == 'ДА' ? $sPropertyValue = 1 : $sPropertyValue = intval($sPropertyValue);
		}*/

		$this->_setPropertyValue($oProperty_Value, $sPropertyValue);
	}

	/**
	 * Import images
	 * @param Shop_Item_Model $oShopItem item
	 * @param array $oImages
	 */
	protected function _importImages(Shop_Item_Model $oShopItem, $oImages)
	{
		$bFirstPicture = TRUE;
		$sGUID = 'ADDITIONAL-IMAGES';

		$oShop = $oShopItem->Shop;

		clearstatcache();

		$this->debug && Core_Log::instance()->clear()
			->status(Core_Log::$MESSAGE)
			->write(sprintf('1С, импорт %d изображений товара ID=%d', count($oImages), $oShopItem->id));

		// Обрабатываем изображения для товара
		foreach ($oImages as $PictureData)
		{
			if (Core_File::isValidExtension($PictureData, Core::$mainConfig['availableExtension']))
			{
				// Папка назначения
				$sDestinationFolder = $oShopItem->getItemPath();

				if (!$bFirstPicture)
				{
					$sFileName = basename($PictureData);
					$sFileDescription = strval($PictureData->attributes()->Описание);

					$oShop_Item_Property_List = Core_Entity::factory('Shop_Item_Property_List', $oShop->id);

					/*$sTmp = explode('_', basename($PictureData));
					$sTmp[1] = basename($sTmp[1], '.'.Core_File::getExtension($sTmp[1]));

					$sPictureData = strval($PictureData);
					$oNewPropertyGUID = $this->xpath($oItem, "ЗначенияРеквизитов/ЗначениеРеквизита[starts-with(Значение, '{$sPictureData}')]/Значение");

					$sPropertyDescription = '';
					if ($oNewPropertyGUID !== FALSE && count($oNewPropertyGUID) > 0)
					{
						$oNewPropertyGUID = strval($oNewPropertyGUID[0]);

						$oNewPropertyGUID = explode('#', $oNewPropertyGUID);
						if (count($oNewPropertyGUID) == 2)
						{
							//$sTmp[1] = $oNewPropertyGUID[1];
							$sTmp[1] = md5($oNewPropertyGUID[0]);
							$sPropertyDescription = $oNewPropertyGUID[1];
						}
					}

					$oProperty = $oShop_Item_Property_List->Properties->getByGuid($sTmp[1], FALSE);*/

					$oProperty = $oShop_Item_Property_List->Properties->getByGuid($sGUID, FALSE);

					if (is_null($oProperty))
					{
						/*$oProperty = Core_Entity::factory('Property');
						$oProperty->name = $sTmp[1];
						$oProperty->type = 2;
						$oProperty->description = $sPropertyDescription;
						$oProperty->tag_name = $sTmp[1];
						$oProperty->guid = $sTmp[1];*/

						$oProperty = Core_Entity::factory('Property');
						$oProperty->name = 'Images';
						$oProperty->type = 2;
						$oProperty->description = '';
						$oProperty->tag_name = 'images';
						$oProperty->guid = $sGUID;

						// Для вновь создаваемого допсвойства размеры берем из магазина
						$oProperty->image_large_max_width = $oShop->image_large_max_width;
						$oProperty->image_large_max_height = $oShop->image_large_max_height;
						$oProperty->image_small_max_width = $oShop->image_small_max_width;
						$oProperty->image_small_max_height = $oShop->image_small_max_height;

						$oShop_Item_Property_List->add($oProperty);
					}

					$oShop->Shop_Item_Property_For_Groups->allowAccess($oProperty->Shop_Item_Property->id, ($oShopItem->modification_id == 0
						? intval($oShopItem->Shop_Group->id)
						: intval($oShopItem->Modification->Shop_Group->id)
					));

					$aPropertyValues = $oProperty->getValues($oShopItem->id, FALSE);

					$oProperty_Value = NULL;
					foreach ($aPropertyValues as $oTmpPropertyValue)
					{
						// Ранее загруженное значение ищим по имени файла
						if ($oTmpPropertyValue->file_name == $sFileName)
						{
							$oProperty_Value = $oTmpPropertyValue;
							break;
						}
					}

					is_null($oProperty_Value) && $oProperty_Value = $oProperty->createNewValue($oShopItem->id);

					$oProperty_Value->save();

					/*$oProperty_Value = isset($aPropertyValues[0])
						? $aPropertyValues[0]
						: $oProperty->createNewValue($oShopItem->id);*/

					if ($oProperty_Value->file != '')
					{
						try
						{
							Core_File::delete($sDestinationFolder . $oProperty_Value->file);
						} catch (Exception $e) {}
					}

					// Удаляем старое малое изображение
					if ($oProperty_Value->file_small != '')
					{
						try
						{
							Core_File::delete($sDestinationFolder . $oProperty_Value->file_small);
						} catch (Exception $e) {}
					}
				}
				else
				{
					if ($oShopItem->image_large != '' && is_file($sDestinationFolder . $oShopItem->image_large))
					{
						try
						{
							Core_File::delete($sDestinationFolder . $oShopItem->image_large);
						} catch (Exception $e) {}
					}

					// Удаляем старое малое изображение
					if ($oShopItem->image_small != '' && is_file($sDestinationFolder . $oShopItem->image_small))
					{
						try
						{
							Core_File::delete($sDestinationFolder . $oShopItem->image_small);
						} catch (Exception $e) {}
					}
				}

				clearstatcache();

				// Удаляем папку назначения вместе со всеми старыми файлами
				//Core_File::deleteDir($sDestinationFolder);

				// Создаем папку назначения
				$oShopItem->createDir();

				// Файл-источник
				$sSourceFile = CMS_FOLDER . $this->sPicturesPath . ltrim($PictureData, '/\\');

				$sSourceFileBaseName = basename($PictureData);

				if (!$oShop->change_filename)
				{
					$sTargetFileName = $sSourceFileBaseName;
				}
				else
				{
					$sTargetFileExtension = Core_File::getExtension($PictureData);

					if ($sTargetFileExtension != '')
					{
						$sTargetFileExtension = ".{$sTargetFileExtension}";
					}

					if (!$bFirstPicture)
					{
						$sTargetFileName = "shop_property_file_{$oShopItem->id}_{$oProperty_Value->id}{$sTargetFileExtension}";
					}
					else
					{
						$sTargetFileName = "shop_items_catalog_image{$oShopItem->id}{$sTargetFileExtension}";
					}
				}

				$aPicturesParam = array();
				$aPicturesParam['large_image_isset'] = TRUE;
				$aPicturesParam['large_image_source'] = $sSourceFile;
				$aPicturesParam['large_image_name'] = $sSourceFileBaseName;
				$aPicturesParam['large_image_target'] = $sDestinationFolder . $sTargetFileName;
				$aPicturesParam['watermark_file_path'] = $oShop->getWatermarkFilePath();
				$aPicturesParam['watermark_position_x'] = $oShop->watermark_default_position_x;
				$aPicturesParam['watermark_position_y'] = $oShop->watermark_default_position_y;
				$aPicturesParam['large_image_preserve_aspect_ratio'] = $oShop->preserve_aspect_ratio;
				$aPicturesParam['small_image_source'] = $aPicturesParam['large_image_source'];
				$aPicturesParam['small_image_name'] = $aPicturesParam['large_image_name'];
				$aPicturesParam['small_image_target'] = $sDestinationFolder . "small_{$sTargetFileName}";
				$aPicturesParam['create_small_image_from_large'] = TRUE;

				if (!$bFirstPicture)
				{
					$aPicturesParam['large_image_max_width'] = $oProperty->image_large_max_width;
					$aPicturesParam['large_image_max_height'] = $oProperty->image_large_max_height;
					$aPicturesParam['small_image_max_width'] = $oProperty->image_small_max_width;
					$aPicturesParam['small_image_max_height'] = $oProperty->image_small_max_height;
				}
				else
				{
					$aPicturesParam['large_image_max_width'] = $oShop->image_large_max_width;
					$aPicturesParam['large_image_max_height'] = $oShop->image_large_max_height;
					$aPicturesParam['small_image_max_width'] = $oShop->image_small_max_width;
					$aPicturesParam['small_image_max_height'] = $oShop->image_small_max_height;
				}

				$aPicturesParam['small_image_watermark'] = $oShop->watermark_default_use_small_image;
				$aPicturesParam['small_image_preserve_aspect_ratio'] = $oShop->preserve_aspect_ratio_small;

				$aPicturesParam['large_image_watermark'] = $oShop->watermark_default_use_large_image;

				try
				{
					$result = Core_File::adminUpload($aPicturesParam);
				}
				catch (Exception $exc)
				{
					$result = array('large_image' => FALSE, 'small_image' => FALSE);
				}

				if ($result['large_image'])
				{
					if (!$bFirstPicture)
					{
						$oProperty_Value->file = $sTargetFileName;
						$oProperty_Value->file_name = $sFileName;
						$oProperty_Value->file_description = $sFileDescription;
						$oProperty_Value->save();
					}
					else
					{
						$oShopItem->image_large = $sTargetFileName;
						$oShopItem->setLargeImageSizes();
					}
				}

				if ($result['small_image'])
				{
					if (!$bFirstPicture)
					{
						$oProperty_Value->file_small = "small_{$sTargetFileName}";
						$oProperty_Value->file_small_name = '';
						$oProperty_Value->save();
					}
					else
					{
						$oShopItem->image_small = "small_{$sTargetFileName}";
						$oShopItem->setSmallImageSizes();
					}
				}

				$oShopItem->save() && $bFirstPicture = FALSE;
			}
		}

		return $this;
	}

	/**
	 * Import properties
	 * @param Shop_Item_Model $oItem item
	 * @param SimpleXMLElement $oProperty
	 */
	protected function _importProperties(Shop_Item_Model $oItem, SimpleXMLElement $oProperty)
	{
		$sValue = strval($oProperty->Значение);

		$sPropertyGUID = strval($oProperty->Ид);

		if (isset($this->aBaseProperties[$sPropertyGUID]))
		{
			$sPropertyValue = isset($this->_aPropertyValues[$sValue])
				? $this->_aPropertyValues[$sValue]
				: $sValue;

			switch (mb_strtoupper($this->aBaseProperties[$sPropertyGUID]))
			{
				case 'HOSTCMS_TITLE':
					$oItem->seo_title = $sPropertyValue;
				break;
				case 'HOSTCMS_DESCRIPTION':
					$oItem->seo_description = $sPropertyValue;
				break;
				case 'HOSTCMS_KEYWORDS':
					$oItem->seo_keywords = $sPropertyValue;
				break;
				case 'HOSTCMS_МЕТКИ':
					$oItem->applyTags($sPropertyValue);
				break;
				case 'YANDEX_MARKET':
					$oItem->yandex_market = $sPropertyValue;
				break;
				case 'ПРОДАВЕЦ':
					$oSeller = Core_Entity::factory('Shop', $this->iShopId)->Shop_Sellers->getByName($sPropertyValue, FALSE);

					if (is_null($oSeller))
					{
						$oSeller = Core_Entity::factory('Shop_Seller');
						$oSeller->shop_id($this->iShopId)->name($sPropertyValue)->path(Core_Guid::get())->save();
					}

					$oItem->shop_seller_id = $oSeller->id;
				break;
				case 'ПРОИЗВОДИТЕЛЬ':

					if (trim($sPropertyValue) != '')
					{
						$oProducer = Core_Entity::factory('Shop', $this->iShopId)->Shop_Producers->getByName($sPropertyValue, FALSE);

						if (is_null($oProducer))
						{
							$oProducer = Core_Entity::factory('Shop_Producer');
							$oProducer->shop_id($this->iShopId)->name($sPropertyValue)->save();
						}

						$oItem->shop_producer_id = $oProducer->id;

						if ($oItem->modification_id)
						{
							$oItem->Modification->shop_producer_id = $oProducer->id;
							$oItem->Modification->save();
						}
					}
				break;
				case 'АКТИВНОСТЬ':
					$oItem->active = $sPropertyValue;
				break;
			}

			$oItem->save();
		}
		elseif ($sValue != '')
		{
			/*$oShop_Item_Property_List = Core_Entity::factory('Shop_Item_Property_List', $this->iShopId);
			$oProperty = $oShop_Item_Property_List->Properties->getByGuid($sPropertyGUID, FALSE);

			if (!is_null($oProperty))
			{
				$aPropertyValues = $oProperty->getValues($oItem->id, FALSE);

				$oProperty_Value = isset($aPropertyValues[0])
					? $aPropertyValues[0]
					: $oProperty->createNewValue($oItem->id);

				$value = isset($this->_aPropertyValues[$sPropertyValue])
					? $this->_aPropertyValues[$sPropertyValue]
					: $sPropertyValue;

				$this->_setPropertyValue($oProperty_Value, $value);
			}*/
			$this->_addItemProperty($oItem, $sPropertyGUID, $sValue);
		}
	}

	/**
	 * Определяет namespace документа, если был указан, то устаналивает его и возвращает $object->xpath($path) с учетом namespace
	 * @param SimpleXMLElement $object
	 * @param string $path
	 * @return array|false
	 */
	public function xpath(SimpleXMLElement $object, $path)
	{
		if ($this->namespace)
		{
			$object->registerXPathNamespace('w', $this->namespace);
			$sXmlns = 'w:';

			// namespace указываем перед каждым элементом xpath
			$aExplode = explode('/', $path);
			foreach ($aExplode as $key => $value)
			{
				$aExplode[$key] = $sXmlns . $value;
			}

			$sOriginalPath = $path;

			$path = implode('/', $aExplode);
		}

		$return = $object->xpath($path);

		if ($this->namespace && ($return === FALSE || !count($return)))
		{
			$return = $object->xpath($sOriginalPath);
		}

		!is_array($return) && $return = array();

		return $return;
	}

	/**
	 * Start import
	 * @return array
	 * @hostcms-event Shop_Item_Import_Cml_Controller.onBeforeImport
	 * @hostcms-event Shop_Item_Import_Cml_Controller.onAfterImport
	 */
	public function import()
	{
		Core_Event::notify('Shop_Item_Import_Cml_Controller.onBeforeImport', $this);

		if (is_null($this->iShopId))
		{
			throw new Core_Exception(Core::_('Shop_Item.error_shop_id'));
		}

		if (is_null($this->iShopGroupId))
		{
			throw new Core_Exception(Core::_('Shop_Item.error_parent_directory'));
		}

		/*
		 Удаляем товары/группы только при получении import.xml
		*/
		if ($this->importAction == 0
			&& count((array)$this->_oSimpleXMLElement->Классификатор)
			&& count((array)$this->_oSimpleXMLElement->ПакетПредложений) == 0)
		{
			Core_QueryBuilder::update('shop_groups')
				->set('deleted', 1)
				->where('shop_id', '=', $this->iShopId)
				->execute();

			Core_QueryBuilder::update('shop_items')
				->set('deleted', 1)
				->where('shop_id', '=', $this->iShopId)
				->execute();
		}

		$oShop = Core_Entity::factory('Shop', $this->iShopId);

		$aNamespaces = $this->_oSimpleXMLElement->getNamespaces(true);
		if (count($aNamespaces))
		{
			list(, $this->namespace) = each($aNamespaces);
		}

		// Проверяем, есть ли файл с дополнительными свойствами для модификаций, если есть - извлекаем данные
		if (is_file($this->_temporaryPropertyFile))
		{
			$aModificationProperties = unserialize(file_get_contents($this->_temporaryPropertyFile));
		}
		else
		{
			$aModificationProperties = array();
		}

		// Файл import.xml
		if (count((array)$this->_oSimpleXMLElement->Классификатор) && count((array)$this->_oSimpleXMLElement->ПакетПредложений) == 0)
		{
			// Удаляем временный файл с дополнительными свойствами для модификаций
			is_file($this->_temporaryPropertyFile) && Core_File::delete($this->_temporaryPropertyFile);

			$classifier = $this->_oSimpleXMLElement->Классификатор;

			//print_r($classifier->xpath($sXmlns . 'Группы'));
			//print_r($this->xpath($classifier, 'Группы'));
			//print_r($this->xpath($this->_oSimpleXMLElement->Каталог, 'Товары/Товар'));

			// Импортируем группы товаров
			foreach ($this->xpath($classifier, 'Группы') as $Groups)
			{
				$this->_importGroups($Groups, $this->iShopGroupId);
			}
			//is_object($classifier->Группы) && $this->_importGroups($classifier->Группы, $this->iShopGroupId);

			// Импортируем дополнительные свойства товаров
			foreach ($this->xpath($classifier, 'Свойства/Свойство') as $oItemProperty)
			{
				$sPropertyName = strval($oItemProperty->Наименование);

				foreach ($this->xpath($oItemProperty, 'ВариантыЗначений/Справочник') as $oValue)
				{
					$this->_aPropertyValues[strval($oValue->ИдЗначения)] = strval($oValue->Значение);
				}

				if (in_array(mb_strtoupper($sPropertyName), $this->aPredefinedBaseProperties))
				{
					// Основное свойство товара
					$this->aBaseProperties[strval($oItemProperty->Ид)] = strval($oItemProperty->Наименование);
				}
				else
				{
					$this->aAdditionalProperties[strval($oItemProperty->Ид)] = strval($oItemProperty->Наименование);
				}
			}

			foreach ($this->xpath($this->_oSimpleXMLElement->Каталог, 'Товары/Товар') as $oItem)
			{
				$sGUID = strval($oItem->Ид);
				$sGUIDmod = FALSE;

				if (strpos($sGUID, '#') !== FALSE)
				{
					$sTmp = explode('#', $sGUID);
					$sGUID = $sTmp[0];
					$sGUIDmod = $sTmp[1];
				}

				$oShopItem = $oShop->Shop_Items->getByGuid($sGUID, FALSE);

				$sItemName = strval($oItem->Наименование);

				if (is_null($oShopItem))
				{
					// Создаем товар
					$oShopItem = Core_Entity::factory('Shop_Item')->guid($sGUID);
					// Минимально необходимы данные
					$oShopItem->name = $sItemName;
					$oShopItem->path = '';
					$oShopItem->shop_id($this->iShopId)->save();
					$this->_aReturn['insertItemCount']++;
				}
				else
				{
					$this->_aReturn['updateItemCount']++;
				}

				// Если передан GUID модификации и товар, для которого загружается модификация, уже существует
				if (strlen($sGUIDmod) && $oShopItem->id)
				{
					$oModificationItem = $oShopItem->Modifications->getByGuid($sGUIDmod, FALSE);

					// Модификация у товара не найдена, создаем ее
					if (is_null($oModificationItem))
					{
						// Если товар - модификация, оставляем лишь базовые данные, название и идентификатор магазина/группы товаров
						$oModificationItem = Core_Entity::factory('Shop_Item')
							->guid($sGUIDmod)
							->modification_id($oShopItem->id)
							->shop_id($oShop->id)
							->shop_group_id(0)
							->save();
					}

					// Подменяем товар на модификацию
					$oShopItem = $oModificationItem;
				}

				$oShopItem->marking = strval($oItem->Артикул);
					/*? strval($oItem->Артикул)
					: (strlen(strval($oItem->Штрихкод))
						? strval($oItem->Штрихкод)
						: ''
					);*/

				$oShopItem->name = $sItemName;

				if (is_array($aTmp = $this->xpath($oItem, 'Группы'))
				&& count($aTmp) > 0
				&& !is_null($oShop_Group = $oShop->Shop_Groups->getByGuid(strval($aTmp[0]->Ид), FALSE)))
				{
					// Группа указана в файле и существует в магазине
					$sGUIDmod === FALSE
						? $oShopItem->shop_group_id = $oShop_Group->id
							: $oShopItem->Modification->shop_group_id($oShop_Group->id)->save();
				}
				else
				{
					// Группа не указана в файле, размещаем в корне (iShopGroupId)
					$sGUIDmod === FALSE
						? $oShopItem->shop_group_id = $this->iShopGroupId
						: $oShopItem->Modification->shop_group_id($this->iShopGroupId)->save();
				}

				$oShopItem->shop_id = $this->iShopId;

				// check item path
				$oShopItem->path == '' && $oShopItem->makePath();

				$oSameShopItem = Core_Entity::factory('Shop', $this->iShopId)
					->Shop_Items
					->getByGroupIdAndPath($oShopItem->shop_group_id, $oShopItem->path);

				if (!is_null($oSameShopItem) && $oSameShopItem->id != $oShopItem->id)
				{
					$oShopItem->path = Core_Guid::get();
				}
				else
				{
					$oSameShopGroup = Core_Entity::factory('Shop', $this->iShopId)->Shop_Groups->getByParentIdAndPath($oShopItem->shop_group_id, $oShopItem->path);

					if (!is_null($oSameShopGroup))
					{
						$oShopItem->path = Core_Guid::get();
					}
				}

				if ($oShopItem->id && $this->importAction == 1 && !is_null($oShopItem->name))
				{
					$oShopItem->save();
				}
				elseif (!is_null($oShopItem->name))
				{
					is_null($oShopItem->path) && $oShopItem->path = '';

					$oShopItem->save();
				}
				else
				{
					throw new Core_Exception(Core::_('Shop_Item.error_save_without_name'));
				}

				// Обрабатываем описание товара
				foreach ($this->xpath($oItem, 'Описание') as $DescriptionData)
				{
					if ($this->itemDescription == 'text')
					{
						$oShopItem->text = nl2br(strval($DescriptionData));
					}
					elseif ($this->itemDescription == 'description')
					{
						$oShopItem->description = nl2br(strval($DescriptionData));
					}
					$oShopItem->save();
				}

				// Обрабатываем "малое описание" товара. Данный тег не соответствует стандарту CommerceML!
				foreach ($this->xpath($oItem, 'МалоеОписание') as $DescriptionData)
				{
					$oShopItem->description = nl2br(strval($DescriptionData));
					$oShopItem->save();
				}

				// Картинки основного товара
				$this->_importImages($oShopItem, $this->xpath($oItem, 'Картинка'));

				// До обработки свойств из 1С нужно записать значения "по умолчанию" для всех свойств, заданных данной группе товара
				$aProperties = Core_Entity::factory('Shop_Item_Property_List', $oShop->id)->Properties;
				$aProperties
					->queryBuilder()
					->join('shop_item_property_for_groups', 'shop_item_property_for_groups.shop_item_property_id', '=', 'shop_item_properties.id')
					->where('shop_item_property_for_groups.shop_id', '=', $oShop->id)
					->where('shop_item_property_for_groups.shop_group_id', '=', $oShopItem->Shop_Group->id);

				$aProperties = $aProperties->findAll(FALSE);

				foreach($aProperties as $oProperty)
				{
					if ($oProperty->type==2)
					{
						continue;
					}

					$aPropertyValues = $oProperty->getValues($oShopItem->id, FALSE);

					if (count($aPropertyValues) == 0)
					{
						$oProperty_Value = $oProperty->createNewValue($oShopItem->id);
						$oProperty_Value->setValue($oProperty->default_value);
						$oProperty_Value->save();
					}
				}

				// Добавляем значения для общих свойств всех товаров
				foreach ($this->xpath($oItem, 'ЗначенияСвойств/ЗначенияСвойства') as $ItemPropertyValue)
				{
					/*if (isset($this->aAdditionalProperties[$sPropertyGUID = strval($ItemPropertyValue->Ид)]))
					{
						$this->_addItemProperty($oShopItem, $sPropertyGUID, strval($ItemPropertyValue->Значение));
					}*/
					$this->_importProperties($oShopItem, $ItemPropertyValue);
				}

				foreach ($this->xpath($oItem, 'ХарактеристикиТовара/ХарактеристикаТовара')
						  as $oItemProperty)
				{
					$this->_addCharacteristic($oShopItem, $oItemProperty);
				}

				// Сохраняем список характеристик модификаций во временный файл
				if (count($aModificationProperties))
				{
					file_put_contents($this->_temporaryPropertyFile, serialize($aModificationProperties));
				}

				foreach ($this->xpath($oItem, 'ЗначенияРеквизитов/ЗначениеРеквизита')
						  as $oItemProperty)
				{
					$this->_addPredefinedAdditionalProperty($oShopItem, strval($oItemProperty->Наименование), strval($oItemProperty->Значение));
				}

				// Обрабатываем свойства/значения свойств для конкретно данного товара
				/*foreach ($this->xpath($oItem, 'ХарактеристикиТовара/ХарактеристикаТовара or ЗначенияРеквизитов/ЗначениеРеквизита')
						  as $oItemProperty)
				{
					if (mb_strtoupper(strval($oItemProperty->Наименование)) == 'ВЕС')
					{
						$oShopItem->weight = Shop_Controller::instance()->convertPrice(strval($oItemProperty->Значение));
						$oShopItem->save();

						continue;
					}
					elseif ($oItemProperty->getName() == 'ЗначениеРеквизита')
					{
						continue;
					}

					$this->_addItemProperty($oShopItem, strval($oItemProperty->Наименование), strval($oItemProperty->Значение));
				}*/

				// Обрабатываем налоги
				foreach ($this->xpath($oItem, 'СтавкиНалогов/СтавкаНалога') as $oTax)
				{
					$oShopTax = $this->_addTax($oTax);
					$oShopItem->shop_tax_id = $oShopTax->id;
					$oShopItem->save();
				}
			}
		}
		// Файл offers.xml
		elseif (count((array)$this->_oSimpleXMLElement->ПакетПредложений) && count((array)$this->_oSimpleXMLElement->Каталог) == 0)
		{
			$packageOfProposals = $this->_oSimpleXMLElement->ПакетПредложений;

			// Обработка специальных цен
			foreach ($this->xpath($packageOfProposals, 'ТипыЦен/ТипЦены') as $oPrice)
			{
				$oShopPrice = Core_Entity::factory('Shop', $this->iShopId)->Shop_Prices->getByGuid(strval($oPrice->Ид), FALSE);

				if (is_null($oShopPrice))
				{
					$oShopPrice = Core_Entity::factory('Shop_Price');
					$oShopPrice->shop_id = $this->iShopId;
					$oShopPrice->guid = strval($oPrice->Ид);
					$oShopPrice->percent = 100;
				}

				$oShopPrice->name = strval($oPrice->Наименование);

				// Если это основная цена, обновляем информацию о налоге
				if (mb_strtoupper($oShopPrice->name) == mb_strtoupper($this->sShopDefaultPriceName))
				{

					$sTaxGUID = md5(mb_strtoupper($oPrice->Налог->Наименование));
					$oShopTax = Core_Entity::factory('Shop_Tax')->getByGuid($sTaxGUID, FALSE);

					// код введён в эксплуатацию 10.06.2015 для совместимости с МойСклад
					if (!is_null($oShopTax))
					{
						// В связи с разницей логик HostCMS и 1С по хранению налогов, поле "учтено в сумме" больше не будет импортироваться
						$iInSum = strval($oPrice->Налог->УчтеноВСумме);
						strtoupper($iInSum) == 'TRUE' ? $oShopTax->tax_is_included = 1 : $oShopTax->tax_is_included = 0;
						$this->_oTaxForBasePrice = $oShopTax->save();
					}

					$this->sShopDefaultPriceGUID = $oShopPrice->guid;
				}
				else
				{
					$oShopPrice->save();
				}
			}

			// Обработка складов
			foreach ($this->xpath($packageOfProposals, 'Склады/Склад') as $oWarehouse)
			{
				$sWarehouseGuid = strval($oWarehouse->Ид);

				$oShopWarehouse = Core_Entity::factory('Shop', $this->iShopId)
					->Shop_Warehouses
					->getByGuid($sWarehouseGuid, FALSE);

				if (is_null($oShopWarehouse))
				{
					$oShopWarehouse = Core_Entity::factory('Shop_Warehouse');
					$oShopWarehouse->shop_id = $this->iShopId;
					$oShopWarehouse->guid = $sWarehouseGuid;
					$oShopWarehouse->name = strval($oWarehouse->Наименование);
					$oShopWarehouse->address = strval($oWarehouse->Адрес->Представление);
					$oShopWarehouse->save();
				}

				/*foreach ($this->xpath($oWarehouse, 'Адрес/АдресноеПоле') as $oWarehouseAddressField)
				{
					//echo "Адресное поле: " . strval($oWarehouseAddressField->Тип) . " - " . strval($oWarehouseAddressField->Значение) . "<br/>";
				}*/
			}

			// Обработка предложений
			foreach ($this->xpath($packageOfProposals, 'Предложения/Предложение') as $oProposal)
			{
				$sItemGUID = strval($oProposal->Ид);

				$sGUIDmod = FALSE;
				if (strpos($sItemGUID, '#') !== FALSE)
				{
					$aItemGUID = explode('#', $sItemGUID);
					$sItemGUID = $aItemGUID[0];
					$sGUIDmod = $aItemGUID[1];
				}

				// Основной товар (не модификация)
				$oShopItem = Core_Entity::factory('Shop', $this->iShopId)->Shop_Items->getByGuid($sItemGUID, FALSE);

				if (!is_null($oShopItem))
				{
					// Если передан GUID модификации
					if (strlen($sGUIDmod))
					{
						$oModificationItem = $oShopItem->Modifications->getByGuid($sGUIDmod, FALSE);

						// Модификация у товара не найдена, создаем ее
						if (is_null($oModificationItem))
						{
							$oModificationItem = Core_Entity::factory('Shop_Item')
								->guid($sGUIDmod)
								->modification_id($oShopItem->id)
								->shop_id($this->iShopId)
								->shop_group_id(0)
								->save();
						}

						// Подменяем товар на модификацию
						$oShopItem = $oModificationItem;

						// извлекаем список допсвойств для данной модификации
						if (isset($aModificationProperties[$oShopItem->guid]) && is_array($aModificationProperties[$oShopItem->guid]))
						{
							foreach($aModificationProperties[$oShopItem->guid] as $aModificationPropertyData)
							{
								$this->_addPredefinedAdditionalProperty($oShopItem, $aModificationPropertyData['name'], $aModificationPropertyData['value'], TRUE);
							}
						}
					}

					// Товар найден, начинаем обновление
					//$oShopItem->marking = strval($oProposal->Артикул);
					$oShopItem->name = strval($oProposal->Наименование);

					$sMeasure = strval($oProposal->БазоваяЕдиница);
					if (strlen($sMeasure))
					{
						$oShopMeasure = Core_Entity::factory('Shop_Measure')->getByName($sMeasure, FALSE);

						if (is_null($oShopMeasure))
						{
							$oShopMeasure = Core_Entity::factory('Shop_Measure');
							$oShopMeasure->name = strval($sMeasure);
							$oShopMeasure->description = strval($oProposal->БазоваяЕдиница->attributes()->НаименованиеПолное);
							$oShopMeasure->save();
						}

						$oShopItem->shop_measure_id = $oShopMeasure->id;
					}

					// Картинки предложений (модификаций)
					$this->_importImages($oShopItem, $this->xpath($oProposal, 'Картинка'));

					// Добавляем значения для общих свойств всех товаров
					foreach ($this->xpath($oProposal, 'ЗначенияСвойств/ЗначенияСвойства') as $ItemPropertyValue)
					{
						$this->_importProperties($oShopItem, $ItemPropertyValue);
					}

					// Обработка характеристик товара из файла offers для совместимости с МойСклад
					foreach ($this->xpath($oProposal, 'ХарактеристикиТовара/ХарактеристикаТовара')
						  as $oItemProperty)
					{
						$this->_addCharacteristic($oShopItem, $oItemProperty);
					}

					foreach ($this->xpath($oProposal, 'Цены/Цена') as $oPrice)
					{
						// Ищем цену
						$oShopPrice = Core_Entity::factory('Shop', $this->iShopId)->Shop_Prices->getByGuid(strval($oPrice->ИдТипаЦены), FALSE);

						if (!is_null($oShopPrice) && $this->sShopDefaultPriceGUID != strval($oPrice->ИдТипаЦены))
						{
							$oShop_Item_Price = Core_Entity::factory('Shop_Item', $oShopItem->id)->Shop_Item_Prices->getByPriceId($oShopPrice->id, FALSE);

							if (is_null($oShop_Item_Price))
							{
								$oShop_Item_Price = Core_Entity::factory('Shop_Item_Price');
								$oShop_Item_Price->shop_item_id = $oShopItem->id;
								$oShop_Item_Price->shop_price_id = $oShopPrice->id;
							}

							$itemPrice = strval($oPrice->ЦенаЗаЕдиницу);

							// Валюта товара
							$baseCurrencyNode = $this->xpath($oProposal, "Цены/Цена[ИдТипаЦены='{$this->sShopDefaultPriceGUID}']");

							if (isset($baseCurrencyNode[0]))
							{
								$sCurrency = strval($baseCurrencyNode[0]->Валюта);

								if (is_numeric($sCurrency) && isset($this->_aCurrencyCodes[$sCurrency]))
								{
									$sCurrency = $this->_aCurrencyCodes[$sCurrency];
								}

								$oItem_Shop_Currency = Core_Entity::factory('Shop_Currency')->getByLike($sCurrency, FALSE);

								$sCurrency = strval($oPrice->Валюта);

								if (is_numeric($sCurrency) && isset($this->_aCurrencyCodes[$sCurrency]))
								{
									$sCurrency = $this->_aCurrencyCodes[$sCurrency];
								}

								// Валюта спеццены
								$oPrice_Currency = Core_Entity::factory('Shop_Currency')->getByLike($sCurrency, FALSE);

								if (!is_null($oItem_Shop_Currency)
									&& !is_null($oPrice_Currency)
									&& $oItem_Shop_Currency->exchange_rate
									&& $oPrice_Currency->exchange_rate)
								{
									$currencyCoefficient = Shop_Controller::instance()->getCurrencyCoefficientInShopCurrency($oPrice_Currency, $oItem_Shop_Currency);

									$itemPrice *= $currencyCoefficient;
								}
							}

							$oShop_Item_Price->value = $itemPrice;

							$oShopItem->add($oShop_Item_Price);
						}
						elseif ($this->sShopDefaultPriceGUID == strval($oPrice->ИдТипаЦены))
						{
							$sCurrency = strval($oPrice->Валюта);
							if (is_numeric($sCurrency) && isset($this->_aCurrencyCodes[$sCurrency]))
							{
								$sCurrency = $this->_aCurrencyCodes[$sCurrency];
							}

							$oShop_Currency = Core_Entity::factory('Shop_Currency')->getByLike($sCurrency, FALSE);

							if (is_null($oShop_Currency))
							{
								$oShop_Currency = Core_Entity::factory('Shop_Currency');
								$oShop_Currency->name = $sCurrency;
								$oShop_Currency->code = $sCurrency;
								$oShop_Currency->exchange_rate = 1;
							}

							$oShopItem->price = Shop_Controller::instance()->convertPrice(strval($oPrice->ЦенаЗаЕдиницу));
							$oShopItem->add($oShop_Currency);

							if (!is_null($this->_oTaxForBasePrice))
							{
								$oShopItem->add($this->_oTaxForBasePrice);
								$this->_oTaxForBasePrice = NULL;
							}

							if (($sMeasureName = strval($oPrice->Единица)) != '')
							{
								if (is_null($oShop_Measure = Core_Entity::factory('Shop_Measure')->getByName($sMeasureName, FALSE)))
								{
									$oShop_Measure = Core_Entity::factory('Shop_Measure')->name($sMeasureName)->save();
								}
								$oShopItem->add($oShop_Measure);
							}
						}
					}

					$aWarehouses = $this->xpath($oProposal, 'Склад');

					// Явно переданы остатки по каждому складу
					if (count($aWarehouses))
					{
						// склады
						foreach ($aWarehouses as $oWarehouseCount)
						{
							$sWarehouseGuid = strval($oWarehouseCount['ИдСклада']);
							$sWarehouseCount = strval($oWarehouseCount['КоличествоНаСкладе']);

							$oShopWarehouse = Core_Entity::factory('Shop', $this->iShopId)->Shop_Warehouses->getByGuid($sWarehouseGuid, FALSE);
							if (!is_null($oShopWarehouse))
							{
								$oShop_Warehouse_Item = $oShopWarehouse->Shop_Warehouse_Items->getByShopItemId($oShopItem->id, FALSE);
								if (is_null($oShop_Warehouse_Item))
								{
									$oShop_Warehouse_Item = Core_Entity::factory('Shop_Warehouse_Item')
										->shop_warehouse_id($oShopWarehouse->id)
										->shop_item_id($oShopItem->id);
								}
								$oShop_Warehouse_Item->count(floatval($sWarehouseCount))->save();
							}
						}
					}
					// Общее количество на складе по умолчанию
					else
					{
						$iItemCount = 0;

						foreach ($this->xpath($oProposal, 'Количество') as $oCount)
						{
							$iItemCount = $oCount;
						}

						// если нет тега "Количество", ставим количество товара на главном складе равным нулю
						// Ищем главный склад
						$oWarehouse = Core_Entity::factory('Shop', $this->iShopId)->Shop_Warehouses->getByDefault("1", FALSE);

						if (is_null($oWarehouse))
						{
							// Склад не обнаружен
							$oWarehouse = Core_Entity::factory('Shop_Warehouse');
							$oWarehouse->name = Core::_("Shop_Warehouse.warehouse_default_name");
							$oWarehouse->active = 1;
							$oWarehouse->default = 1;
							$oWarehouse->shop_id = $this->iShopId;
							$oWarehouse->save();
						}

						$oShop_Warehouse_Item = $oWarehouse->Shop_Warehouse_Items->getByShopItemId($oShopItem->id, FALSE);

						if (is_null($oShop_Warehouse_Item))
						{
							$oShop_Warehouse_Item = Core_Entity::factory('Shop_Warehouse_Item')
								->shop_warehouse_id($oWarehouse->id)
								->shop_item_id($oShopItem->id);
						}

						$oShop_Warehouse_Item->count(floatval($iItemCount))->save();
					}

					$oShopItem->save();
					$this->_aReturn['updateItemCount']++;
				}
			}
		}
		// Файл 1C v.7.xx
		elseif (count((array)$this->_oSimpleXMLElement->Каталог))
		{
			$catalog = $this->_oSimpleXMLElement->Каталог;
			foreach ($catalog->xpath('Свойство') as $oXmlProperty)
			{
				$oShop_Item_Property_List = Core_Entity::factory('Shop_Item_Property_List', $this->iShopId);
				$oShopProperty = $oShop_Item_Property_List->Properties->getByGuid(strval($oXmlProperty->attributes()->Идентификатор), FALSE);

				if (is_null($oShopProperty))
				{
					$oProperty = Core_Entity::factory('Property');
					$oProperty->name = strval($oXmlProperty->attributes()->Наименование);
					$oProperty->type = 1;
					$oProperty->tag_name = Core_Str::transliteration(strval($oXmlProperty->attributes()->Наименование));
					$oProperty->guid = strval($oXmlProperty->attributes()->Идентификатор);
					$oShop_Item_Property_List->add($oProperty);
				}
			}

			$aGroupList = array();
			$aGroupListTree = array();
			foreach ($catalog->xpath('Группа') as $oXmlGroup)
			{
				$sParentGUID = strval($oXmlGroup->attributes()->Родитель) == '' ? 0 : strval($oXmlGroup->attributes()->Родитель);
				$aGroupList[strval($oXmlGroup->attributes()->Идентификатор)] = $oXmlGroup;
				$aGroupListTree[$sParentGUID][] = strval($oXmlGroup->attributes()->Идентификатор);
			}
			$aStack = array(0 => 0);
			while (count($aStack) > 0)
			{
				$sStackEnd = end($aStack);
				unset($aStack[count($aStack) - 1]);
				if (isset($aGroupListTree[$sStackEnd]))
				{
					foreach ($aGroupListTree[$sStackEnd] as $sGroupGUID)
					{
						$oShopGroup = Core_Entity::factory('Shop', $this->iShopId)->Shop_Groups->getByGuid($sGroupGUID, FALSE);
						if (is_null($oShopGroup))
						{
							$oShopGroup = Core_Entity::factory('Shop_Group');
							$oShopGroup->guid = strval($aGroupList[$sGroupGUID]->attributes()->Идентификатор);
							$oShopGroup->shop_id = $this->iShopId;
							$this->_aReturn['insertDirCount']++;
						}
						else
						{
							$this->_aReturn['updateDirCount']++;
						}
						is_null($oShopGroup->path) && $oShopGroup->path= '';
						$oShopGroup->name = strval($aGroupList[$sGroupGUID]->attributes()->Наименование);
						$oShopGroup->parent_id = $sStackEnd === 0 ? 0 : Core_Entity::factory('Shop', $this->iShopId)->Shop_Groups->getByGuid($sStackEnd, FALSE)->id;
						$oShopGroup->save();
						$aStack[count($aStack)] = $sGroupGUID;
					}
				}
			}

			foreach ($catalog->xpath('Товар') as $oXmlItem)
			{
				$oShopItem = $oShop->Shop_Items->getByGuid(strval($oXmlItem->attributes()->Идентификатор), FALSE);

				if (is_null($oShopItem))
				{
					// Создаем товар
					$oShopItem = Core_Entity::factory('Shop_Item')->guid(strval($oXmlItem->attributes()->Идентификатор));
					$oShopItem->shop_id = $this->iShopId;
					$oShopItem->guid = strval($oXmlItem->attributes()->Идентификатор);
					$this->_aReturn['insertItemCount']++;
				}
				else
				{
					$this->_aReturn['updateItemCount']++;
				}
				is_null($oShopItem->path) && $oShopItem->path='';
				$oShopItem->name = strval($oXmlItem->attributes()->Наименование);
				$oShopItem->marking = strval($oXmlItem->attributes()->ИдентификаторВКаталоге);
				$oShopGroup = Core_Entity::factory('Shop', $this->iShopId)->Shop_Groups->getByGuid(strval($oXmlItem->attributes()->Родитель), FALSE);
				if (is_null($oShopGroup))
				{
					$oShopGroup = Core_Entity::factory('Shop_Group', 0);
				}
				$oShopItem->shop_group_id = $oShopGroup->id;
				$oShopMeasure = Core_Entity::factory('Shop_Measure')->getByName(strval($oXmlItem->attributes()->Единица), FALSE);
				if (is_null($oShopMeasure))
				{
					$oShopMeasure = Core_Entity::factory('Shop_Measure', 0);
				}
				$oShopItem->shop_measure_id = $oShopMeasure->id;
				$oShopItem->save();

				foreach ($oXmlItem->xpath('ЗначениеСвойства') as $oXmlPropertyValue)
				{
					$oShop_Item_Property_List = Core_Entity::factory('Shop_Item_Property_List', $this->iShopId);
					$oShopProperty = $oShop_Item_Property_List->Properties->getByGuid(strval($oXmlPropertyValue->attributes()->ИдентификаторСвойства), FALSE);

					if (!is_null($oShopProperty) && $oShopProperty->type != 2 && $oShopProperty->type != 3)
					{
						if (is_null(Core_Entity::factory('Shop', $this->iShopId)
							->Shop_Item_Property_For_Groups
							->getByShopItemPropertyIdAndGroupId($oShopProperty->Shop_Item_Property->id, $oShopItem->shop_group_id)))
						{
							Core_Entity::factory('Shop_Item_Property_For_Group')
								->shop_group_id($oShopItem->shop_group_id)
								->shop_item_property_id($oShopProperty->Shop_Item_Property->id)
								->shop_id($this->iShopId)
								->save();
						}

						$aPropertyValues = $oShopProperty->getValues($oShopItem->id, FALSE);

						$oProperty_Value = isset($aPropertyValues[0])
							? $aPropertyValues[0]
							: $oShopProperty->createNewValue($oShopItem->id);

						$sValue = strval($oXmlPropertyValue->attributes()->Значение);
						$oProperty_Value->setValue($sValue);
						$oProperty_Value->save();
					}
				}
			}

			$offers = $this->_oSimpleXMLElement->ПакетПредложений;
			foreach ($offers->xpath('Предложение') as $oXmlOffer)
			{
				$oShopItem = $oShop->Shop_Items->getByGuid(strval($oXmlOffer->attributes()->ИдентификаторТовара), FALSE);

				if (!is_null($oShopItem))
				{
					$oShopItem->price = Shop_Controller::instance()->convertPrice(strval($oXmlOffer->attributes()->Цена));

					if (!is_null($oShop_Currency = Core_Entity::factory('Shop_Currency')->getByLike(strval($oXmlOffer->attributes()->Валюта), FALSE)))
					{
						$oShopItem->shop_currency_id = $oShop_Currency->id;
					}
					$oShopItem->save();
					$oWarehouse = Core_Entity::factory('Shop', $this->iShopId)->Shop_Warehouses->getByDefault("1", FALSE);

					if (!is_null($oWarehouse))
					{
						$oShop_Warehouse_Item = $oWarehouse->Shop_Warehouse_Items->getByShopItemId($oShopItem->id, FALSE);
						if (is_null($oShop_Warehouse_Item))
						{
							$oShop_Warehouse_Item = Core_Entity::factory('Shop_Warehouse_Item')
								->shop_warehouse_id($oWarehouse->id)
								->shop_item_id($oShopItem->id);
						}
						$oShop_Warehouse_Item->count(strval($oXmlOffer->attributes()->Количество))->save();
					}
				}
			}
		}

		// Пересчет количества товаров в группах
		$oShop->recount();

		Core_Event::notify('Shop_Item_Import_Cml_Controller.onAfterImport', $this);

		return $this->_aReturn;
	}

	/**
	 * Сохранение значения свойства заданного типа
	 * @param Property_Value_Model $oProperty_Value property value object
	 * @param string $value value
	 */
	protected function _setPropertyValue($oProperty_Value, $value)
	{
		$oProperty = $oProperty_Value->Property;

		switch ($oProperty->type)
		{
			// целое число
			case 0:
				$oProperty_Value->setValue(Shop_Controller::convertPrice($value));
			break;
			// Файл
			case 2:
			break;
			// Список
			case 3:
				if (Core::moduleIsActive('list') && $oProperty->list_id)
				{
					$oListItem = Core_Entity::factory('List', $oProperty->list_id)
						->List_Items
						->getByValue($value, FALSE);

					if (is_null($oListItem))
					{
						$oListItem = Core_Entity::factory('List_Item')
							->list_id($oProperty->list_id)
							->value($value)
							->save();
					}
					$oProperty_Value->setValue($oListItem->id);
				}
			break;
			case 7:
				$value = mb_strtolower($value);

				if ($value == 'true' || $value == 'да')
				{
					$value = 1;
				}
				elseif ($value == 'false' || $value == 'нет')
				{
					$value = 0;
				}
				else
				{
					$value = (boolean)$value === TRUE ? 1 : 0;
				}

				$oProperty_Value->setValue($value);
			break;
			case 8:
				if (!preg_match("/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $value))
				{
					$value = Core_Date::datetime2sql($value);
				}

				$oProperty_Value->setValue($value);
			break;
			case 9:
				if (!preg_match("/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $value))
				{
					$value = Core_Date::datetime2sql($value);
				}

				$oProperty_Value->setValue($value);
			break;
			default:
				$oProperty_Value->setValue(nl2br($value));
			break;
		}

		$oProperty_Value->save();
	}
}
