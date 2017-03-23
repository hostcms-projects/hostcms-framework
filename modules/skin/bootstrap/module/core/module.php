<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Core.
 *
 * @package HostCMS 6\Skin
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Skin_Bootstrap_Module_Core_Module extends Core_Module
{
	/**
	 * Name of the skin
	 * @var string
	 */
	protected $_skinName = 'bootstrap';

	/**
	 * Name of the module
	 * @var string
	 */
	protected $_moduleName = 'core';

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_adminPages = array(
			1 => array('title' => Core::_('Admin.index_systems_events')),
			2 => array('title' => Core::_('Admin.index_systems_characteristics')),
			3 => array('title' => Core::_('Admin.notes'))
		);
	}

	/**
	 * Show admin widget
	 * @param int $type
	 * @param boolean $ajax
	 * @return self
	 */
	public function adminPage($type = 0, $ajax = FALSE)
	{
		$type = intval($type);

		$this->_path = "/admin/index.php?ajaxWidgetLoad&moduleId=0&type={$type}";

		switch ($type)
		{
			//Заметки
			case 1:
				$windowId = 'modalNotes';
			break;
			// Журнал событий
			case 2:
				$windowId = 'modalEvents';
			break;
			default:
				$windowId = 'modalCharacteristics';
			break;
		}

		switch ($type)
		{
			// Заметки
			case 1:
				if ($ajax)
				{
					$this->_notesContent();
				}
				else
				{
					?><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="notesAdminPage">
						<script type="text/javascript">
						$.widgetLoad({ path: '<?php echo $this->_path?>', context: $('#notesAdminPage') });
						</script>
					</div><?php
				}
			break;
			// Журнал событий
			case 2:
				if ($ajax)
				{
					$this->_eventsContent();
				}
				else
				{
					?><div class="col-xs-12 col-sm-8 col-md-8 col-lg-8" id="eventsAdminPage">
						<script type="text/javascript">
						$.widgetLoad({ path: '<?php echo $this->_path?>', context: $('#eventsAdminPage') });
						</script>
					</div><?php
				}
			break;
			// Системные характеристики
			default:
				if ($ajax)
				{
					$this->_characteristicsContent();
				}
				else
				{
					?><div class="systems-characteristics col-xs-12 col-sm-4 col-md-4 col-lg-4" id="characteristicsAdminPage">
						<script type="text/javascript">
						$.widgetLoad({ path: '<?php echo $this->_path?>', context: $('#characteristicsAdminPage') });
						</script>
					</div><?php
				}
			break;
		}

		return $this;
	}

	protected function _eventsContent()
	{
		$oCore_Log = Core_Log::instance();
		$file_name = $oCore_Log->getLogName(date('Y-m-d'));

		?><div class="widget">
			<div class="widget-header bordered-bottom bordered-themeprimary">
				<i class="widget-icon fa fa-tasks themeprimary"></i>
				<span class="widget-caption themeprimary"><?php echo Core::_('Admin.index_systems_events');?></span>
				<div class="widget-buttons">
					<a data-toggle="maximize">
						<i class="fa fa-expand gray"></i>
					</a>
					<a onclick="$(this).find('i').addClass('fa-spin'); $.widgetLoad({ path: '<?php echo $this->_path?>', context: $('#eventsAdminPage'), 'button': $(this).find('i') });">
						<i class="fa fa-refresh gray"></i>
					</a>
				</div>
			</div>
			<div class="widget-body">
				<!--<div class="padd scroll-systemsevents">-->
				<div class="widget-main no-padding">
					<div class="tickets-container">

					<!--<ul class="eventsjournal timeline fadeInDown">-->
		<?php
		if (is_file($file_name))
		{
			if ($fp = @fopen($file_name, 'r'))
			{
				?>
				<ul class="tickets-list">
				<?php
				$countEvents = 8;

				$aLines = array();
				$iSize = @filesize($file_name);
				$iSlice = 10240;

				$iSize > $iSlice && fseek($fp, $iSize - $iSlice);

				// [0]-дата/время, [1]-имя пользователя, [2]-события, [3]-статус события, [4]-сайт, [5]-страница
				while (!feof($fp))
				{
					$event = fgetcsv($fp, $iSlice);
					if (empty($event[0]) || count($event) < 3)
					{
						continue;
					}
					$aLines[] = $event;
				}

				count($aLines) > $countEvents && $aLines = array_slice($aLines, -$countEvents);
				$aLines = array_reverse($aLines);

				foreach ($aLines as $aLine)
				{
					if (count($aLine) > 3)
					{
						switch (intval($aLine[3]))
						{
							case 1:
								$statusCharClassName = ' fa-check';
								$statusColorName = 'palegreen';
							break;
							case 2:
								$statusCharClassName = 'fa-exclamation';
								$statusColorName = 'yellow';
							break;
							case 3:
								$statusCharClassName = 'fa-exclamation';
								$statusColorName = 'orange';
							break;
							case 4:
								$statusCharClassName = 'fa-exclamation';
								$statusColorName = 'red';
							break;
							default:
								$statusCharClassName = 'fa-info';
								$statusColorName = 'darkgray';
						}
						?><li class="ticket-item">
							<div class="row">
								<div class="ticket-user col-lg-7 col-sm-12">
									<span class="user-name"><?php echo htmlspecialchars(Core_Str::cut(strip_tags($aLine[2]), 70))?></span>
								</div>
								<div class="ticket-time col-lg-3 col-sm-6 col-xs-6">
									<div class="divider hidden-md hidden-sm hidden-xs"></div>
									<i class="fa fa-clock-o"></i>
									<span class="time"><?php echo htmlspecialchars(Core_Date::sql2datetime($aLine[0]));?></span>
								</div>
								<div class="ticket-type col-lg-2 col-sm-6 col-xs-6">
									<span class="divider hidden-xs"></span>
									<i class="fa fa-user"></i>
									<span class="type"><?php echo htmlspecialchars($aLine[1])?></span>
								</div>
								<div class="ticket-state bg-<?php echo $statusColorName?>">
									<i class="fa <?php echo $statusCharClassName?>"></i>
								</div>
							</div>
						</li>
					<?php
					}
				}

				unset($aLines);
				?>
				</ul>
				<?php
				if (Core::moduleIsActive('eventlog'))
				{
					$sEventlogHref = '/admin/eventlog/index.php';
					?>
					<br />
					<div class="footer">
						<a class="btn btn-info" href="<?php echo $sEventlogHref;?>" onclick="$.adminLoad({path: '<?php echo $sEventlogHref;?>'}); return false"><i class="fa fa-book"></i><?php echo Core::_('Admin.index_events_journal_link') ?></a>
					</div>
					<?php
				}
			}
			else
			{
				$oModalWindowSub->value(
					Core_Message::get(Core::_('Admin.index_error_open_log') . $file_name, 'error')
				);
			}
		}
		?>
					</div>
				</div>
			</div>
		</div>
		<?php
		return $this;
	}

	protected function _characteristicsContent()
	{
		$dbVersion = Core_DataBase::instance()->getVersion();
		$gdVersion = Core_Image::instance('gd')->getVersion();
		$pcreVersion = Core::getPcreVersion();
		$memoryLimit = ini_get('memory_limit')
			? ini_get('memory_limit')
			: 'undefined';

		$maxExecutionTime = intval(ini_get('max_execution_time'));
		?><div class="widget">
			<div class="widget-header bordered-bottom bordered-blue">
				<i class="widget-icon fa fa-gears blue"></i>
				<span class="widget-caption blue"><?php echo Core::_('Admin.index_systems_characteristics')?></span>
				<div class="widget-buttons">
					<a data-toggle="maximize">
						<i class="fa fa-expand gray"></i>
					</a>
					<a onclick="$(this).find('i').addClass('fa-spin'); $.widgetLoad({ path: '<?php echo $this->_path?>', context: $('#characteristicsAdminPage'), 'button': $(this).find('i') });">
						<i class="fa fa-refresh gray"></i>
					</a>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-main no-padding">
					<div class="tickets-container">
						<ul class="tickets-list">
							<li class="ticket-item">
								<div class="row">
									<div class="ticket-user">
										<span class="user-name"><?php echo Core::_('Admin.index_tech_date_hostcms')?></span>
										<span class="user-company"><?php echo CURRENT_VERSION ?></span>
									</div>
									<div class="ticket-state bg-palegreen">
										<i class="fa fa-check"></i>
									</div>
								</div>
							</li>
							<li class="ticket-item">
								<div class="row">
									<div class="ticket-user">
										<span class="user-name"><?php echo Core::_('Admin.index_tech_date_editorial')?></span>
										<span class="user-company"><?php echo Core::_('Core.redaction' . Core_Array::get(Core::$config->get('core_hostcms'), 'integration', 0))?></span>
									</div>
									<div class="ticket-state bg-palegreen">
										<i class="fa fa-check"></i>
									</div>
								</div>
							</li>
							<li class="ticket-item">
								<div class="row">
									<div class="ticket-user">
										<span class="user-name"><?php echo Core::_('Admin.index_tech_date_php') ?></span>
										<span class="user-company"><?php echo phpversion() ?></span>
									</div>
									<?php
									if(version_compare(phpversion(), '5.2.2', ">="))
									{
										$divClass = ' bg-palegreen';
										$iClass = ' fa-check';
									}
									else
									{
										$divClass = ' bg-darkorange';
										$iClass = ' fa-times';
									}
									?>
									<div class="ticket-state<?php echo $divClass?>">
										<i class="fa<?php echo $iClass?>"></i>
									</div>
								</div>
							</li>
							<li class="ticket-item">
								<div class="row">
									<div class="ticket-user">
										<span class="user-name"><?php echo Core::_('Admin.index_tech_date_sql') ?></span>
										<span class="user-company"><?php echo $dbVersion ?></span>
									</div>
									<?php
									if(version_compare($dbVersion, '5.0.0', ">=") )
									{
										$divClass = ' bg-palegreen';
										$iClass = ' fa-check';
									}
									else
									{
										$divClass = ' bg-darkorange';
										$iClass = ' fa-times';
									}
									?>
									<div class="ticket-state<?php echo $divClass?>">
										<i class="fa<?php echo $iClass?>"></i>
									</div>
								</div>
							</li>
							<li class="ticket-item">
								<div class="row">
									<div class="ticket-user">
										<span class="user-name"><?php echo Core::_('Admin.index_tech_date_gd') ?></span>
										<span class="user-company"><?php echo $gdVersion ?></span>
									</div>
									<?php
									if(version_compare($gdVersion, '2.0', ">="))
									{
										$divClass = ' bg-palegreen';
										$iClass = ' fa-check';
									}
									else
									{
										$divClass = ' bg-darkorange';
										$iClass = ' fa-times';
									}
									?>
									<div class="ticket-state<?php echo $divClass?>">
										<i class="fa<?php echo $iClass?>"></i>
									</div>
								</div>
							</li>
							<li class="ticket-item">
								<div class="row">
									<div class="ticket-user">
										<span class="user-name"><?php echo Core::_('Admin.index_tech_date_pcre') ?></span>
										<span class="user-company"><?php echo $pcreVersion ?></span>
									</div>

									<?php
									if(version_compare($pcreVersion, '7.0', ">="))
									{
										$divClass = ' bg-palegreen';
										$iClass = ' fa-check';
									}
									else
									{
										$divClass = ' bg-darkorange';
										$iClass = ' fa-times';
									}
									?>
									<div class="ticket-state<?php echo $divClass?>">
										<i class="fa<?php echo $iClass?>"></i>
									</div>
								</div>
							</li>
							<li class="ticket-item">
								<div class="row">
									<div class="ticket-user">
										<span class="user-name"><?php echo Core::_('Admin.index_tech_date_max_time') ?></span>
										<span class="user-company"><?php echo $maxExecutionTime ?></span>
									</div>

									<?php
									if(!$maxExecutionTime || $maxExecutionTime >= 30)
									{
										$divClass = ' bg-palegreen';
										$iClass = ' fa-check';
									}
									else
									{
										$divClass = ' bg-darkorange';
										$iClass = ' fa-times';
									}
									?>
									<div class="ticket-state<?php echo $divClass?>">
										<i class="fa<?php echo $iClass?>"></i>
									</div>
								</div>
							</li>
							<li class="ticket-item">
								<div class="row">
									<div class="ticket-user">
										<span class="user-name"><?php echo Core::_('Admin.index_memory_limit') ?></span>
										<span class="user-company"><?php echo $memoryLimit?></span>
									</div>

									<?php
									if(Core_Str::convertSizeToBytes($memoryLimit) >= Core_Str::convertSizeToBytes('16M'))
									{
										$divClass = ' bg-palegreen';
										$iClass = ' fa-check';
									}
									else
									{
										$divClass = ' bg-darkorange';
										$iClass = ' fa-times';
									}
									?>
									<div class="ticket-state<?php echo $divClass?>">
										<i class="fa<?php echo $iClass?>"></i>
									</div>
								</div>
							</li>
							<li class="ticket-item">
								<div class="row">
									<div class="ticket-user">
										<span class="user-name"><?php echo Core::_('Admin.index_tech_date_mb')?></span>
										<span class="user-company"><?php echo function_exists('mb_internal_encoding') ? Core::_('Admin.index_on') : Core::_('Admin.index_off')?></span>
									</div>

									<?php
									if(function_exists('mb_internal_encoding'))
									{
										$divClass = ' bg-palegreen';
										$iClass = ' fa-check';
									}
									else
									{
										$divClass = ' bg-darkorange';
										$iClass = ' fa-times';
									}
									?>
									<div class="ticket-state<?php echo $divClass?>">
										<i class="fa<?php echo $iClass?>"></i>
									</div>
								</div>
							</li>
							<li class="ticket-item">
								<div class="row">
									<div class="ticket-user">
										<span class="user-name"><?php echo Core::_('Admin.index_tech_date_json')?></span>
										<span class="user-company"><?php echo function_exists('json_encode') ? Core::_('Admin.index_on') : Core::_('Admin.index_off')?></span>
									</div>

									<?php
									if(function_exists('json_encode'))
									{
										$divClass = ' bg-palegreen';
										$iClass = ' fa-check';
									}
									else
									{
										$divClass = ' bg-darkorange';
										$iClass = ' fa-times';
									}
									?>
									<div class="ticket-state<?php echo $divClass?>">
										<i class="fa<?php echo $iClass?>"></i>
									</div>
								</div>
							</li>
							<li class="ticket-item">
								<div class="row">
									<div class="ticket-user">
										<span class="user-name"><?php echo Core::_('Admin.index_tech_date_simplexml')?></span>
										<span class="user-company"><?php echo function_exists('simplexml_load_string') ? Core::_('Admin.index_on') : Core::_('Admin.index_off')?></span>
									</div>

									<?php
									if(function_exists('simplexml_load_string'))
									{
										$divClass = ' bg-palegreen';
										$iClass = ' fa-check';
									}
									else
									{
										$divClass = ' bg-darkorange';
										$iClass = ' fa-times';
									}
									?>
									<div class="ticket-state<?php echo $divClass?>">
										<i class="fa<?php echo $iClass?>"></i>
									</div>
								</div>
							</li>
							<li class="ticket-item">
								<div class="row">
									<div class="ticket-user">
										<span class="user-name"><?php echo Core::_('Admin.index_tech_date_iconv') ?></span>
										<span class="user-company"><?php echo function_exists('iconv') ? Core::_('Admin.index_on') : Core::_('Admin.index_off')?></span>
									</div>

									<?php
									if(function_exists('iconv'))
									{
										$divClass = ' bg-palegreen';
										$iClass = ' fa-check';
									}
									else
									{
										$divClass = ' bg-darkorange';
										$iClass = ' fa-times';
									}
									?>
									<div class="ticket-state<?php echo $divClass?>">
										<i class="fa<?php echo $iClass?>"></i>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<?php
		return $this;
	}

	protected function _notesContent()
	{
		$oUser = Core_Entity::factory('User', 0)->getCurrent();

		if (!is_null($oUser))
		{
			$aUser_Notes = $oUser->User_Notes->findAll(FALSE);

			?><div id="overview" class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="widget">
						<div class="widget-header bordered-bottom bordered-darkorange">
							<i class="widget-icon fa fa-tasks darkorange"></i>
							<span class="widget-caption darkorange"><?php echo Core::_('Admin.notes')?></span>
							<div class="widget-buttons">
								<a onclick="$.addNote()">
									<i class="fa fa-plus darkorange" title="Добавить заметку"></i>
								</a>
							</div>
						</div>
						<div class="widget-body">
							<div id="user-notes" class="row">

								<!-- Default note -->
								<div id="default-user-note" class="user-note col-lg-3 col-md-4 col-sm-6 col-xs-12">
									<div class="row">
										<div class="user-note-block">
											<div>
												<textarea></textarea>
											</div>
											<div class="user-note-state bg-darkorange">
												<a data-id="0" onclick="res = confirm('<?php echo Core::_('Admin_form.msg_information_delete')?>'); if (res) { $.destroyNote($(this).parents('div.user-note')) } return false"><i class="fa fa-remove"></i></a>
											</div>
										</div>
									</div>
								</div>
								<script type="text/javascript">
								<?php
								foreach ($aUser_Notes as $oUser_Note)
								{
									?>
									$.createNote({
										'id': <?php echo $oUser_Note->id?>,
										'value': '<?php echo Core_Str::escapeJavascriptVariable($oUser_Note->value)?>'
									});
									<?php
								}
								?>
								</script>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
		}

		return $this;
	}
}