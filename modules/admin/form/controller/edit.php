<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Admin forms.
 *
 * @package HostCMS 6\Admin
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2013 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Admin_Form_Controller_Edit extends Admin_Form_Action_Controller_Type_Edit
{
	/**
	 * Set object
	 * @param object $object object
	 * @return self
	 */
	public function setObject($object)
	{
		$this
			->addSkipColumn('admin_word_id');

		parent::setObject($object);

		$oMainTab = $this->getTab('main');
		
		$oNameTab = Core::factory('Admin_Form_Entity_Tab')
			->caption(Core::_('Admin_Form.admin_form_tab_0'))
			->name('Name');
		
		$this->addTabBefore($oNameTab, $oMainTab);
		
		// Название и описание для всех языков
		$aAdmin_Languages = Core_Entity::factory('Admin_Language')->findAll();

		if (!empty($aAdmin_Languages))
		{
			foreach ($aAdmin_Languages as $oAdmin_Language)
			{
				$oAdmin_Word_Value = $this->_object->Admin_Word->getWordByLanguage($oAdmin_Language->id);

				if ($oAdmin_Word_Value)
				{
					$name = $oAdmin_Word_Value->name;
					$description = $oAdmin_Word_Value->description;
				}
				else
				{
					$name = '';
					$description = '';
				}

				$oAdmin_Form_Entity_Input_Name = Core::factory('Admin_Form_Entity_Input')
					->name('name_lng_' . $oAdmin_Language->id)
					->caption(Core::_('Admin_Form.form_forms_lng_name') . ' (' . $oAdmin_Language->shortname . ')')
					->value($name)
					->class('large')
					/*->format(
						array('minlen' => array('value' => 1))
					)*/;
					
				$oAdmin_Form_Entity_Textarea_Description = Core::factory('Admin_Form_Entity_Textarea')
					->name('description_lng_' . $oAdmin_Language->id)
					->caption(Core::_('Admin_Form.form_forms_lng_description') . ' (' . $oAdmin_Language->shortname . ')')
					->value($description)
					->rows(2);
				
				$oNameTab
					->add($oAdmin_Form_Entity_Input_Name)
					->add($oAdmin_Form_Entity_Textarea_Description);
			}
		}
		
		$this->getField('on_page')
			->class('');

		// Поле сортировки по умолчанию
		$this->getField('default_order_field')
			->divAttr(array('style' => 'float: left'))
			->style('width: 220px');
			
		// Направление сортировки
		$oMainTab->delete($this->getField('default_order_direction'));
		
		$oSelect_Order_Direction = Core::factory('Admin_Form_Entity_Select')
			->options(
				array(
					1 => Core::_('Admin_Form.asc'),
					2 => Core::_('Admin_Form.desc')
				)
			)
			->name('default_order_direction')
			->value($this->_object->default_order_direction)
			->caption(Core::_('Admin_Form.default_order_direction'))
			->style('width: 220px');

		$oMainTab->add(
			$oSelect_Order_Direction
		);
		
		//
		$oAdmin_Word_Value = $this->_object->Admin_Word->getWordByLanguage(CURRENT_LANGUAGE_ID);
		$form_name = $oAdmin_Word_Value ? $oAdmin_Word_Value->name : '';
		
		$title = is_null($this->_object->id)
			? Core::_('Admin_Form.form_add_forms_title')
			: Core::_('Admin_Form.form_edit_forms_title', $form_name);

		$this->title($title);

		return $this;
	}

	/**
	 * Processing of the form. Apply object fields.
	 * @return self
	 */
	protected function _applyObjectProperty()
	{
		parent::_applyObjectProperty();

		$aAdmin_Languages = Core_Entity::factory('Admin_Language')->findAll();

		if (!empty($aAdmin_Languages))
		{
			$oAdmin_Form = $this->_object;
			foreach ($aAdmin_Languages as $oAdmin_Language)
			{
				$oAdmin_Word_Value = $oAdmin_Form->Admin_Word->getWordByLanguage($oAdmin_Language->id);

				$name = Core_Array::getPost('name_lng_' . $oAdmin_Language->id);
				$description = Core_Array::getPost('description_lng_' . $oAdmin_Language->id);
				
				if (!$oAdmin_Word_Value)
				{
					$oAdmin_Word_Value = Core_Entity::factory('Admin_Word_Value');
					$oAdmin_Word_Value->admin_language_id = $oAdmin_Language->id;
				}

				$oAdmin_Word_Value->name = $name;
				$oAdmin_Word_Value->description = $description;
				$oAdmin_Word_Value->save();
				$oAdmin_Form->Admin_Word->add($oAdmin_Word_Value);

				$oAdmin_Form->add($oAdmin_Form->Admin_Word);
			}
		}

		return $this;
	}
	
}