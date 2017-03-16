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
class Shop_Delivery_Model extends Core_Entity
{
	/**
	 * Backend property
	 * @var int
	 */
	var $conditions = 1;

	/**
	 * One-to-many or many-to-many relations
	 * @var array
	 */
	protected $_hasMany = array(
		'shop_delivery_condition' => array()
	);

	/**
	 * Belongs to relations
	 * @var array
	 */
	protected $_belongsTo = array(
		'shop' => array()
	);

	/**
	 * List of preloaded values
	 * @var array
	 */
	protected $_preloadValues = array(
		'sorting' => 0
	);

	/**
	 * Default sorting for models
	 * @var array
	 */
	protected $_sorting = array(
		'shop_deliveries.sorting' => 'ASC',
	);

	/**
	 * Constructor.
	 * @param int $id entity ID
	 */
	public function __construct($id = NULL)
	{
		parent::__construct($id);

		if (is_null($id))
		{
			$oUserCurrent = Core_Entity::factory('User', 0)->getCurrent();
			$this->_preloadValues['user_id'] = is_null($oUserCurrent) ? 0 : $oUserCurrent->id;
		}
	}

	/**
	 * Get the path to the delivery's image
	 * @return string
	 */
	public function getDeliveryFilePath()
	{
		return $this->getPath() . $this->image;
	}

	/**
	 * Get delivery file href
	 * @return string
	 */
	public function getDeliveryFileHref()
	{
		return $this->getHref() . rawurlencode($this->image);
	}

	/**
	 * Get delivery path
	 * @return string
	 */
	public function getPath()
	{
		return $this->Shop->getPath() . '/types_of_delivery/';
	}

	/**
	 * Get delivery href
	 * @return string
	 */
	public function getHref()
	{
		return '/' . $this->Shop->getHref()
		. '/types_of_delivery/';
	}

	/**
	 * Delete delivery image
	 */
	public function deleteImage()
	{
		try
		{
			Core_File::delete($this->getDeliveryFilePath());
		} catch (Exception $e) {}

		$this->image = '';
		$this->save();
	}

	/**
	 * Create directory for delivery files
	 * @return self
	 */
	public function createDir()
	{
		if (!is_dir($this->getPath()))
		{
			try
			{
				Core_File::mkdir($this->getPath(), CHMOD, TRUE);
			} catch (Exception $e) {}
		}

		return $this;
	}

	/**
	 * Set image size
	 * @return self
	 */
	public function setImageSizes()
	{
		$path = $this->getDeliveryFilePath();

		if (is_file($path))
		{
			$aSizes = Core_Image::instance()->getImageSize($path);
			if ($aSizes)
			{
				$this->image_width = $aSizes['width'];
				$this->image_height = $aSizes['height'];
				$this->save();
			}
		}

		return $this;
	}

	/**
	 * Copy object
	 * @return Core_Entity
	 */
	public function copy()
	{
		$newObject = parent::copy();

		try
		{
			Core_File::copy($this->getDeliveryFilePath(), $newObject->getDeliveryFilePath());
		} catch (Exception $e) {}

		$aShop_Delivery_Conditions = $this->Shop_Delivery_Conditions->findAll();
		foreach($aShop_Delivery_Conditions as $oShop_Delivery_Condition)
		{
			$oNew_Shop_Delivery_Condition = $oShop_Delivery_Condition->copy();
			$newObject->add($oNew_Shop_Delivery_Condition);
		}

		return $newObject;
	}

	/**
	 * Delete object from database
	 * @param mixed $primaryKey primary key for deleting object
	 * @return Core_Entity
	 */
	public function delete($primaryKey = NULL)
	{
		if (is_null($primaryKey))
		{
			$primaryKey = $this->getPrimaryKey();
		}

		$this->id = $primaryKey;

		$aShop_Delivery_Conditions = $this->Shop_Delivery_Conditions->findAll();
		foreach($aShop_Delivery_Conditions as $oShop_Delivery_Condition)
		{
			$oShop_Delivery_Condition->delete();
		}

		return parent::delete($primaryKey);
	}
}