<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Template Module.
 *
 * @package HostCMS
 * @subpackage Template
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2016 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Template_Module extends Core_Module
{
	/**
	 * Module version
	 * @var string
	 */
	public $version = '6.5';

	/**
	 * Module date
	 * @var date
	 */
	public $date = '2016-06-09';

	/**
	 * Module name
	 * @var string
	 */
	protected $_moduleName = 'template';
	
	/**
	 * Constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->menu = array(
			array(
				'sorting' => 70,
				'block' => 0,
				'ico' => 'fa fa-th',
				'name' => Core::_('template.menu'),
				'href' => "/admin/template/index.php",
				'onclick' => "$.adminLoad({path: '/admin/template/index.php'}); return false"
			)
		);
	}
}