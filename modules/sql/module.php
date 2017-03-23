<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * SQL.
 *
 * @package HostCMS 6\Sql
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Sql_Module extends Core_Module{	/**
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
	protected $_moduleName = 'sql';
	
	/**
	 * Constructor.
	 */	public function __construct()	{
		parent::__construct();
		$this->menu = array(			array(				'sorting' => 270,				'block' => 3,
				'ico' => 'fa fa-database',				'name' => Core::_('sql.menu'),				'href' => "/admin/sql/index.php",				'onclick' => "$.adminLoad({path: '/admin/sql/index.php'}); return false"			)		);	}}