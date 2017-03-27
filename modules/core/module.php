<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Abstract module
 *
 * @package HostCMS
 * @subpackage Core
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2016 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
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
	 * Module
	 * @var Core_Entity
	 */
	//protected $_module = NULL;

	/**
	 * The singleton instances.
	 * @var mixed
	 */
	static public $instance = NULL;

	/**
	 * Create module instance
	 * @param string $moduleName module name
	 * @return mixed
	 */
	static public function factory($moduleName)
	{
		if (isset(self::$instance[$moduleName]))
		{
			return self::$instance[$moduleName];
		}

		$modelName = ucfirst($moduleName) . '_Module';

		if (class_exists($modelName))
		{
			$oReflectionClass = new ReflectionClass($modelName);

			return self::$instance[$moduleName] = !$oReflectionClass->isAbstract()
				? new $modelName()
				: NULL;
		}

		return NULL;
	}

	/**
	 * Set module
	 * @param Core_Entity Module
	 */
	/*public function setModule(Core_Entity $Module)
	{
		$this->_module = $Module;
		return $this;
	}*/

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
	
	/**
	 * List of Schedule Actions
	 * @var array
	 */
	protected $_scheduleActions = array();
	
	/**
	 * Get List of Schedule Actions
	 * @return array
	 */
	public function getScheduleActions()
	{
		return $this->_scheduleActions;
	}
	
	/**
	 * Notify module on the action on schedule
	 * @param int $action action number
	 * @param int $entityId entity ID
	 * @return array
	 */
	public function callSchedule($action, $entityId)
	{
		// do smth
	}
}