<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * IP addresses.
 *
 * @package HostCMS 6\Ipaddress
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Ipaddress_Module extends Core_Module{	/**
	 * Module version
	 * @var string
	 */
	public $version = '6.5';

	/**
	 * Module date
	 * @var date
	 */
	public $date = '2015-06-05';
	/**
	 * Module name
	 * @var string
	 */
	protected $_moduleName = 'ipaddress';
	
	/**
	 * Constructor.
	 */	public function __construct()	{
		parent::__construct();
		$this->menu = array(			array(				'sorting' => 260,				'block' => 3,
				'ico' => 'fa fa-link',				'name' => Core::_('ipaddress.menu'),				'href' => "/admin/ipaddress/index.php",				'onclick' => "$.adminLoad({path: '/admin/ipaddress/index.php'}); return false"			)		);	}}