<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Admin forms.
 *
 * @package HostCMS 6\Admin
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2012 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Admin_Form_Entity_Checkbox extends Admin_Form_Entity_Input
{
	/**
	 * Constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->type('checkbox');
	}

	/**
	 * Executes the business logic.
	 */
	public function execute()
	{
		if (is_null($this->checked)
			&& $this->value != 0)
		{
			$this->checked = 'checked';
		}
	
		// Значение, передаваемое при включенном checkbox
		$this->value = 1;
	
		$aAttr = $this->getAttrsString();

		$aDefaultDivAttr = array('class' => 'item_div');
		$this->divAttr = Core_Array::union($this->divAttr, $aDefaultDivAttr);
		
		$aDivAttr = array();
		
		// Установим атрибуты div'a.
		if (is_array($this->divAttr))
		{
			foreach ($this->divAttr as $attrName => $attrValue)
			{
				$aDivAttr[] = "{$attrName}=\"" . htmlspecialchars($attrValue) . "\"";
			}
		}
		
		?><div <?php echo implode(' ', $aDivAttr)?>><?php

		/*?><input <?php echo implode(' ', $aAttr) ?>/><?php
		?><span class="caption" style="display: inline"><label for="<?php echo $this->id?>"><?php echo $this->caption?></label></span><?php*/
		
		?><label><input <?php echo implode(' ', $aAttr) ?>/> <?php
		?><span class="caption" style="display: inline"><?php echo $this->caption?></span></label><?php

		//parent::execute();
		foreach ($this->_children as $oCore_Html_Entity)
		{
			$oCore_Html_Entity->execute();
		}
		?></div><?php
	}
}
