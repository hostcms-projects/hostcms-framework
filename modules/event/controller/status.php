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
class Event_Controller_Status extends Admin_Form_Action_Controller
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
			$eventStatusId = Core_Array::getRequest('eventStatusId');

			if (is_null($eventStatusId))
			{
				throw new Core_Exception("eventStatusId is NULL");
			}

			$oEvent_Status = Core_Entity::factory('Event_Status')->find(intval($eventStatusId));

			if (!is_null($oEvent_Status->id))
			{
				$oEvent = $this->_object;
				$oEvent->event_status_id = $oEvent_Status->id;
				$oEvent->completed = $oEvent_Status->final;
				$oEvent->save();
			}
		}
	}
}