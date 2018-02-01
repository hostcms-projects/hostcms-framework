<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Events.
 *
 * @package HostCMS
 * @subpackage Event
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2017 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Event_Controller_Group extends Admin_Form_Action_Controller
{

	/**
	 * Executes the business logic.
	 * @param mixed $operation Operation name
	 * @return self
	 */

	public function execute($operation = NULL)
	{
		if (is_null($operation))
		{
			$eventGroupId = Core_Array::getRequest('eventGroupId');

			if (is_null($eventGroupId))
			{
				throw new Core_Exception("eventGroupId is NULL");
			}

			$oEvent = $this->_object;
			$oEvent->event_group_id = intval($eventGroupId);
			$oEvent->save();
		}
	}
}