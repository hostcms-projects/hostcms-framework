<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Abstract module
 *
 * @package HostCMS 6\Core
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2014 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
abstract class Core_Module
{
	/**
	 * Module version
	 * @var string
	 */
	public $version = NULL;

	/**
	 * Module date
	 * @var date
	 */
	public $date = NULL;

	/**
	 * Module menu
	 * @var array
	 */
	public $menu = array();

	/**
	 * Module name
	 * @var string
	 */
	protected $_moduleName = NULL;

	/**
	 * Create module instance
	 * @param string $moduleName module name
	 * @return mixed
	 */
	static public function factory($moduleName)
	{
		$modelName = ucfirst($moduleName) . '_Module';
		if (class_exists($modelName))
		{
			$oReflectionClass = new ReflectionClass($modelName);

			return !$oReflectionClass->isAbstract()
				? new $modelName()
				: NULL;
		}

		return NULL;
	}

	/**
	 * Get module name
	 * @return array
	 */
	public function getModuleName()
	{
		return $this->_moduleName;
	}

	/**
	 * List of admin pages
	 * @var array
	 */
	protected $_adminPages = array();

	/**
	 * Get list of admin pages
	 * @return array
	 */
	public function getAdminPages()
	{
		return $this->_adminPages;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {}

	/**
	 * Install module
	 * @return self
	 * @hostcms-event Core_Module.onBeforeInstall
	 */
	public function install()
	{
		Core_Event::notify(get_class($this) . '.onBeforeInstall', $this);

		return $this;
	}

	/**
	 * Uninstall module
	 * @return self
	 * @hostcms-event Core_Module.onBeforeUninstall
	 */
	public function uninstall()
	{
		Core_Event::notify(get_class($this) . '.onBeforeUninstall', $this);

		return $this;
	}
}