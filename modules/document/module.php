<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Documents.
 *
 * @package HostCMS 6\Document
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Document_Module extends Core_Module{	/**
	 * Module version
	 * @var string
	 */
	public $version = '6.5';

	/**
	 * Module date
	 * @var date
	 */
	public $date = '2016-02-03';
	/**
	 * Module name
	 * @var string
	 */
	protected $_moduleName = 'document';

	/**
	 * Constructor.
	 */	public function __construct()	{
		parent::__construct();
		$this->menu = array(			array(				'sorting' => 20,				'block' => 0,
				'ico' => 'fa fa-file-text-o',
				'name' => Core::_('Document.menu'),				'href' => "/admin/document/index.php",				'onclick' => "$.adminLoad({path: '/admin/document/index.php'}); return false"			)		);	}}