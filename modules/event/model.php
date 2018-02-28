<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Event_Model
 *
 * @package HostCMS
 * @subpackage Event
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2018 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Event_Model extends Core_Entity
{
	/**
	 * One-to-many or many-to-many relations
	 * @var array
	 */
	protected $_hasMany = array(
		'event_attachment' => array(),
		'event_siteuser' => array(),
		'event_user' => array(),
		'user' => array('through' => 'event_user'),
	);

	/**
	 * Belongs to relations
	 * @var array
	 */
	protected $_belongsTo = array(
		'event_type' => array(),
		'event_group' => array(),
		'event_status' => array()
	);

	/**
	 * Backend property
	 * @var mixed
	 */
	public $status = NULL;

	/**
	 * Backend property
	 * @var mixed
	 */
	public $group = NULL;

	public function deadline()
	{
		return !$this->completed
			&& $this->finish != '0000-00-00 00:00:00' && Core_Date::sql2timestamp($this->finish) < time();
	}

	/**
	 * Backend callback method
	 * @param Admin_Form_Field $oAdmin_Form_Field
	 * @param Admin_Form_Controller $oAdmin_Form_Controller
	 * @return string
	 */
	public function name($oAdmin_Form_Field, $oAdmin_Form_Controller)
	{
		ob_start();

		$oEventCreator = $this->getCreator();

		$oUser = Core_Entity::factory('User')->getCurrent();

		// Временая метка создания дела
		$iEventCreationTimestamp = Core_Date::sql2timestamp($this->datetime);

		// Сотрудник - создатель дела
		$userIsEventCreator = $oEventCreator && $oEventCreator->id == $oUser->id;

		$oEvent_Type = $this->Event_Type;

		$this->event_type_id && $this->showType();

		// Менять статус дела может только его создатель
		if ($userIsEventCreator)
		{
			// Список статусов дел
			$aEvent_Statuses = Core_Entity::factory('Event_Status')->findAll();

			$aMasEventStatuses = array();

			foreach ($aEvent_Statuses as $oEvent_Status)
			{
				$aMasEventStatuses[$oEvent_Status->id] = array('value' => $oEvent_Status->name, 'color' => $oEvent_Status->color);
			}

			$oCore_Html_Entity_Dropdownlist = new Core_Html_Entity_Dropdownlist();

			$oCore_Html_Entity_Dropdownlist
				->value($this->event_status_id)
				->options($aMasEventStatuses)
				//->class('btn-group event-status')
				->onchange("$.adminLoad({path: '/admin/event/index.php', additionalParams: 'hostcms[checked][0][{$this->id}]=0&eventStatusId=' + $(this).find('li[selected]').prop('id'), action: 'changeStatus', windowId: 'id_content'});")
				->execute();
		}
		else
		{
			if ($this->event_status_id)
			{
				$oEvent_Status = Core_Entity::factory('Event_Status', $this->event_status_id);

				$sEventStatusName = htmlspecialchars($oEvent_Status->name);
				$sEventStatusColor = htmlspecialchars($oEvent_Status->color);
			}
			else
			{
				$sEventStatusName = Core::_('Event.notStatus');
				$sEventStatusColor = '#aebec4';
			}
			?>
			<div class="event-status">
				<i class="fa fa-circle" style="margin-right: 5px; color: <?php echo $sEventStatusColor?>"></i><span style="color: <?php echo $sEventStatusColor?>"><?php echo $sEventStatusName?></span>
			</div>
			<?php
		}

		$nameColorClass = $this->deadline()
			? 'event-title-deadline'
			: '';

		$deadlineIcon = $this->deadline()
			? '<i class="fa fa-clock-o event-title-deadline"></i>'
			: '';

		?>
		<div class="event-title editable <?php echo $nameColorClass?>" id="apply_check_0_<?php echo $this->id?>_fv_1142"><?php echo $deadlineIcon, htmlspecialchars($this->name)?></div>

		<div class="event-description"><?php echo $this->description?></div>

		<div class="event-date"><?php

		if ($this->all_day)
		{
			echo Event_Controller::getDate($this->start);
		}
		else
		{
			if (!is_null($this->start) && $this->start != '0000-00-00 00:00:00')
			{
				echo Event_Controller::getDateTime($this->start);
			}

			if (!is_null($this->start) && $this->start != '0000-00-00 00:00:00'
				&& !is_null($this->finish) && $this->finish != '0000-00-00 00:00:00'
			)
			{
				echo ' — ';
			}

			if (!is_null($this->finish) && $this->finish != '0000-00-00 00:00:00')
			{
				?><strong><?php echo Event_Controller::getDateTime($this->finish);?></strong><?php
			}
		}

		$iDeltaTime  = time() - $iEventCreationTimestamp;

		// ФИО создателя дела, если оным не является текущий сотрудник
		if (!$userIsEventCreator && $oEventCreator)
		{
			?>
			<div class="<?php echo $oEventCreator->isOnline() ? 'online margin-left-20' : 'offline margin-left-20'; ?>"></div>
			<!-- <a href="/admin/user/index.php?hostcms[action]=view&hostcms[checked][0][<?php echo $oEventCreator->id?>]=1" onclick="$.adminLoad({path: '/admin/user/index.php', additionalParams: 'hostcms[action]=view&hostcms[checked][0][<?php echo $oEventCreator->id?>]=1', windowId: 'id_content'}); return false"><?php echo htmlspecialchars($oEventCreator->getFullName());?></a>-->
			<a href="/admin/user/index.php?hostcms[action]=view&hostcms[checked][0][<?php echo $oEventCreator->id?>]=1" onclick="$.modalLoad({path: '/admin/user/index.php', action: 'view', operation: 'modal', additionalParams: 'hostcms[checked][0][<?php echo $oEventCreator->id?>]=1', windowId: 'id_content'}); return false"><?php echo htmlspecialchars($oEventCreator->getFullName());?></a><?php
		}
		?>
		</span>
		<span class="small darkgray pull-right"><i class="fa fa-clock-o"></i><?php echo Core_Date::time2string($iDeltaTime)?></span>
		</div><?php

		return ob_get_clean();
	}

	/**
	 * Backend callback method
	 * @param Admin_Form_Field $oAdmin_Form_Field
	 * @param Admin_Form_Controller $oAdmin_Form_Controller
	 * @return string
	 */
	public function event_group_idBackend($oAdmin_Form_Field, $oAdmin_Form_Controller)
	{
		ob_start();

		$oEventCreator = $this->getCreator();
		$oCurrentUser = Core_Entity::factory('User')->getCurrent();

		// Сотрудник - создатель дела
		$userIsEventCreator = ($oEventCreator && $oEventCreator->id == $oCurrentUser->id);

		// Менять группу дела может только его создатель
		if ($userIsEventCreator)
		{
			$aMasEventGroups = array(array('value' => Core::_('Event.notGroup'), 'color' => '#aebec4'));

			// Группы дел
			$aEventGroups = Core_Entity::factory('Event_Group')->findAll();

			foreach ($aEventGroups as $oEventGroup)
			{
				$aMasEventGroups[$oEventGroup->id] = array('value' => $oEventGroup->name, 'color' => $oEventGroup->color);
			}

			$oCore_Html_Entity_Dropdownlist = new Core_Html_Entity_Dropdownlist();

			$oCore_Html_Entity_Dropdownlist
				->value($this->event_group_id)
				->options($aMasEventGroups)
				->onchange("$.adminLoad({path: '/admin/event/index.php', additionalParams: 'hostcms[checked][0][{$this->id}]=0&eventGroupId=' + $(this).find('li[selected]').prop('id'), action: 'changeGroup', windowId: 'id_content'});")
				->execute();
		}
		else
		{
			if ($this->event_group_id)
			{
				$oEventGroup = Core_Entity::factory('Event_Group', $this->event_group_id);

				$sEventGroupName = htmlspecialchars($oEventGroup->name);
				$sEventGroupColor = htmlspecialchars($oEventGroup->color);
			}
			else
			{
				$sEventGroupName = Core::_('Event.notGroup');
				$sEventGroupColor = '#aebec4';
			}
			?>
			<div class="event-group">
				<i class="fa fa-circle" style="margin-right: 5px; color: <?php echo $sEventGroupColor?>"></i><span style="color: <?php echo $sEventGroupColor?>"><?php echo $sEventGroupName?></span>
			</div>
			<?php
		}

		return ob_get_clean();
	}

	/**
	 * Backend callback method
	 * @param Admin_Form_Field $oAdmin_Form_Field
	 * @param Admin_Form_Controller $oAdmin_Form_Controller
	 * @return string
	 */
	public function important($oAdmin_Form_Field, $oAdmin_Form_Controller)
	{
		$sExclamation = '<i class="fa fa-exclamation-circle ' . ($this->important ? 'red' : 'fa-inactive') . '"></i>';

		// Создатель дела
		$oEventCreator = $this->getCreator();

		// Авторизованный сотрудник
		$oUserCurrent = Core_Entity::factory('User', 0)->getCurrent();

		// Изменять важность дела может только его создатель
		if ($oEventCreator && $oEventCreator->id == $oUserCurrent->id)
		{
			ob_start();

			Admin_Form_Entity::factory('a')
				->href("/admin/event/index.php?hostcms[action]=changeImportant&hostcms[checked][0][{$this->id}]=0")
				->onclick("$.adminLoad({path: '/admin/event/index.php',additionalParams: 'hostcms[checked][0][{$this->id}]=0', action: 'changeImportant', windowId: '{$oAdmin_Form_Controller->getWindowId()}'}); return false")
				->add(
					Admin_Form_Entity::factory('Code')
						->html($sExclamation)
				)
				->execute();

			$sExclamation = ob_get_clean();
		}

		return $sExclamation;
	}

	/**
	 * Change important
	 * @return self
	 * @hostcms-event event.onBeforeChangeImportant
	 * @hostcms-event event.onAfterChangeImportant
	 */
	public function changeImportant()
	{
		// Создатель дела
		$oEventCreator = $this->getCreator();

		// Авторизованный сотрудник
		$oUser = Core_Entity::factory('User', 0)->getCurrent();

		// Изменять важность дела может только его создатель
		if ($oEventCreator && $oEventCreator->id == $oUser->id)
		{
			Core_Event::notify($this->_modelName . '.onBeforeChangeImportant', $this);

			$this->important = 1 - $this->important;
			$this->save();

			Core_Event::notify($this->_modelName . '.onAfterChangeImportant', $this);

			return $this;
		}

		return FALSE;
	}

	/**
	 * Change completed
	 * @return self
	 * @hostcms-event event.onBeforeChangeCompleted
	 * @hostcms-event event.onAfterChangeCompleted
	 */
	public function changeCompleted()
	{
		Core_Event::notify($this->_modelName . '.onBeforeChangeCompleted', $this);

		$this->completed = 1 - $this->completed;
		!$this->completed && $this->event_status_id = 0;
		$this->save();

		$this->changeCompletedSendNotification();

		Core_Event::notify($this->_modelName . '.onAfterChangeCompleted', $this);

		return $this;
	}

	public function changeCompletedSendNotification()
	{
		$oModule = Core_Entity::factory('Module')->getByPath('event');

		if (!is_null($oModule))
		{
			// Ответственные сотрудники
			$oEventUsers = $this->Event_Users;

			// Текущий сотрудник
			$oUser = Core_Entity::factory('User', 0)->getCurrent();

			$oEventUsers
				->queryBuilder()
				->where('user_id', '!=', $oUser->id);

			$aEventUsers = $oEventUsers->findAll();

			// Добавляем уведомление
			$oNotification = Core_Entity::factory('Notification')
				->title($this->name)
				->description($this->completed ? Core::_('Event.notificationDescriptionType2') : Core::_('Event.notificationDescriptionType3'))
				->datetime(Core_Date::timestamp2sql(time()))
				->module_id($oModule->id)
				->type($this->completed ? 2 : 3) // 2 - дело завершено, 3 - дело возобновлено
				->entity_id($this->id)
				->save();

			// Связываем уведомление с ответственными сотрудниками
			foreach ($aEventUsers as $oEventUser)
			{
				Core_Entity::factory('Notification_User')
					->notification_id($oNotification->id)
					->user_id($oEventUser->user_id)
					->save();
			}
		}
	}

	public function getCreator()
	{
		$oEvent_User = $this->Event_Users->getByCreator(1);

		return $oEvent_User ? $oEvent_User->User : FALSE;
	}

	/**
	 * Delete object from database
	 * @param mixed $primaryKey primary key for deleting object
	 * @return Core_Entity
	 * @hostcms-event event.onBeforeRedeclaredDelete
	 */
	public function delete($primaryKey = NULL)
	{
		if (is_null($primaryKey))
		{
			$primaryKey = $this->getPrimaryKey();
		}

		$this->id = $primaryKey;

		Core_Event::notify($this->_modelName . '.onBeforeRedeclaredDelete', $this, array($primaryKey));

		$this->Event_Attachments->deleteAll(FALSE);
		$this->Event_Users->deleteAll(FALSE);
		$this->Event_Siteusers->deleteAll(FALSE);

		$this->deleteDir();

		return parent::delete($primaryKey);
	}

	/**
	 * Get message files href
	 */
	public function getHref()
	{
		 return 'upload/private/events/' . Core_File::getNestingDirPath($this->id, 3) . '/event_' . $this->id . '/';
	}

	/**
	 * Get path for files
	 * @return string
	 */
	public function getPath()
	{
		return CMS_FOLDER . $this->getHref();
	}

	/**
	 * Create message files directory
	 * @return self
	 */
	public function createDir()
	{
		if (!is_dir($this->getPath()))
		{
			try
			{
				Core_File::mkdir($this->getPath(), CHMOD, TRUE);
			} catch (Exception $e) {}
		}

		return $this;
	}

	/**
	 * Delete message files directory
	 * @return self
	 */
	public function deleteDir()
	{
		if (is_dir($this->getPath()))
		{
			try
			{
				Core_File::deleteDir($this->getPath());
			} catch (Exception $e) {}
		}

		return $this;
	}

	/**
	 * Delete message attachment file
	 * @param $event_attachment_id attachment id
	 * @return self
	 */
	public function deleteFile($event_attachment_id)
	{
		$oEvent_Attachment = $this->Event_Attachments->getById($event_attachment_id);
		if ($oEvent_Attachment)
		{
			$oEvent_Attachment->delete();
		}

		return $this;
	}

	/**
	 * Show type badge
	 * @return string
	 */
	public function showType()
	{
		?><span class="badge badge-square margin-right-10" style="color: <?php echo $this->Event_Type->color?>; background-color:<?php echo Core_Str::hex2lighter($this->Event_Type->color, 0.88)?>"><i class="<?php echo htmlspecialchars($this->Event_Type->icon)?>"></i> <?php echo htmlspecialchars($this->Event_Type->name)?></span><?php
	}

	public function getToday($bCache = TRUE)
	{
		$dateTime = date('Y-m-d');

		$this->queryBuilder()
			->where('events.completed', '=', 0)
			->open()
				->where('events.start', '>', $dateTime . ' 00:00:00')
				//->setOr()
				->where('events.finish', '<', $dateTime . ' 23:59:59')
				->setOr()
				// ->where('events.all_day', '=', 1)
				->where('events.start', '<', $dateTime . ' 23:59:59')
				->where('events.finish', '>', $dateTime . ' 00:00:00')
			->close()
			->clearOrderBy()
			->orderBy('start', 'DESC')
			->orderBy('important', 'DESC');

		return $this->findAll($bCache);
	}

	/**
	 * Mark entity as deleted
	 * @return Core_Entity
	 */
	public function markDeleted()
	{
		$oUser = Core_Entity::factory('User', 0)->getCurrent();

		$aCalendar_Caldavs = Core_Entity::factory('Calendar_Caldav')->getAllByActive(1);
		foreach ($aCalendar_Caldavs as $oCalendar_Caldav)
		{
			$oCalendar_Caldav_User = $oCalendar_Caldav->Calendar_Caldav_Users->getByUser_id($oUser->id);

			if (!is_null($oCalendar_Caldav_User)
				&& !is_null($oCalendar_Caldav_User->caldav_server)
				&& !is_null($oCalendar_Caldav_User->username)
				&& !is_null($oCalendar_Caldav_User->password)
			)
			{
				$Calendar_Caldav_Controller = Calendar_Caldav_Controller::instance($oCalendar_Caldav->driver);

				$Calendar_Caldav_Controller
					->setUrl($oCalendar_Caldav_User->caldav_server)
					->setUsername($oCalendar_Caldav_User->username)
					->setPassword($oCalendar_Caldav_User->password)
					->setData($oCalendar_Caldav_User->data)
					->connect();

				$aCalendars = $Calendar_Caldav_Controller->findCalendars();

				if (count($aCalendars))
				{
					$Calendar_Caldav_Controller->setCalendar(array_shift($aCalendars));

					$oModule = Core_Entity::factory('Module')->getByPath('event');

					$sUid = $this->id . '_' . $oModule->id;
					$sUrl = $Calendar_Caldav_Controller->getCalendar() . $sUid . '.ics';

					$Calendar_Caldav_Controller->delete($sUrl);
				}
			}
		}

		return parent::markDeleted();
	}

	/**
	 * Copy object
	 * @return Core_Entity
	 */
	public function copy()
	{
		$newObject = parent::copy();

		$aEvent_Users = $this->Event_Users->findAll(FALSE);
		foreach ($aEvent_Users as $oEvent_User)
		{
			$newObject->add(clone $oEvent_User);
		}

		if (Core::moduleIsActive('siteuser'))
		{
			$aEvent_Siteusers = $this->Event_Siteusers->findAll(FALSE);

			foreach ($aEvent_Siteusers as $oEvent_Siteuser)
			{
				$newObject->add(clone $oEvent_Siteuser);
			}
		}

		return $newObject;
	}
}