<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Shortcode.
 *
 * @package HostCMS 6\Shortcode
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2017 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Shortcode_Module extends Core_Module
	 * Module version
	 * @var string
	 */
	public $version = '6.6';

	/**
	 * Module date
	 * @var date
	 */
	public $date = '2017-01-27';
	/**
	 * Module name
	 * @var string
	 */
	protected $_moduleName = 'shortcode';

	/**
	 * Constructor.
	 */
		parent::__construct();

		$this->menu = array(
			array(
				'sorting' => 150,
				'block' => 3,
				'ico' => 'fa fa-file-code-o',
				'name' => Core::_('Shortcode.menu'),
				'href' => "/admin/shortcode/index.php",
				'onclick' => "$.adminLoad({path: '/admin/shortcode/index.php'}); return false"
			)
		);