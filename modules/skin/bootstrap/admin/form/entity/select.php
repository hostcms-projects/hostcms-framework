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
class Skin_Bootstrap_Admin_Form_Entity_Select extends Skin_Default_Admin_Form_Entity_Select
{
	/**
	 * Executes the business logic.
	 */
	public function execute()
	{
		$aAttr = $this->getAttrsString();

		// Установим атрибуты div'a.
		$aDivAttr = array();
		if (is_array($this->divAttr))
		{
			foreach ($this->divAttr as $attrName => $attrValue)
			{
				$aDivAttr[] = "{$attrName}=\"" . htmlspecialchars($attrValue) . "\"";
			}
		}

		?><div <?php echo implode(' ', $aDivAttr)?>><?php

		if ($this->filter)
		{
			?><div class="row">
				<div class="col-lg-6 col-md-5 col-sm-5 col-xs-5"><?php
		}

		?><span class="caption"><?php echo $this->caption;

		$this->invertor && $this->_invertor();
		
		?></span><?php

		if (count($this->_children))
		{
			?><div class="input-group"><?php
		}

		?><select <?php echo implode(' ', $aAttr) ?>><?php
		if (is_array($this->options))
		{
			foreach ($this->options as $key => $xValue)
			{
				$sAttr = '';

				if (is_array($xValue))
				{
					$value = Core_Array::get($xValue, 'value');
					$attr = Core_Array::get($xValue, 'attr', array());

					!empty($attr) && $sAttr = ' ';
					foreach($attr as $attrKey => $attrValue)
					{
						$sAttr .= Core_Str::xml($attrKey) . '="' . Core_Str::xml($attrValue) . '"';
					}
				}
				else
				{
					$value = $xValue;
				}

				?><option value="<?php echo htmlspecialchars($key)?>"<?php echo ($this->value == $key) ? ' selected="selected"' : ''?><?php echo $sAttr?>><?php
				?><?php echo htmlspecialchars($value, ENT_COMPAT, 'UTF-8')?><?php
				?></option><?php
			}
		}
		?></select><?php

		$this->executeChildren();

		if (count($this->_children))
		{
			?></div><?php
		}

		if ($this->filter)
		{
			?></div><?php
			$this->_filter();
			?></div><?php
		}

		?></div><?php
	}

	/**
	 * Show invertor
	 * @return self
	 */
	protected function _invertor()
	{
		?><label class="checkbox-inline"><?php
		$oCore_Html_Entity_Input = Core::factory('Core_Html_Entity_Input')
			->type("checkbox")
			->id($this->invertor_id)
			->name($this->name . '_inverted')
			->value(1);

		$this->inverted && $oCore_Html_Entity_Input->checked(TRUE);

		$oCore_Html_Entity_Input->execute();

		Core::factory('Core_Html_Entity_Span')
			->class('caption text')
			->style('display:inline')
			->value($this->invertorCaption . '&nbsp;')
			->execute();
		?></label><?php
		return $this;
	}

	/**
	 * Show filter
	 * @return self
	 */
	protected function _filter()
	{
		$windowId = $this->_Admin_Form_Controller->getWindowId();
		$iFilterCount = self::$iFilterCount;

		Admin_Form_Entity::factory('Div')
			->class('col-lg-3 col-md-3 col-sm-3 col-xs-4 no-padding-left')
			->add(
				Admin_Form_Entity::factory('Div')
					->class('input-group margin-top-21')
					->add(
						Admin_Form_Entity::factory('Code')
							->html('<span class="input-group-addon"><i class="fa fa-search"></i></span>
								<input class="form-control" type="text" id="filer_' . $this->id . '" onkeyup="clearTimeout(oSelectFilter' . $iFilterCount . '.timeout); oSelectFilter' . $iFilterCount . '.timeout = setTimeout(function(){oSelectFilter' . $iFilterCount . ".Set($('#{$windowId} #filer_{$this->id}').val()); oSelectFilter{$iFilterCount}.Filter(); }, 500)". '" onkeypress="if (event.keyCode == 13) return false;" />' .
								'<span class="input-group-addon" onclick="' . "$('#{$windowId} #filer_{$this->id}').val('');oSelectFilter{$iFilterCount}.Set('');oSelectFilter{$iFilterCount}.Filter();" . '"><i class="fa fa-times-circle no-margin"></i></span>'
							)

					)
			)
			->execute();

			Admin_Form_Entity::factory('Div')
				->class('col-lg-3 col-md-4 col-sm-4 col-xs-3 no-padding-left margin-top-21')
				->add(
					Admin_Form_Entity::factory('Code')
						->html('<label class="checkbox-inline">' .
						'<input id="field_id_15" class="form-control" type="checkbox" value="1" checked="checked" name="indexing" />' .
						'<span class="text"> ' . Core::_('Admin_Form.input_case_sensitive') . '</span></label>')
				)
				->execute();

			Core::factory('Core_Html_Entity_Script')
					->type("text/javascript")
					->value("var oSelectFilter{$iFilterCount} = new cSelectFilter('{$windowId}', '{$this->id}');")
					->execute();

		self::$iFilterCount++;

		return $this;
	}
}