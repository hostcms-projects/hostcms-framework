<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Administration center users.
 *
 * @package HostCMS 6\User
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class User_Controller_Edit extends Admin_Form_Action_Controller_Type_Edit
{
	/**
	 * Set object
	 * @param object $object object
	 * @return self
	 */
	public function setObject($object)
	{
		parent::setObject($object);

		$oMainTab = $this->getTab('main');
		$oAdditionalTab = $this->getTab('additional');

		$oAdditionalTab->delete($this->getField('user_group_id'));

		$user_group_id = is_null($this->_object->user_group_id)
			? intval(Core_Array::getGet('user_group_id', 0))
			: $this->_object->user_group_id;
			
			
		$oMainTab
			->add($oMainRow1 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow2 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow3 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow4 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow5 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow6 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow7 = Admin_Form_Entity::factory('Div')->class('row'));

		$oMainTab->move($this->getField('login'), $oMainRow1);


		// Селектор с группами пользователей
		$oSelect_User_Groups = Admin_Form_Entity::factory('Select')
			->options($this->_fillUserGroup())
			->name('user_group_id')
			->value($user_group_id)
			->caption(Core::_('User.users_type_form'));

		/*
		$oMainTab->addAfter(
			$oSelect_User_Groups, $this->getField('login')
		);
		*/
		
		$oMainRow2->add($oSelect_User_Groups);

		$oMainTab->delete($this->getField('password'));

		$aPasswordFormat = array(
			'minlen' => array('value' => 5),
			'maxlen' => array('value' => 255)
		);

		$oPasswordFirst = Admin_Form_Entity::factory('Password')
			->caption(Core::_('User.password'))
			->id('password_first')
			->name('password_first')
			->divAttr(array('class' => 'form-group col-xs-12 col-sm-6 col-md-6 col-lg-6'));

		!$this->_object->id && $oPasswordFirst->format($aPasswordFormat);

		//$oMainTab->addAfter($oPasswordFirst, $oSelect_User_Groups);
		

		$oPasswordSecond = Admin_Form_Entity::factory('Password')
			->caption(Core::_('User.password_second'))
			->name('password_second')
			->divAttr(array('class' => 'form-group col-xs-12 col-sm-6 col-md-6 col-lg-6'));
			

		$aPasswordFormatSecond = array(
			'fieldEquality' => array(
				'value' => 'password_first',
				'message' => Core::_('user.ua_add_edit_user_form_password_second_message')
			)
		);

		!$this->_object->id && $aPasswordFormatSecond += $aPasswordFormat;

		$oPasswordSecond->format($aPasswordFormatSecond);

		//$oMainTab->addAfter($oPasswordSecond, $oPasswordFirst);
		$oMainRow3
			->add($oPasswordFirst)
			->add($oPasswordSecond);
		
		$oMainTab->delete($this->getField('settings'));

		//$oMainTab->moveAfter($this->getField('active'), $oPasswordSecond);
		$oMainTab
			->move($this->getField('active'), $oMainRow4)
			->move($this->getField('superuser'), $oMainRow5)
			->move($this->getField('only_access_my_own'), $oMainRow6)
			->move($this->getField('read_only'), $oMainRow7);


		$oPersonalDataTab = Admin_Form_Entity::factory('Tab')
			->caption(Core::_('User.users_type_form_tab_2'))
			->name('tab_personal_data');

		$this->addTabAfter($oPersonalDataTab, $oMainTab);

		$oPersonalDataTab
			->add($oPersonalDataRow1 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oPersonalDataRow2 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oPersonalDataRow3 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oPersonalDataRow4 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oPersonalDataRow5 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oPersonalDataRow6 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oPersonalDataRow7 = Admin_Form_Entity::factory('Div')->class('row'));
		
		$oMainTab
			->move($this->getField('surname'), $oPersonalDataRow1)
			->move($this->getField('name'), $oPersonalDataRow2)
			->move($this->getField('patronymic'), $oPersonalDataRow3)
			->move($this->getField('position'), $oPersonalDataRow4)
			->move($this->getField('email'), $oPersonalDataRow5)
			->move($this->getField('icq'), $oPersonalDataRow6)
			->move($this->getField('site'), $oPersonalDataRow7);
		

		$title = $this->_object->id
			? Core::_('User.ua_edit_user_form_title')
			: Core::_('User.ua_add_user_form_title');

		$this->title($title);

		return $this;
	}

	/**
	 * Fill user groups list
	 * @return array
	 */
	protected function _fillUserGroup()
	{
		$oSite = Core_Entity::factory('site', CURRENT_SITE);

		$aUserGroups = $oSite->User_Groups->findAll();

		$aReturnUserGroups = array();
		foreach ($aUserGroups as $oUserGroup)
		{
			$aReturnUserGroups[$oUserGroup->id] = $oUserGroup->name;
		}

		return $aReturnUserGroups;
	}

	/**
	 * Processing of the form. Apply object fields.
	 * @return self
	 * @hostcms-event User_Controller_Edit.onAfterRedeclaredApplyObjectProperty
	 */
	protected function _applyObjectProperty()
	{
		$this
			->addSkipColumn('password')
			->addSkipColumn('settings');

		$password = Core_Array::getPost('password_first');

		if ($password != '' || is_null($this->_object->id))
		{
			$this->_object->password = Core_Hash::instance()->hash($password);
		}

		parent::_applyObjectProperty();

		Core_Event::notify(get_class($this) . '.onAfterRedeclaredApplyObjectProperty', $this, array($this->_Admin_Form_Controller));
	}

	/**
	 * Fill sites list
	 * @return array
	 */
	public function fillSites()
	{
		$aSites = Core_Entity::factory('User')->getCurrent()->getSites();

		$aReturn = array();
		foreach($aSites as $oSite)
		{
			$aReturn[$oSite->id] = $oSite->name;
		}

		return $aReturn;
	}

	/**
	 * Executes the business logic.
	 * @param mixed $operation Operation name
	 * @return mixed
	 */
	public function execute($operation = NULL)
	{
		if (!is_null($operation) && $operation != '')
		{
			$login = Core_Array::getRequest('login');
			$id = Core_Array::getRequest('id');
			$oSameUser = Core_Entity::factory('User')->getByLogin($login);

			if (!is_null($oSameUser) && $oSameUser->id != $id)
			{
				$this->addMessage(
					Core_Message::get(Core::_('User.user_has_already_registered'), 'error')
				);
				return TRUE;
			}
		}

		return parent::execute($operation);
	}
}