<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Trash.
 *
 * @package HostCMS 6\Trash
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Trash_Module extends Core_Module{	/**
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
	protected $_moduleName = 'trash';
	
	/**
	 * Constructor.
	 */	public function __construct()	{
		parent::__construct();
		$this->menu = array(			array(				'sorting' => 260,				'block' => 3,
				'ico' => 'fa fa-trash-o',				'name' => Core::_('trash.menu'),				'href' => "/admin/trash/index.php",				'onclick' => "$.adminLoad({path: '/admin/trash/index.php'}); return false"			)		);	}}