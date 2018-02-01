<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Company_Module
 *
 * @package HostCMS
 * @subpackage Company
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2017 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Company_Module extends Core_Module{	/**
	 * Module version
	 * @var string
	 */
	public $version = '6.7';

	/**
	 * Module date
	 * @var date
	 */
	public $date = '2017-12-25';
	/**
	 * Module name
	 * @var string
	 */
	protected $_moduleName = 'company';
	
	/**
	 * Constructor.
	 */	public function __construct()	{
		parent::__construct();
		$this->menu = array(			array(				'sorting' => 140,				'block' => 3,				
				'ico' => 'fa fa-building-o',				'name' => Core::_('Company.model_name'),				'href' => "/admin/company/index.php",				'onclick' => "$.adminLoad({path: '/admin/company/index.php'}); return false"			)		);	}
}