<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Admin forms.
 *
 * @package HostCMS 6\Admin
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Skin_Bootstrap_Admin_Form_Entity_Menu extends Admin_Form_Entity
{
	/**
	 * Allowed object properties
	 * @var array
	 */
	protected $_allowedProperties = array(
		'name',
		'img',
		'href',
		'onclick',
		'target',
		'icon',
		'position',
	);

	/**
	 * Show menu item
	 */
	protected function _showMenuItem()
	{
		$aFirstColors = array(
			'btn-success',
			'btn-info',
			'btn-danger',
			'btn-warning',
			'btn-maroon',
		);

		$aSecondColors = array(
			'btn-palegreen',
			'btn-azure',
			'btn-darkorange',
			'btn-yellow',
			'btn-magenta',
		);

		$aDropdownColors = array(
			'dropdown-success',
			'dropdown-info',
			'dropdown-danger',
			'dropdown-warning',
			'dropdown-maroon',
		);

		$count = count($aSecondColors);

		$index = $this->position % $count;

		$oCore_Html_Entity_A = Core::factory('Core_Html_Entity_A');

		if ($this->href && $this->onclick)
		{
			$oCore_Html_Entity_A
				->href($this->href)
				->onclick($this->onclick);

			!is_null($this->target) && $oCore_Html_Entity_A->target($this->target);
			/*?><a href="<?php echo $this->href?>" onclick="<?php echo $this->onclick?>" ><?php*/
		}
		else
		{
			$oCore_Html_Entity_A
				->class("btn {$aFirstColors[$index]}")
				->set('data-toggle', 'dropdown');
			/*?><a class="btn <?php echo $aFirstColors[$index]?>" data-toggle="dropdown"><?php*/
		}

		if ($this->icon)
		{
			$oCore_Html_Entity_A->add(
				Core::factory('Core_Html_Entity_I')
					->class("{$this->icon} icon-separator")
			);

			/*?><i class="<?php echo htmlspecialchars($this->icon)?> icon-separator"></i><?php*/
		}

		//$oCore_Html_Entity_A->value($this->name);

		$oCore_Html_Entity_A->add(
			Core::factory('Core_Html_Entity_Code')
				->value(htmlspecialchars($this->name))
		);

		$oCore_Html_Entity_A->execute();

		if (!$this->href && !$this->onclick)
		{
			?><a class="btn <?php echo $aSecondColors[$index]?> dropdown-toggle" data-toggle="dropdown"><i class="fa fa-angle-down"></i></a><?php
		}

		if (!empty($this->_children))
		{
			?><ul class="dropdown-menu <?php echo $aDropdownColors[$index]?>"><?php

			// Вывод подменю
			foreach ($this->_children as $key => $subMenu)
			{
				?><li><?php $subMenu->_showMenuItem()?></li><?php
			}
			?></ul><?php
		}
	}

	/**
	 * Executes the business logic.
	 */
	public function execute()
	{
		?><div class="btn-group"><?php $this->_showMenuItem()?></div><?php
	}
}
