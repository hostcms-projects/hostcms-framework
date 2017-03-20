<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * input entity
 *
 * @package HostCMS 6\Core\Html
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2012 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Core_Html_Entity_Input extends Core_Html_Entity
{
	/**
	 * Allowed object properties
	 * @var array
	 */
	protected $_allowedProperties = array(
		'name',
		'align',
		'alt',
		'border',
		'checked',
		'disabled',
		'max',
		'maxlength',
		'min',
		'placeholder',
		'readonly',
		'size',
		'src',
		'tabindex',
		'type',
		'value'
	);

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->type('text');
	}

	/**
	 * Executes the business logic.
	 */
	public function execute()
	{
		$aAttr = $this->getAttrsString();

		echo PHP_EOL;

		?><input <?php echo implode(' ', $aAttr) ?>/><?php
	}
}