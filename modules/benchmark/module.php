<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Benchmarks.
 *
 * @package HostCMS 6\Benchmark
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Benchmark_Module extends Core_Module{	/**
	 * Module version
	 * @var string
	 */
	public $version = '6.5';

	/**
	 * Module date
	 * @var date
	 */
	public $date = '2015-05-29';
	/**
	 * Module name
	 * @var string
	 */
	protected $_moduleName = 'benchmark';

	/**
	 * Constructor.
	 */	public function __construct()	{
		parent::__construct();
		$this->menu = array(			array(				'sorting' => 260,				'block' => 3,
				'ico' => 'fa fa-trophy',				'name' => Core::_('benchmark.menu'),				'href' => "/admin/benchmark/index.php",				'onclick' => "$.adminLoad({path: '/admin/benchmark/index.php'}); return false"			)		);	}}