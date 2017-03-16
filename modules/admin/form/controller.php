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
class Admin_Form_Controller
{
	/**
	 * Use skin
	 * @var boolean
	 */
	protected $_skin = TRUE;

	/**
	 * Use AJAX
	 * @var boolean
	 */
	protected $_ajax = FALSE;

	/**
	 * Dataset array
	 * @var array
	 */
	protected $_datasets = array();

	/**
	 * Admin form
	 * @var Admin_Form
	 */
	protected $_Admin_Form = NULL;

	/**
	 * Current language in administrator's center
	 * @var string
	 */
	protected $_Admin_Language = NULL;

	/**
	 * Default form sorting field
	 * @var string
	 */
	protected $_sortingAdmin_Form_Field = NULL;

	/**
	 * String of additional parameters
	 * @var string
	 */
	protected $_additionalParams = NULL;

	/**
	 * Add additional param
	 * @param string $key param name
	 * @param string $value param value
	 * @return self
	 */
	public function addAdditionalParam($key, $value)
	{
		$this->_additionalParams .= '&' . htmlspecialchars($key) . '=' . rawurlencode($value);
		return $this;
	}

	/**
	 * Form setup
	 * @return self
	 */
	public function setUp()
	{
		if (!defined('DISABLE_COMPRESSION') || !DISABLE_COMPRESSION)
		{
			// Если сжатие уже не включено на сервере директивой zlib.output_compression = On
			// http://php.net/manual/en/function.ini-get.php
			// A boolean ini value of off will be returned as an empty string or "0"
			// while a boolean ini value of on will be returned as "1".
			// The function can also return the literal string of INI value.

			// MSIE 8.0 has problem with the fastpage-enabled content was not being un-gzipped
			if (/*strpos(Core_Array::get($_SERVER, 'HTTP_USER_AGENT'), 'MSIE 8.0') === FALSE
				&& */ini_get('zlib.output_compression') == 0)
			{
				// включаем сжатие буфера вывода
				ob_start("ob_gzhandler");
			}
		}

		Core::initConstants(Core_Entity::factory('Site', CURRENT_SITE));

		$aTmp = array();
		foreach ($_GET as $key => $value)
		{
			if (!is_array($value))
			{
				//$aTmp[] = htmlspecialchars($key, ENT_QUOTES) . '=' . htmlspecialchars($value, ENT_QUOTES);
				$aTmp[] = htmlspecialchars($key) . '=' . rawurlencode($value);
			}
		}

		$this->_additionalParams = implode('&', $aTmp);

		$this->formSettings();
		return $this;
	}

	/**
	 * Data set from _REQUEST
	 * @var array
	 */
	public $request = array();

	/**
	 * Apply form settings
	 * @return self
	 */
	public function formSettings()
	{
		$this->request = $_REQUEST;

		$formSettings = Core_Array::get($this->request, 'hostcms', array())
			+ array(
				'limit' => NULL,
				'current' => NULL,
				'sortingfield' => NULL,
				'sortingdirection' => NULL,
				'action' => NULL,
				'operation' => NULL,
				'window' => 'id_content',
				'checked' => array()
			);

		$this
			->limit($formSettings['limit'])
			->current($formSettings['current'])
			->sortingDirection($formSettings['sortingdirection'])
			->sortingFieldId($formSettings['sortingfield'])
			->action($formSettings['action'])
			->operation($formSettings['operation'])
			->checked($formSettings['checked'])
			->window($formSettings['window'])
			->ajax(Core_Array::get($this->request, '_', FALSE));

		$oUserCurrent = Core_Entity::factory('User', 0)->getCurrent();

		if ($oUserCurrent && $this->_Admin_Form)
		{
			$user_id = is_null($oUserCurrent) ? 0 : $oUserCurrent->id;

			$oAdmin_Form_Setting = $this->_Admin_Form->getSettingForUser(
				$user_id
			);

			$bAdmin_Form_Setting_Already_Exists = $oAdmin_Form_Setting;

			if (!$bAdmin_Form_Setting_Already_Exists)
			{
				$oAdmin_Form_Setting = Core_Entity::factory('Admin_Form_Setting');

				// Связываем с формой и пользователем сайта
				$this->_Admin_Form->add($oAdmin_Form_Setting);
				$oUserCurrent->add($oAdmin_Form_Setting);
			}

			!is_null($this->_limit) && $oAdmin_Form_Setting->on_page = intval($this->_limit);
			!is_null($this->_current) && $oAdmin_Form_Setting->page_number = intval($this->_current);

			if (!is_null($this->_sortingFieldId))
			{
				$oAdmin_Form_Setting->order_field_id = intval($this->_sortingFieldId);
			}
			// Восстанавливаем сохраненный
			elseif ($bAdmin_Form_Setting_Already_Exists)
			{
				$this->_sortingFieldId = $oAdmin_Form_Setting->order_field_id;
			}

			if (!is_null($this->_sortingDirection))
			{
				$oAdmin_Form_Setting->order_direction = intval($this->_sortingDirection);
			}
			// Восстанавливаем сохраненный
			elseif ($bAdmin_Form_Setting_Already_Exists)
			{
				$this->_sortingDirection = $oAdmin_Form_Setting->order_direction;
			}

			$oAdmin_Form_Setting->save();
		}

		// Добавляем замену для windowId
		$this->_externalReplace['{windowId}'] = $this->getWindowId();
		return $this;
	}

	/**
	 * Path
	 * @var string
	 */
	protected $_path = NULL;

	/**
	 * Set path
	 * @param string $path path
	 * @return self
	 */
	public function path($path)
	{
		$this->_path = $path;

		// Добавляем замену для path
		$this->_externalReplace['{path}'] = $this->_path;

		return $this;
	}

	/**
	 * Get path
	 * @return string
	 */
	public function getPath()
	{
		return $this->_path;
	}

	/**
	 * Array of checked items
	 * @var array
	 */
	protected $_checked = array();

	/**
	 * Set checked items on the form
	 * Выбранные элементы в форме
	 * @param array $checked checked items
	 * @return self
	 */
	public function checked(array $checked)
	{
		$this->_checked = $checked;
		return $this;
	}

	/**
	 * Get checked items on the form
	 * @return array
	 */
	public function getChecked()
	{
		return $this->_checked;
	}

	/**
	 * Clear checked items on the form
	 * @return self
	 */
	public function clearChecked()
	{
		$this->_checked = array();
		return $this;
	}

	/**
	 * List of handlers' actions
	 * @var array
	 */
	protected $_actionHandlers = array();

	/**
	 * Добавление обработчика действия
	 * @param Admin_Form_Action_Controller $oAdmin_Form_Action_Controller action controller
	 */
	public function addAction(Admin_Form_Action_Controller $oAdmin_Form_Action_Controller)
	{
		// Set link to controller
		$oAdmin_Form_Action_Controller->controller($this);

		$this->_actionHandlers[$oAdmin_Form_Action_Controller->getName()] = $oAdmin_Form_Action_Controller;
	}

	/**
	 * List of children entities
	 * @var array
	 */
	protected $_children = array();

	/**
	 * Add entity
	 * @param Admin_Form_Entity $oAdmin_Form_Entity
	 * @return self
	 */
	public function addEntity(Admin_Form_Entity $oAdmin_Form_Entity)
	{
		// Set link to controller
		$oAdmin_Form_Entity->controller($this);
		$this->_children[] = $oAdmin_Form_Entity;
		return $this;
	}

	/**
	 * List of external replaces
	 * @var array
	 */
	protected $_externalReplace = array();

	/**
	* Add external replacement
	* Добавление внешней подстановки
	* @param string $key name of  replacement
	* @param string $value value of replacement
	* @return self
	*/
	public function addExternalReplace($key, $value)
	{
		$this->_externalReplace[$key] = $value;
		return $this;
	}

	/**
	 * Constructor.
	 * @param Admin_Form_Model $oAdmin_Form admin form
	 */
	public function __construct(Admin_Form_Model $oAdmin_Form = NULL)
	{
		$this->_Admin_Form = $oAdmin_Form;

		//$this->_Admin_Form->_load();

		if ($oAdmin_Form)
		{
			if (is_null($this->_Admin_Form->key_field))
			{
				throw new Core_Exception('Admin form does not exist.');
			}

			$this->_Admin_Language = Core_Entity::factory('Admin_Language')->getCurrent();

			$formName = $this->_Admin_Form->Admin_Word->getWordByLanguage($this->_Admin_Language->id);

			if ($formName->name)
			{
				$this->_title = $formName->name;
				$this->_pageTitle = $formName->name;
			}

			$sortingFieldName = $this->_Admin_Form->default_order_field;

			$this->_sortingAdmin_Form_Field = $this->_Admin_Form->Admin_Form_Fields->getByName($sortingFieldName);

			if (is_null($this->_sortingAdmin_Form_Field))
			{
				throw new Core_Exception("Default form sorting field '%sortingFieldName' does not exist.",
					array ('%sortingFieldName' => $sortingFieldName)
				);
			}

			$oUserCurrent = Core_Entity::factory('User', 0)->getCurrent();
			$user_id = is_null($oUserCurrent) ? 0 : $oUserCurrent->id;

			$oAdmin_Form_Setting = $this->_Admin_Form->getSettingForUser(
				$user_id
			);

			// Данные поля сортировки и направления из настроек пользователя
			if ($oAdmin_Form_Setting)
			{
				$this
					->limit($oAdmin_Form_Setting->on_page)
					->current($oAdmin_Form_Setting->page_number)
					->sortingFieldId($oAdmin_Form_Setting->order_field_id)
					->sortingDirection($oAdmin_Form_Setting->order_direction);
			}
			else
			{
				// Данные по умолчанию из настроек формы
				$this->sortingFieldId(
					$this->_Admin_Form->Admin_Form_Fields
						->getByName($this->_Admin_Form->default_order_field)->id
				)
				->sortingDirection($this->_Admin_Form->default_order_direction);
			}
		}

		// Current path
		$this->path($_SERVER['PHP_SELF']);
	}

	/**
	 * Is showing operations necessary
	 * @var boolean
	 */
	protected $_showOperations = TRUE;

	/**
	 * Display operations of the form
	 * @param boolean $showOperations mode
	 * @return self
	 */
	public function showOperations($showOperations)
	{
		$this->_showOperations = $showOperations;
		return $this;
	}

	/**
	 * List of filter handlers
	 * @var array
	 */
	protected $_filters = array();

	/**
	 * Add handler of the filter
	 * Добавление обработчика фильтра
	 * @param string $fieldName field name
	 * @param string $function function name
	 * @return self
	 */
	public function addFilter($fieldName, $function)
	{
		$this->_filters[$fieldName] = $function;
		return $this;
	}

	/**
	 * Is showing filter necessary
	 * @var boolean
	 */
	protected $_showFilter = TRUE;

	/**
	 * Show filter of the form
	 * @param boolean $showFilter mode
	 * @return self
	 */
	public function showFilter($showFilter)
	{
		$this->_showFilter = $showFilter;
		return $this;
	}

	/**
	 * Is showing list of action necessary
	 * @var boolean
	 */
	protected $_showBottomActions = TRUE;

	/**
	 * Display actions of the bottom of the form
	 * @param boolean $showBottomActions mode
	 * @return self
	 */
	public function showBottomActions($showBottomActions)
	{
		$this->_showBottomActions = $showBottomActions;
		return $this;
	}

	/**
	 * Page title <h1>
	 */
	protected $_title = NULL;

	/**
	 * Set <h1> for form
	 * @param string $title content
	 * @return self
	 */
	public function title($title)
	{
		$this->_title = $title;
		return $this;
	}

	/**
	 * Page title <title>
	 * @var string
	 */
	protected $_pageTitle = NULL;

	/**
	 * Set page <title>
	 * @param $pageTitle title
	 * @return self
	 */
	public function pageTitle($pageTitle)
	{
		$this->_pageTitle = $pageTitle;
		return $this;
	}

	/**
	 * Get page <title>
	 * @return string
	 */
	public function getPageTitle()
	{
		return $this->_pageTitle;
	}

	/**
	 * Limits elements on page
	 * @var int
	 */
	protected $_limit = ON_PAGE;

	/**
	 * Current page
	 * @var int
	 */
	protected $_current = 1; // счет с 1

	/**
	 * Set limit of elements on page
	 * @param int $limit count
	 * @return self
	 */
	public function limit($limit)
	{
		$limit = intval($limit);

		if ($limit > 0)
		{
			$this->_limit = $limit;
		}
		return $this;
	}

	/**
	 * Set current page
	 * @param int $current page
	 * @return self
	 */
	public function current($current)
	{
		$current = intval($current);

		if ($current > 0)
		{
			$this->_current = intval($current);
		}
		return $this;
	}

	/**
	 * Action name
	 * @var string
	 */
	protected $_action = NULL;

	/**
	 * Set action
	 * @param string $action action
	 * @return self
	 */
	public function action($action)
	{
		$this->_action = $action;
		return $this;
	}

	/**
	 * Get action
	 * @return string
	 */
	public function getAction()
	{
		return $this->_action;
	}

	/**
	 * Action operation e.g. "save" or "apply"
	 */
	protected $_operation = NULL;

	/**
	 * Set operation
	 * @param string $operation operation
	 * @return self
	 */
	public function operation($operation)
	{
		$this->_operation = $operation;
		return $this;
	}

	/**
	 * Get operation
	 * @return string
	 */
	public function getOperation()
	{
		return $this->_operation;
	}

	/**
	 * Set AJAX
	 * @param boolean $ajax ajax
	 * @return self
	 */
	public function ajax($ajax)
	{
		$this->_ajax = ($ajax != FALSE);
		return $this;
	}

	/**
	 * Get AJAX
	 * @return boolean
	 */
	public function getAjax()
	{
		return $this->_ajax;
	}

	/**
	 * Show skin
	 * @param boolean $skin use skin mode
	 * @return self
	 */
	public function skin($skin)
	{
		$this->_skin = ($skin != FALSE);
		return $this;
	}

	/**
	 * Add dataset
	 * @param Admin_Form_Dataset $oAdmin_Form_Dataset dataset
	 * @return self
	 */
	public function addDataset(Admin_Form_Dataset $oAdmin_Form_Dataset)
	{
		$this->_datasets[] = $oAdmin_Form_Dataset->controller($this);
		return $this;
	}

	/**
	 * Get dataset
	 * @param int $key index
	 * @return Admin_Form_Dataset|NULL
	 */
	public function getDataset($key)
	{
		return isset($this->_datasets[$key])
			? $this->_datasets[$key]
			: NULL;
	}

	/**
	 * Window ID
	 * @var int
	 */
	protected $_windowId = NULL;

	/**
	 * Set window ID
	 * @param int $windowId ID
	 * @return self
	 */
	public function window($windowId)
	{
		$this->_windowId = $windowId;
		return $this;
	}

	/**
	 * Get window ID
	 * @return int
	 */
	public function getWindowId()
	{
		return $this->_windowId;
	}

	/**
	 * Count of elements on page
	 * @var array
	 */
	protected $_onPage = array (10 => 10, 20 => 20, 30 => 30, /*40 => 40, */50 => 50, 100 => 100, 500 => 500, 1000 => 1000);

	/**
	 * Show items count selector
	 */
	protected function _onPageSelector()
	{
		$sCurrentValue = $this->_limit;
		$windowId = Core_Str::escapeJavascriptVariable($this->getWindowId());
		$additionalParams = Core_Str::escapeJavascriptVariable(
			str_replace(array('"'), array('&quot;'), $this->_additionalParams)
		);
 		$path = Core_Str::escapeJavascriptVariable($this->getPath());

		$oCore_Html_Entity_Select = Core::factory('Core_Html_Entity_Select')
			->name('admin_forms_on_page')
			->id('id_on_page')
			->onchange("$.adminLoad({path: '{$path}', additionalParams: '{$additionalParams}', limit: this.options[this.selectedIndex].value, windowId : '{$windowId}'}); return false")
			->options($this->_onPage)
			->value($sCurrentValue)
			->execute();
	}

	/**
	 * Total founded items
	 * @var int
	 */
	protected $_totalCount = NULL;

	/**
	 * Get count of total founded items
	 * @return int
	 */
	public function getTotalCount()
	{
		if (is_null($this->_totalCount))
		{
			try
			{
				foreach ($this->_datasets as $oAdmin_Form_Dataset)
				{
					$this->_totalCount += $oAdmin_Form_Dataset->getCount();
				}
			}
			catch (Exception $e)
			{
				Core_Message::show($e->getMessage(), 'error');
			}
		}

		return $this->_totalCount;
	}

	/**
	 * Count of links to next pages
	 * @var int
	 */
	protected $_pageNavigationDelta = 5;

	/**
	* Показ строки ссылок
	*/
	protected function _pageNavigation()
	{
		$total_count = $this->getTotalCount();
		$total_page = $total_count / $this->_limit;

		// Округляем в большую сторону
		if ($total_count % $this->_limit != 0)
		{
			$total_page = intval($total_page) + 1;
		}

		$this->_current > $total_page && $this->_current = $total_page;

		$oCore_Html_Entity_Div = Core::factory('Core_Html_Entity_Div')
			->style('float: left; text-align: center; margin-top: 10px');

		// Формируем скрытые ссылки навигации для перехода по Ctrl + стрелка
		if ($this->_current < $total_page)
		{
			// Ссылка на следующую страницу
			$page = $this->_current + 1 ? $this->_current + 1 : 1;
			$oCore_Html_Entity_Div->add(
				Core::factory('Core_Html_Entity_A')
					->onclick($this->getAdminLoadAjax($this->getPath(), NULL, NULL, NULL, NULL, $page))
					->id('id_next')
			);
		}

		if ($this->_current > 1)
		{
			// Ссылка на предыдущую страницу
			$page = $this->_current - 1 ? $this->_current - 1 : 1;
			$oCore_Html_Entity_Div->add(
				Core::factory('Core_Html_Entity_A')
					->onclick($this->getAdminLoadAjax($this->getPath(), NULL, NULL, NULL, NULL, $page))
					->id('id_prev')
			);
		}

		// Отображаем строку ссылок, если общее число страниц больше 1.
		if ($total_page > 1)
		{
			// Определяем номер ссылки, с которой начинается строка ссылок.
			$link_num_begin = ($this->_current - $this->_pageNavigationDelta < 1)
				? 1
				: $this->_current - $this->_pageNavigationDelta;

			// Определяем номер ссылки, которой заканчивается строка ссылок.
			$link_num_end = $this->_current + $this->_pageNavigationDelta;
			$link_num_end > $total_page && $link_num_end = $total_page;

			// Определяем число ссылок выводимых на страницу.
			$count_link = $link_num_end - $link_num_begin + 1;

			if ($this->_current == 1)
			{
				$oCore_Html_Entity_Div->add(
					Core::factory('Core_Html_Entity_Span')
						->class('current')
						->value($link_num_begin)
				);
			}
			else
			{
				$href = $this->getAdminLoadHref($this->getPath(), NULL, NULL, NULL, NULL, 1);
				$onclick = $this->getAdminLoadAjax($this->getPath(), NULL, NULL, NULL, NULL, 1);

				$oCore_Html_Entity_Div->add(
					Core::factory('Core_Html_Entity_A')
						->href($href)
						->onclick($onclick)
						->class('page_link')
						->value(1)
				);

				// Выведем … со ссылкой на 2-ю страницу, если показываем с 3-й
				if ($link_num_begin > 1)
				{
					$href = $this->getAdminLoadHref($this->getPath(), NULL, NULL, NULL, NULL, 2);
					$onclick = $this->getAdminLoadAjax($this->getPath(), NULL, NULL, NULL, NULL, 2);

					$oCore_Html_Entity_Div->add(
						Core::factory('Core_Html_Entity_A')
							->href($href)
							->onclick($onclick)
							->class('page_link')
							->value('…')
					);
				}
			}

			// Страница не является первой и не является последней.
			for ($i = 1; $i < $count_link - 1; $i++)
			{
				$link_number = $link_num_begin + $i;

				if ($link_number == $this->_current)
				{
					// Страница является текущей
					$oCore_Html_Entity_Div->add(
						Core::factory('Core_Html_Entity_Span')
							->class('current')
							->value($link_number)
					);
				}
				else
				{
					$href = $this->getAdminLoadHref($this->getPath(), NULL, NULL, NULL, NULL, $link_number);
					$onclick = $this->getAdminLoadAjax($this->getPath(), NULL, NULL, NULL, NULL, $link_number);
					$oCore_Html_Entity_Div->add(
						Core::factory('Core_Html_Entity_A')
							->href($href)
							->onclick($onclick)
							->class('page_link')
							->value($link_number)
					);
				}
			}

			// Если последняя страница является текущей
			if ($this->_current == $total_page)
			{
				$oCore_Html_Entity_Div->add(
					Core::factory('Core_Html_Entity_Span')
							->class('current')
							->value($total_page)
				);
			}
			else
			{
				// Выведем … со ссылкой на предпоследнюю страницу
				if ($link_num_end < $total_page)
				{
					$href = $this->getAdminLoadHref($this->getPath(), NULL, NULL, NULL, NULL, $total_page - 1);
					$onclick = $this->getAdminLoadAjax($this->getPath(), NULL, NULL, NULL, NULL, $total_page - 1);

					$oCore_Html_Entity_Div->add(
						Core::factory('Core_Html_Entity_A')
							->href($href)
							->onclick($onclick)
							->class('page_link')
							->value('…')
					);
				}

				$href = $this->getAdminLoadHref($this->getPath(), NULL, NULL, NULL, NULL, $total_page);
				$onclick = $this->getAdminLoadAjax($this->getPath(), NULL, NULL, NULL, NULL, $total_page);

				// Последняя страница не является текущей
				$oCore_Html_Entity_Div->add(
					Core::factory('Core_Html_Entity_A')
						->href($href)
						->onclick($onclick)
						->class('page_link')
						->value($total_page)
				);
			}

			$oCore_Html_Entity_Div->execute();
			Core::factory('Core_Html_Entity_Div')
				->style('clear: both')
				->execute();
		}
	}

	/**
	 * Content
	 * @var string
	 */
	protected $_content = NULL;

	/**
	 * Message text
	 * @var string
	 */
	protected $_message = NULL;

	/**
	 * Get content message
	 * @return string
	 */
	public function getContent()
	{
		return $this->_content;
	}

	/**
	 * Add content for administration center form
	 * @param string $content content
	 * @return self
	 */
	public function addContent($content)
	{
		$this->_content .= $content;
		return $this;
	}

	/**
	 * Get form message
	 * @return string
	 */
	public function getMessage()
	{
		return $this->_message;
	}

	/**
	 * Add message for administration center form
	 * @param string $message message
	 * @return self
	 */
	public function addMessage($message)
	{
		$this->_message .= $message;
		return $this;
	}

	/**
	 * Show built data
	 */
	public function show()
	{
		$oAdmin_Answer = Core_Skin::instance()->answer();

		$oAdmin_Answer
			->ajax($this->_ajax)
			->skin($this->_skin)
			->content($this->getContent())
			->message($this->getMessage())
			->title($this->_title)
			->execute();
	}

	/**
	 * Executes the business logic.
	 */
	public function execute()
	{
		ob_start();

		if (!empty($this->_action))
		{
			$actionName = $this->_action;

			$aReadyAction = array();

			try
			{
				// Текущий пользователь
				$oUser = Core_Entity::factory('User')->getCurrent();

				// Read Only режим
				if (defined('READ_ONLY') && READ_ONLY || $oUser->read_only && !$oUser->superuser)
				{
					throw new Core_Exception(
						Core::_('User.demo_mode'), array(), 0, FALSE
					);
				}

				// Доступные действия для пользователя
				$aAdmin_Form_Actions = $this->_Admin_Form->Admin_Form_Actions->getAllowedActionsForUser($oUser);

				$bActionAllowed = FALSE;

				// Проверка на право доступа к действию
				foreach ($aAdmin_Form_Actions as $oAdmin_Form_Action)
				{
					if ($oAdmin_Form_Action->name == $actionName)
					{
						$bActionAllowed = TRUE;
						break;
					}
				}

				if ($bActionAllowed)
				{
					foreach ($this->_checked as $datasetKey => $checkedItems)
					{
						foreach ($checkedItems as $checkedItemId => $v1)
						{
							if (isset($this->_datasets[$datasetKey]))
							{
								$oObject = $this->_datasets[$datasetKey]->getObject($checkedItemId);

								// Проверка на доступность действия к dataset
								if ($oAdmin_Form_Action->dataset != -1
									&& $oAdmin_Form_Action->dataset != $datasetKey)
								{
									break;
								}

								// Проверка через user_id на право выполнения действия над объектом
								$bAccessToObject = $oUser->checkObjectAccess($oObject);

								if (!$bAccessToObject)
								{
									throw new Core_Exception(
										Core::_('User_Module.error_object_owned_another_user'), array(), 0, FALSE
									);
								}

								if (isset($this->_actionHandlers[$actionName]))
								{
									$actionResult = $this->_actionHandlers[$actionName]
										->setDatasetId($datasetKey)
										->setObject($oObject)
										->execute($this->_operation);

									$this->addMessage(
										$this->_actionHandlers[$actionName]->getMessage()
									);

									$this->addContent(
										$this->_actionHandlers[$actionName]->getContent()
									);
								}
								else
								{
									// Уже есть выше при проверке права доступа к действию, если действие было, то здесь также есть доступ
									// Проверяем наличие действия с такими именем у формы
									/*$oAdmin_Form_Action = $this->_Admin_Form->Admin_Form_Actions->getByName($actionName);
									if (!is_null($oAdmin_Form_Action))
									{*/
										$actionResult = $oObject->$actionName();
									/*}
									else
									{
										throw new Core_Exception('Action "%actionName" does not exist.',
											array('%actionName' => $actionName)
										);
									}*/
								}

								// Действие вернуло TRUE, прерываем выполнение
								if ($actionResult === TRUE)
								{
									$this->addMessage(ob_get_clean())
										->addContent('')
										->pageTitle('')
										->title('');
									return $this->show();
								}
								elseif ($actionResult !== NULL)
								{
									$aReadyAction[$oObject->getModelName()] = isset($aReadyAction[$datasetKey])
										? $aReadyAction[$datasetKey] + 1
										: 1;
								}
							}
							else
							{
								throw new Core_Exception('Dataset %datasetKey does not exist.',
									array('%datasetKey' => $datasetKey)
								);
							}
						}
					}
				}
				else
				{
					throw new Core_Exception(
						Core::_('Admin_Form.msg_error_access'), array(), 0, FALSE
					);
				}
			}
			catch (Exception $e)
			{
				Core_Message::show($e->getMessage(), 'error');
			}

			// были успешные операции
			foreach ($aReadyAction as $modelName => $actionChangedCount)
			{
				Core_Message::show(Core::_("{$modelName}.{$actionName}_success", NULL, $actionChangedCount));
			}
		}

		$this
			->addMessage(ob_get_clean())
			->addContent($this->_getForm())
			->show();
	}

	/**
	 * Show children elements
	 * @return self
	 */
	public function showChildren()
	{
		// Связанные с формой элементы (меню, строка навигации и т.д.)
		foreach ($this->_children as $oAdmin_Form_Entity)
		{
			$oAdmin_Form_Entity->execute();
		}

		return $this;
	}

	/**
	 * Show form title in administration center
	 * @return self
	 */
	protected function _showFormTitle()
	{
		// Заголовок формы
		if (!is_null($this->_pageTitle) && strlen($this->_pageTitle) > 0)
		{
			// Заголовок
			Core::factory('Admin_Form_Entity_Title')
				->name($this->_pageTitle)
				->execute();
		}

		return $this;
	}

	/**
	 * Show form content in administration center
	 * @return self
	 */
	protected function _showFormContent()
	{
		$aAdmin_Form_Fields = $this->_Admin_Form->Admin_Form_Fields->findAll();

		if (empty($aAdmin_Form_Fields))
		{
			throw new Core_Exception('Admin form does not have fields.');
		}

		$windowId = $this->getWindowId();

		$allow_filter = FALSE;

		?><table width="100%" cellpadding="2" cellspacing="2" class="admin_table"><?php
		?><tr class="admin_table_title"><?php

		// Ячейку над групповыми чекбоксами показываем только при наличии действий
		if ($this->_Admin_Form->show_operations && $this->_showOperations)
		{
			?><td width="25">&nbsp;</td><?php
		}

		foreach ($aAdmin_Form_Fields as $iAdmin_Form_Field_key => $oAdmin_Form_Field)
		{
			// Если был хотя бы один фильтр
			$oAdmin_Form_Field->allow_filter && $allow_filter = TRUE;

			$align = $oAdmin_Form_Field->align
				? ' align="' . htmlspecialchars($oAdmin_Form_Field->align) . '"'
				: '';

			$width = htmlspecialchars(trim($oAdmin_Form_Field->width));

			$Admin_Word_Value = $oAdmin_Form_Field->Admin_Word->getWordByLanguage($this->_Admin_Language->id);

			// Слово найдено
			$fieldName = $Admin_Word_Value && strlen($Admin_Word_Value->name) > 0
				? htmlspecialchars($Admin_Word_Value->name)
				: '&mdash;';

			// Определяем нужно ли отображать стрелки сортировки
			ob_start();

			// Не подсвечивать
			$highlight = FALSE;

			if ($oAdmin_Form_Field->allow_sorting)
			{
				$hrefDown = $this->getAdminLoadHref($this->getPath(), NULL, NULL, NULL, NULL, NULL, $oAdmin_Form_Field->id, 1);
				$onclickDown = $this->getAdminLoadAjax($this->getPath(), NULL, NULL, NULL, NULL, NULL, $oAdmin_Form_Field->id, 1);

				$hrefUp = $this->getAdminLoadHref($this->getPath(), NULL, NULL, NULL, NULL, NULL, $oAdmin_Form_Field->id, 0);
				$onclickUp = $this->getAdminLoadAjax($this->getPath(), NULL, NULL, NULL, NULL, NULL, $oAdmin_Form_Field->id, 0);

				if ($oAdmin_Form_Field->id == $this->_sortingFieldId)
				{
					// Подсвечивать
					$highlight = TRUE;

					if ($this->_sortingDirection == 0)
					{
						?><img src="/admin/images/arrow_up.gif" alt="&uarr" /> <?php
						?><a href="<?php echo $hrefDown?>" onclick="<?php echo $onclickDown?>"><img src="/admin/images/arrow_down_gray.gif" alt="&darr" /></a><?php
					}
					else
					{
						?><a href="<?php echo $hrefUp?>" onclick="<?php echo $onclickUp?>"><img src="/admin/images/arrow_up_gray.gif" alt="&uarr" /></a> <?php
						?><img src="/admin/images/arrow_down.gif" alt="&darr" /><?php
					}
				}
				else
				{
					?><a href="<?php echo $hrefUp?>" onclick="<?php echo $onclickUp?>"><img src="/admin/images/arrow_up_gray.gif" alt="&uarr" /></a> <?php
					?><a href="<?php echo $hrefDown?>" onclick="<?php echo $onclickDown?>"><img src="/admin/images/arrow_down_gray.gif" alt="&darr" /></a><?php
				}
			}

			$sort_arrows = ob_get_clean();

			?><td <?php if (!empty($width)) { echo 'width="' . $width . '"'; }?><?php echo $align?><?php echo $highlight ? ' class="hl"' : ''?>><?php
				?><nobr><?php echo $fieldName?> <?php echo $sort_arrows?></nobr><?php
			?></td><?php
		}

		// Текущий пользователь
		$oUser = Core_Entity::factory('User')->getCurrent();

		// Доступные действия для пользователя
		$aAllowed_Admin_Form_Actions = $this->_Admin_Form->Admin_Form_Actions->getAllowedActionsForUser($oUser);

		if ($this->_Admin_Form->show_operations && $this->_showOperations
		|| $allow_filter && $this->_showFilter)
		{
			/*if (isset($this->form_params['actions_width']))
			{
				$width = Core_Type_Conversion::toStr($this->form_params['actions_width']);
			}
			else
			{*/
				// min width action column
				$width = 10;

				foreach ($aAllowed_Admin_Form_Actions as $o_Admin_Form_Action)
				{
					// Отображаем действие, только если разрешено.
					if ($o_Admin_Form_Action->single)
					{
						$width += 16;
					}
				}
			//}

			?><td width="<?php echo $width?>">&nbsp;</td><?php
		}
		?></tr><?php
		?><tr class="admin_table_filter"><?php
		// Чекбокс "Выбрать все" показываем только при наличии действий
		if ($this->_Admin_Form->show_operations && $this->_showOperations)
		{
			?><td align="center" width="25"><input type="checkbox" name="admin_forms_all_check" id="id_admin_forms_all_check" onclick="$('#<?php echo $windowId?>').highlightAllRows(this.checked)" /></td><?php
		}

		// Фильтр.
		foreach ($aAdmin_Form_Fields as $iAdmin_Form_Field_key => $oAdmin_Form_Field)
		{
			// Перекрытие параметров для данного поля
			foreach ($this->_datasets as $datasetKey => $oAdmin_Form_Dataset)
			{
				$oAdmin_Form_Field_Changed = $this->_changeField($oAdmin_Form_Dataset, $oAdmin_Form_Field);
			}

			$width = htmlspecialchars(trim($oAdmin_Form_Field->width));

			// Подсвечивать
			$highlight = $oAdmin_Form_Field->allow_sorting
				? ($oAdmin_Form_Field->id == $this->_sortingAdmin_Form_Field->id)
				: FALSE;

			?><td <?php echo !empty($width) ? 'width="'.$width.'"' : ''?><?php echo $highlight ? ' class="hl"' : ''?>><?php

				if ($oAdmin_Form_Field->allow_filter)
				{
					$value = trim(Core_Array::get($this->request, "admin_form_filter_{$oAdmin_Form_Field->id}"));

					// Функция обратного вызова для фильтра
					if (isset($this->_filters[$oAdmin_Form_Field->name]))
					{
						switch ($oAdmin_Form_Field->type)
						{
							case 1: // Строка
							case 2: // Поле ввода
							case 4: // Ссылка
							case 10: // Функция обратного вызова
							case 3: // Checkbox.
							case 8: // Выпадающий список
								echo call_user_func($this->_filters[$oAdmin_Form_Field->name], $value, $oAdmin_Form_Field);
							break;

							case 5: // Дата-время.
							case 6: // Дата.
								$date_from = Core_Array::get($this->request, "admin_form_filter_from_{$oAdmin_Form_Field->id}", NULL);
								$date_to = Core_Array::get($this->request, "admin_form_filter_to_{$oAdmin_Form_Field->id}", NULL);

								echo call_user_func($this->_filters[$oAdmin_Form_Field->name], $date_from, $date_to, $oAdmin_Form_Field);
							break;
						}
					}
					else
					{
						$style = !empty($width)
							? "width: {$width};"
							: "width: 97%;";

						switch ($oAdmin_Form_Field->type)
						{
							case 1: // Строка
							case 2: // Поле ввода
							case 4: // Ссылка
							case 10: // Функция обратного вызова
								$value = htmlspecialchars($value);
								?><input type="text" name="admin_form_filter_<?php echo $oAdmin_Form_Field->id?>" id="id_admin_form_filter_<?php echo $oAdmin_Form_Field->id?>" value="<?php echo $value?>" style="<?php echo $style?>" /><?php
							break;

							case 3: // Checkbox.
								?><select name="admin_form_filter_<?php echo $oAdmin_Form_Field->id?>" id="id_admin_form_filter_<?php echo $oAdmin_Form_Field->id?>">
									<option value="0" <?php echo $value == 0 ? "selected" : ''?>><?php echo htmlspecialchars(Core::_('Admin_Form.filter_selected_all'))?></option>
									<option value="1" <?php echo $value == 1 ? "selected" : ''?>><?php echo htmlspecialchars(Core::_('Admin_Form.filter_selected'))?></option>
									<option value="2" <?php echo $value == 2 ? "selected" : ''?>><?php echo htmlspecialchars(Core::_('Admin_Form.filter_not_selected'))?></option>
								</select><?php
							break;

							case 5: // Дата-время.
								$date_from = Core_Array::get($this->request, "admin_form_filter_from_{$oAdmin_Form_Field->id}", NULL);
								$date_from = htmlspecialchars($date_from);

								$date_to = Core_Array::get($this->request, "admin_form_filter_to_{$oAdmin_Form_Field->id}", NULL);
								$date_to = htmlspecialchars($date_to);

								?><input type="text" name="admin_form_filter_from_<?php echo $oAdmin_Form_Field->id?>" id="id_admin_form_filter_from_<?php echo $oAdmin_Form_Field->id?>" value="<?php echo $date_from?>" size="17" class="calendar_field" />
								<div><input type="text" name="admin_form_filter_to_<?php echo $oAdmin_Form_Field->id?>" id="id_admin_form_filter_to_<?php echo $oAdmin_Form_Field->id?>" value="<?php echo $date_to?>" size="17" class="calendar_field" /></div>
								<script type="text/javascript">
								(function($) {
									$("#id_admin_form_filter_from_<?php echo $oAdmin_Form_Field->id?>").datetimepicker({showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true, timeFormat: 'hh:mm:ss'});
									$("#id_admin_form_filter_to_<?php echo $oAdmin_Form_Field->id?>").datetimepicker({showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true, timeFormat: 'hh:mm:ss'});
								})(jQuery);
								</script><?php
							break;

							case 6: // Дата.
								$date_from = Core_Array::get($this->request, "admin_form_filter_from_{$oAdmin_Form_Field->id}", NULL);
								$date_from = htmlspecialchars($date_from);

								$date_to = Core_Array::get($this->request, "admin_form_filter_to_{$oAdmin_Form_Field->id}", NULL);
								$date_to = htmlspecialchars($date_to);

								?><input type="text" name="admin_form_filter_from_<?php echo $oAdmin_Form_Field->id?>" id="id_admin_form_filter_from_<?php echo $oAdmin_Form_Field->id?>" value="<?php echo $date_from?>" size="8" class="calendar_field" />
								<div><input type="text" name="admin_form_filter_to_<?php echo $oAdmin_Form_Field->id?>" id="id_admin_form_filter_to_<?php echo $oAdmin_Form_Field->id?>" value="<?php echo $date_to?>" size="8" class="calendar_field" /></div>
								<script type="text/javascript">
								(function($) {
									$("#id_admin_form_filter_from_<?php echo $oAdmin_Form_Field->id?>").datepicker({showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true});
									$("#id_admin_form_filter_to_<?php echo $oAdmin_Form_Field->id?>").datepicker({showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true});
								})(jQuery);
								</script>
								<?php
							break;

							case 8: // Выпадающий список.

							?><select name="admin_form_filter_<?php echo $oAdmin_Form_Field->id?>" id="id_admin_form_filter_<?php echo $oAdmin_Form_Field->id?>" style="<?php echo $style?>">
							<option value="HOST_CMS_ALL" <?php echo $value == 'HOST_CMS_ALL' ? "selected" : ''?>><?php echo htmlspecialchars(Core::_('Admin_Form.filter_selected_all'))?></option>
							<?php
							$str_array = explode("\n", $oAdmin_Form_Field_Changed->list);
							$value_array = array();

							foreach ($str_array as $str_value)
							{
								// Каждую строку разделяем по равно
								$str_explode = explode('=', $str_value);

								if ($str_explode[0] != 0 && count($str_explode) > 1)
								{
									// сохраняем в массив варинаты значений и ссылки для них
									$value_array[intval(trim($str_explode[0]))] = trim($str_explode[1]);

									?><option value="<?php echo htmlspecialchars($str_explode[0])?>" <?php echo $value == $str_explode[0] ? "selected" : ''?>><?php echo htmlspecialchars(trim($str_explode[1]))?></option><?php
								}
							}
							?>
							</select>
							<?php
							break;

							default:
							?><div style="color: #CEC3A3; text-align: center">&mdash;</div><?php
							break;
						}
					}
				}
				else
				{
					// Фильтр не разрешен.
					?><div style="color: #CEC3A3; text-align: center">&mdash;</div><?php
				}
			?></td><?php
		}

		// Фильтр показываем если есть события или хотя бы у одного есть фильтр
		if ($this->_Admin_Form->show_operations && $this->_showOperations
		|| $allow_filter && $this->_showFilter)
		{
			$onclick = $this->getAdminLoadAjax($this->getPath());
			?><td><?php
				?><input title="<?php echo Core::_('Admin_Form.button_to_filter')?>" type="image" src="/admin/images/filter.gif" id="admin_forms_apply_button" type="button" value="<?php echo Core::_('Admin_Form.button_to_filter')?>" onclick="<?php echo $onclick?>" /> <input title="<?php echo Core::_('Admin_Form.button_to_clear')?>" type="image" src="/admin/images/clear.png" type="button" value="<?php echo Core::_('Admin_Form.button_to_clear')?>" onclick="$.clearFilter('<?php echo $windowId?>')" /><?php
			?></td><?php
		}
		?></tr><?php

		$aEntities = array();

		// Устанавливаем ограничения на источники
		$this->setDatasetConditions();

		foreach ($this->_datasets as $datasetKey => $oAdmin_Form_Dataset)
		{
			// Добавляем внешнюю замену по датасету
			$this->AddExternalReplace('{dataset_key}', $datasetKey);

			$aDataFromDataset = $oAdmin_Form_Dataset->load();

			if (!empty($aDataFromDataset))
			{
				foreach ($aDataFromDataset as $oEntity)
				{
					try
					{
						$key_field_name = $this->_Admin_Form->key_field;
						$key_field_value = $oEntity->$key_field_name;

						// Экранируем ' в имени индексного поля, т.к. дальше это значение пойдет в JS
						$key_field_value = str_replace("'", "\'", $key_field_value);
					}
					catch (Exception $e)
					{
						Core_Message::show('Caught exception: ' .  $e->getMessage() . "\n", 'error');
						$key_field_value = NULL;
					}

					?><tr id="row_<?php echo $datasetKey?>_<?php echo $key_field_value?>">
						<?php
						// Чекбокс "Для элемента" показываем только при наличии действий
						if ($this->_Admin_Form->show_operations && $this->_showOperations)
						{
							?><td align="center" width="25">
								<input type="checkbox" id="check_<?php echo $datasetKey?>_<?php echo $key_field_value?>" onclick="$('#<?php echo $windowId?>').setTopCheckbox(); $('#' + getWindowId('<?php echo $windowId?>') + ' #row_<?php echo $datasetKey?>_<?php echo $key_field_value?>').toggleHighlight()" /><?php
							?></td><?php
						}

						foreach ($aAdmin_Form_Fields AS $iAdmin_Form_Field_key => $oAdmin_Form_Field)
						{
							// Перекрытие параметров для данного поля
							$oAdmin_Form_Field_Changed = $this->_changeField($oAdmin_Form_Dataset, $oAdmin_Form_Field);

							/*
							// Проверяем, установлено ли пользователем перекрытие параметров
							// для данного поля.
							if (isset($this->form_params['field_params'][$datasetKey][$field_value['admin_forms_field_name']]))
							{
								// Пользователь перекрыл параметры для данного поля.
								$field_value = array_merge($field_value, $this->form_params['field_params'][$datasetKey][$field_value['admin_forms_field_name']]);
							}
							elseif (isset($this->form_params['field_params'][$datasetKey][$oAdmin_Form_Field_Changed->id]))
							{
								// Проверка перекрытых параметров по идентификатору.
								$field_value = array_merge($field_value, $this->form_params['field_params'][$datasetKey][$oAdmin_Form_Field_Changed->id]);
							}
							*/

							// Параметры поля.
							$width_value = htmlspecialchars(trim($oAdmin_Form_Field_Changed->width));

							$width = !empty($width_value)
								? 'width="'.$width_value.'"'
								: '';

							$style = htmlspecialchars(trim($oAdmin_Form_Field_Changed->style));
							$style = empty($style)
								? ''
								: 'style="'.$style.'"';

							$align = htmlspecialchars(trim($oAdmin_Form_Field_Changed->align));

							if (!empty($align))
							{
								$align = 'align="'.$align.'"';
							}

							$attrib = trim($oAdmin_Form_Field_Changed->attributes);

							// Не подсвечивать
							$highlight = false;

							if ($oAdmin_Form_Field_Changed->allow_sorting)
							{
								if ($oAdmin_Form_Field_Changed->id == $this->_sortingAdmin_Form_Field->id)
								{
									// Подсвечивать
									$highlight = TRUE;
								}
							}

							?><td <?php echo $width?> <?php echo $style?> <?php echo $align?> <?php echo $attrib?><?php echo $highlight ? ' class="hl"' : ''?>><?php

							$fieldName = $oAdmin_Form_Field_Changed->name;

							try
							{
								if ($oAdmin_Form_Field_Changed->type != 10)
								{
									if (isset($oEntity->$fieldName))
									{
										// Выведим значение свойства
										$value = htmlspecialchars($oEntity->$fieldName);
									}
									elseif (method_exists($oEntity, $fieldName))
									{
										// Выполним функцию обратного вызова
										$value = htmlspecialchars($oEntity->$fieldName());
									}
									else
									{
										$value = NULL;
									}
								}

								$element_name = "apply_check_{$datasetKey}_{$key_field_value}_fv_{$oAdmin_Form_Field_Changed->id}";

								// Отображения элементов полей, в зависимости от их типа.
								switch ($oAdmin_Form_Field_Changed->type)
								{
									case 1: // Текст.
										//trim($value) == '' && $value = '&nbsp;';

										$class = 'dl';

										$oAdmin_Form_Field_Changed->editable && $class .= ' editable';

										?><div id="<?php echo $element_name?>"><div <?php echo !empty($width_value) ? 'style="width: ' . $width_value . '"' : '' ?> class="<?php echo $class?>"><?php
										echo $this->applyFormat(nl2br($value), $oAdmin_Form_Field_Changed->format);
										?></div></div><?php
									break;
									case 2: // Поле ввода.
										?><input type="text" name="<?php echo $element_name?>" id="<?php echo $element_name?>" value="<?php echo $value?>" <?php echo $style?> <?php echo ''/*$size*/?> onchange="$.setCheckbox('<?php echo $windowId?>', 'check_<?php echo $datasetKey?>_<?php echo $key_field_value?>'); $('#' + getWindowId('<?php echo $windowId?>') + ' #row_<?php echo $datasetKey?>_<?php echo $key_field_value?>').toggleHighlight()" onkeydown="$.setCheckbox('<?php echo $windowId?>', 'check_<?php echo $datasetKey?>_<?php echo $key_field_value?>'); $('#' + getWindowId('<?php echo $windowId?>') + ' #row_<?php echo $datasetKey?>_<?php echo $key_field_value?>').toggleHighlight()" /><?php
									break;
									case 3: // Checkbox.
										?><input type="checkbox" name="<?php echo $element_name?>" id="<?php echo $element_name?>" <?php echo intval($value) ? 'checked="checked"' : ''?> onclick="$.setCheckbox('<?php echo $windowId?>', 'check_<?php echo $datasetKey?>_<?php echo $key_field_value?>'); $('#' + getWindowId('<?php echo $windowId?>') + ' #row_<?php echo $datasetKey?>_<?php echo $key_field_value?>').toggleHighlight();" value="1" /><?php
									break;
									case 4: // Ссылка.
										$link = $oAdmin_Form_Field_Changed->link;
										$onclick = $oAdmin_Form_Field_Changed->onclick;

										//$link_text = trim($value);
										$link_text = $this->applyFormat($value, $oAdmin_Form_Field_Changed->format);

										$link = $this->doReplaces($aAdmin_Form_Fields, $oEntity, $link);
										$onclick = $this->doReplaces($aAdmin_Form_Fields, $oEntity, $onclick);

										// Нельзя применять, т.к. 0 является пустотой if (empty($link_text))
										if (mb_strlen($link_text) != 0)
										{
											?><a href="<?php echo $link?>" <?php echo (!empty($onclick)) ? "onclick=\"{$onclick}\"" : ''?>><?php echo $link_text?></a><?php
										}
										else
										{
											?>&nbsp;<?php
										}
									break;
									case 5: // Дата-время.
										$value = $value == '0000-00-00 00:00:00' || $value == ''
											? ''
											: Core_Date::sql2datetime($value);
										echo $this->applyFormat($value, $oAdmin_Form_Field_Changed->format);

									break;
									case 6: // Дата.
										$value = $value == '0000-00-00 00:00:00' || $value == ''
											? ''
											: Core_Date::sql2date($value);
										echo $this->applyFormat($value, $oAdmin_Form_Field_Changed->format);

									break;
									case 7: // Картинка-ссылка.
										$link = $oAdmin_Form_Field_Changed->link;
										$onclick = $oAdmin_Form_Field_Changed->onclick;

										$link = $this->doReplaces($aAdmin_Form_Fields, $oEntity, $link);
										$onclick = $this->doReplaces($aAdmin_Form_Fields, $oEntity, $onclick);

										// ALT-ы к картинкам
										$alt_array = array();

										// TITLE-ы к картинкам
										$title_array = array();

										$value_trim = trim($value);

										/*
										Разделяем варианты значений на строки, т.к. они приходят к нам в виде:
										0 = /images/off.gif
										1 = /images/on.gif
										*/
										$str_array = explode("\n", $oAdmin_Form_Field_Changed->image);
										$value_array = array();

										foreach ($str_array as $str_value)
										{
											// Каждую строку разделяем по равно
											$str_explode = explode('=', $str_value/*, 2*/);

											if (count($str_explode) > 1)
											{
												// сохраняем в массив варинаты значений и ссылки для них
												$value_array[trim($str_explode[0])] = trim($str_explode[1]);

												// Если указано альтернативное значение для картинки - добавим его в alt и title
												if (isset($str_explode[2]) && $value_trim == trim($str_explode[0]))
												{
													$alt_array[$value_trim] = trim($str_explode[2]);
													$title_array[$value_trim] = trim($str_explode[2]);
												}
											}
										}

										// Получаем заголовок столбца на случай, если для IMG не было указано alt-а или title
										$Admin_Word_Value = $oAdmin_Form_Field->Admin_Word->getWordByLanguage(
											$this->_Admin_Language->id
										);

										$fieldName = $Admin_Word_Value
											? htmlspecialchars($Admin_Word_Value->name)
											: "&mdash;";

										// Warning: 01-06-11 Создать отдельное поле в таблице в БД и в нем хранить alt-ы
										if (isset($field_value['admin_forms_field_alt']))
										{
											$str_array_alt = explode("\n", trim($field_value['admin_forms_field_alt']));

											foreach ($str_array_alt as $str_value)
											{
												// Каждую строку разделяем по равно
												$str_explode_alt = explode('=', $str_value, 2);

												// сохраняем в массив варинаты значений и ссылки для них
												if (count($str_explode_alt) > 1)
												{
													$alt_array[trim($str_explode_alt[0])] = trim($str_explode_alt[1]);
												}
											}
										}
										elseif (!isset($alt_array[$value]))
										{
											$alt_array[$value] = $fieldName;
										}

										// Warning: 01-06-11 Создать отдельное поле в таблице в БД и в нем хранить title-ы
										if (isset($field_value['admin_forms_field_title']))
										{
											$str_array_title = explode("\n", $field_value['admin_forms_field_title']);

											foreach ($str_array_title as $str_value)
											{
												// Каждую строку разделяем по равно
												$str_explode_title = explode('=', $str_value, 2);

												if (count($str_explode_title) > 1)
												{
													// сохраняем в массив варинаты значений и ссылки для них
													$title_array[trim($str_explode_title[0])] = trim($str_explode_title[1]);
												}
											}
										}
										elseif (!isset($title_array[$value]))
										{
											$title_array[$value] = $fieldName;
										}

										if (isset($value_array[$value]))
										{
											$src = $value_array[$value];
										}
										elseif(isset($value_array['']))
										{
											$src = $value_array[''];
										}
										else
										{
											$src = NULL;
										}

										// Отображаем картинку ссылкой
										if (!empty($link) && !is_null($src))
										{
											?><a href="<?php echo $link?>" onclick="$('#' + getWindowId('<?php echo $windowId?>') + ' #row_<?php echo $datasetKey?>_<?php echo $key_field_value?>').toggleHighlight();<?php echo $onclick?>"><img src="<?php echo htmlspecialchars($src)?>" alt="<?php echo Core_Type_Conversion::toStr($alt_array[$value])?>" title="<?php echo Core_Type_Conversion::toStr($title_array[$value])?>"></a><?php
										}
										// Отображаем картинку без ссылки
										elseif (!is_null($src))
										{
											?><img src="<?php echo htmlspecialchars($src)?>" alt="<?php echo Core_Type_Conversion::toStr($alt_array[$value])?>" title="<?php echo Core_Type_Conversion::toStr($title_array[$value])?>"><?php
										}
										/*elseif (!empty($link) && !isset($value_array[$value]))
										{
											// Картинки для такого значения не найдено, но есть ссылка
											?><a href="<?php echo $link?>" onclick="$('#' + getWindowId('<?php echo $windowId?>') + ' #row_<?php echo $datasetKey?>_<?php echo $key_field_value?>').toggleHighlight();<?php echo $onclick?> ">&mdash;</a><?php
										}*/
										else
										{
											// Картинки для такого значения не найдено
											?>&mdash;<?php
										}
									break;
									case 8: // Выпадающий список
										/*
										Разделяем варианты значений на строки, т.к. они приходят к нам в виде:
										0 = /images/off.gif
										1 = /images/on.gif
										*/

										$str_array = explode("\n", $oAdmin_Form_Field_Changed->list);

										$value_array = array();

										?><select name="<?php echo $element_name?>" id="<?php echo $element_name?>" onchange="$.setCheckbox('<?php echo $windowId?>', 'check_<?php echo $datasetKey?>_<?php echo $key_field_value?>');" <?php echo $style?>><?php

										foreach ($str_array as $str_value)
										{
											// Каждую строку разделяем по равно
											$str_explode = explode('=', $str_value, 2);

											if (count($str_explode) > 1)
											{
												// сохраняем в массив варинаты значений и ссылки для них
												$value_array[intval(trim($str_explode[0]))] = trim($str_explode[1]);

												$selected = $value == $str_explode[0]
													? ' selected = "" '
													: '';

												?><option value="<?php echo htmlspecialchars($str_explode[0])?>" <?php echo $selected?>><?php echo htmlspecialchars(trim($str_explode[1]))?></option><?php
											}
										}
										?>
										</select>
										<?php

									break;
									case 9: // Текст "AS IS"
										if (mb_strlen($value) != 0)
										{
											echo html_entity_decode($value, ENT_COMPAT, 'UTF-8');
										}
										else
										{
											?>&nbsp;<?php
										}

									break;
									case 10: // Вычисляемое поле с помощью функции обратного вызова,
									// имя функции обратного вызова f($field_value, $value)
									// передается функции с именем, содержащимся в $field_value['callback_function']
										if (method_exists($oEntity, $fieldName)
											|| method_exists($oEntity, 'isCallable') && $oEntity->isCallable($fieldName)
										)
										{
											// Выполним функцию обратного вызова
											echo $oEntity->$fieldName($oAdmin_Form_Field, $this);
										}
										elseif (property_exists($oEntity, $fieldName))
										{
											// Выведим значение свойства
											echo $oEntity->$fieldName;
										}
									break;
									default: // Тип не определен.
										?>&nbsp;<?php
									break;
								}
							}
							catch (Exception $e)
							{
								Core_Message::show('Caught exception: ' .  $e->getMessage() . "\n", 'error');
							}
							?></td><?php
						}

						// Действия для строки в правом столбце
						if ($this->_Admin_Form->show_operations
						&& $this->_showOperations
						|| $allow_filter && $this->_showFilter)
						{
							// Определяем ширину столбца для действий.
							$width = isset($this->form_params['actions_width'])
								? strval($this->form_params['actions_width'])
								: '10px'; // Минимальная ширина

							// <nobr> из-за IE
							?><td class="admin_forms_action_td" style="width: <?php echo $width?>"><nobr><?php

							foreach ($aAllowed_Admin_Form_Actions as $o_Admin_Form_Action)
							{
								// Отображаем действие, только если разрешено.
								if (!$o_Admin_Form_Action->single)
								{
									continue;
								}

								// Проверяем, привязано ли действие к определенному dataset'у.
								if ($o_Admin_Form_Action->dataset != -1
								&& $o_Admin_Form_Action->dataset != $datasetKey)
								{
									continue;
								}

								$Admin_Word_Value = $o_Admin_Form_Action->Admin_Word->getWordByLanguage($this->_Admin_Language->id);

								if ($Admin_Word_Value && strlen($Admin_Word_Value->name) > 0)
								{
									$name = $Admin_Word_Value->name;
								}
								else
								{
									$name = '';
								}

								$href = $this->getAdminActionLoadHref($this->getPath(), $o_Admin_Form_Action->name, NULL,
										$datasetKey, $key_field_value);

								$onclick = $this->getAdminActionLoadAjax($this->getPath(), $o_Admin_Form_Action->name, NULL, $datasetKey, $key_field_value);

								// Добавляем установку метки для чекбокса и строки + добавлем уведомление, если необходимо
								if ($o_Admin_Form_Action->confirm)
								{
									$onclick = "res = confirm('".Core::_('Admin_Form.confirm_dialog', htmlspecialchars($name))."'); if (!res) { $('#{$windowId} #row_{$datasetKey}_{$key_field_value}').toggleHighlight(); } else {{$onclick}} return res;";
								}

								?><a href="<?php echo $href?>" onclick="<?php echo $onclick?>"><img src="<?php echo htmlspecialchars($o_Admin_Form_Action->picture)?>" alt="<?php echo $name?>" title="<?php echo $name?>"></a> <?php
							}
							?></nobr></td><?php
						}

						?></tr><?php
				}
			}
		}

		?></table><?php

		return $this;
	}

	/**
	 * Show action panel in administration center
	 * @return self
	 */
	protected function _showBottomActions()
	{
		// Строка с действиями
		if ($this->_showBottomActions)
		{
			$windowId = $this->getWindowId();

			// Текущий пользователь
			$oUser = Core_Entity::factory('User')->getCurrent();

			// Доступные действия для пользователя
			$aAllowed_Admin_Form_Actions = $this->_Admin_Form->Admin_Form_Actions->getAllowedActionsForUser($oUser);
		?>
		<table cellpadding="5" cellspacing="0" border="1" width="100%" style="margin-top: 8px;" class="light_table">
		<tr>
		<?php
		// Чекбокс "Выбрать все" показываем только при наличии действий
		if ($this->_Admin_Form->show_operations && $this->_showOperations)
		{
			?><td align="center" width="25">
				<input type="checkbox" name="admin_forms_all_check2" id="id_admin_forms_all_check2" onclick="$('#<?php echo $windowId?>').highlightAllRows(this.checked)" />
			</td><?php
		}

		?><td>
			<div class="admin_form_action"><?php

				if ($this->_Admin_Form->show_group_operations)
				{
					// Групповые операции
					if (!empty($aAllowed_Admin_Form_Actions))
					{
						foreach ($aAllowed_Admin_Form_Actions as $o_Admin_Form_Action)
						{
							if ($o_Admin_Form_Action->group)
							{
								$Admin_Word_Value = $o_Admin_Form_Action->Admin_Word->getWordByLanguage($this->_Admin_Language->id);

								if ($Admin_Word_Value && strlen($Admin_Word_Value->name) > 0)
								{
									$text = $Admin_Word_Value->name;
								}
								else
								{
									$text = '';
								}

								$href = $this->getAdminLoadHref($this->getPath(), $o_Admin_Form_Action->name);
								$onclick = $this->getAdminLoadAjax($this->getPath(), $o_Admin_Form_Action->name);

								// Нужно подтверждение для действия
								if ($o_Admin_Form_Action->confirm)
								{
									$onclick = "res = confirm('".Core::_('Admin_Form.confirm_dialog', htmlspecialchars($text))."'); if (res) { $onclick } else {return false}";

									$link_class = 'admin_form_action_alert_link';
								}
								else
								{
									$link_class = 'admin_form_action_link';
								}

								// ниже по тексту alt-ы и title-ы не выводятся, т.к. они дублируются текстовыми
								// надписями и при отключении картинок текст дублируется
								/* alt="<?php echo htmlspecialchars($text)?>"*/
								?><nobr><a href="<?php echo $href?>" onclick="<?php echo $onclick?>"><img src="<?php echo htmlspecialchars($o_Admin_Form_Action->picture)?>" title="<?php echo htmlspecialchars($text)?>"></a> <a href="<?php echo $href?>" onclick="<?php echo $onclick?>" class="<?php echo $link_class?>"><?php echo htmlspecialchars($text)?></a>
								</nobr><?php
							}
						}
					}
				}
				?>
			</div>
			</td>
			<td width="110" align="center">
				<div class="admin_form_action">
				<?php
				// Дописываем параметры фильтра
				/*if (count($_REQUEST) > 0)
				{
					foreach ($_REQUEST as $rkey => $rvalue)
					{
						// Передаем параметры фильтра
						if (mb_strpos($rkey, 'admin_form_filter_') === 0)
						{
							$this->AAdditionalParams .= "&{$rkey}=".urlencode($rvalue);
						}
					}
				}
				$action_href = '';
				?>
				<nobr>
				<a href="<?php echo $action_href?>" target="_blank"><img src="/admin/images/export.gif" title="<?php echo Core::_('Admin_Form.export_csv')?>"></a>
				<a href="<?php echo $action_href?>" target="_blank"><?php echo Core::_('Admin_Form.export_csv')?></a>
				</nobr><?php */
				?></div>
			</td>
			<td width="60" align="center"><?php
				$this->_onPageSelector()
			?></td>
		</tr>
		</table>
		<?php
		}

		return $this;
	}

	/**
	 * Edit-in-Place in administration center
	 * @return self
	 */
	protected function _applyEditable()
	{
		$windowId = Core_Str::escapeJavascriptVariable($this->getWindowId());
		$path = Core_Str::escapeJavascriptVariable($this->getPath());

		// Текущий пользователь
		$oUser = Core_Entity::factory('User')->getCurrent();

		// Доступные действия для пользователя
		$aAllowed_Admin_Form_Actions = $this->_Admin_Form->Admin_Form_Actions->getAllowedActionsForUser($oUser);

		// Editable
		$bEditable = FALSE;
		foreach ($aAllowed_Admin_Form_Actions as $o_Admin_Form_Action)
		{
			if ($o_Admin_Form_Action->name == 'apply')
			{
				$bEditable = TRUE;
				break;
			}
		}

		if ($bEditable)
		{
			Core::factory('Core_Html_Entity_Script')
				->type("text/javascript")
				->value("(function($){
					$('#{$windowId} table.admin_table .editable').editable({windowId: '{$windowId}', path: '{$path}'});
				})(jQuery);")
				->execute();
		}

		Core::factory('Core_Html_Entity_Script')
			->type("text/javascript")
			->value("(function($){
				$('#{$windowId} table.admin_table .admin_table_filter :input').on('keydown', $.filterKeyDown);
			})(jQuery);")
			->execute();

		return $this;
	}

	/**
	 * Get form
	 * @return string
	 */
	protected function _getForm()
	{
		ob_start();

		$this
			->_showFormTitle()
			->showChildren()
			->_showFormContent()
			->_showBottomActions()
			->_applyEditable()
			->_pageNavigation();

		return ob_get_clean();
	}

	/**
	 * Apply external replaces in $subject
	 * @param array $aAdmin_Form_Fields Admin_Form_Fields
	 * @param Core_Entity $oEntity entity
	 * @param string $subject
	 * @return string
	 */
	public function doReplaces($aAdmin_Form_Fields, $oEntity, $subject)
	{
		foreach ($this->_externalReplace as $replace_key => $replace_value)
		{
			$subject = str_replace($replace_key, $replace_value, $subject);
		}

		$aColumns = $oEntity->getTableColums();
		foreach ($aColumns as $columnName => $columnArray)
		{
			$subject = str_replace('{'.$columnName.'}', $oEntity->$columnName, $subject);
		}

		return $subject;
	}

	/**
	* Применяет формат отображения $format к строке $str.
	* Если формат является пустой строкой - $str возвращается в исходном виде.
	*
	* @param string $str исходная строка
	* @param string $format форма отображения. Строка формата состоит из директив: обычных символов (за исключением %),
	* которые копируются в результирующую строку, и описатели преобразований,
	* каждый из которых заменяется на один из параметров.
	*/
	public function applyFormat($str, $format)
	{
		if (!empty($format))
		{
			$str = sprintf($format, $str);
		}
		return $str;
	}

	/**
	 * ID of sorting field
	 * @var int
	 */
	protected $_sortingFieldId = NULL;

	/**
	 * Set sorting field by ID
	 * @param int $sortingFieldId field ID
	 * @return self
	 */
	public function sortingFieldId($sortingFieldId)
	{
		if (!is_null($sortingFieldId) && $this->_Admin_Form)
		{
			// Проверка принадлежности форме
			$oAdmin_Form_Field = Core_Entity::factory('Admin_Form_Field')->find($sortingFieldId);

			if ($oAdmin_Form_Field && $this->_Admin_Form->id == $oAdmin_Form_Field->admin_form_id)
			{
				$this->_sortingFieldId = $sortingFieldId;
			}
			else
			{
				$this->_sortingFieldId = NULL;
				$this->_sortingDirection = NULL;
			}
		}
		return $this;
	}

	/**
	 * Sorting direction
	 * @var int
	 */
	protected $_sortingDirection = NULL;

	/**
	 * Set sorting direction
	 * @param int $sortingDirection direction
	 * @return self
	 */
	public function sortingDirection($sortingDirection)
	{
		if (!is_null($sortingDirection))
		{
			$this->_sortingDirection = intval($sortingDirection);
		}

		return $this;
	}

	/**
	 * Backend callback method
	 * @param string $path path
	 * @param string $action action
	 * @param string $operation operation
	 * @param string $datasetKey dataset key
	 * @param string $datasetValue dataset value
	 * @param string $additionalParams additional params
	 * @param string $limit limit
	 * @param string $current current
	 * @param int $sortingFieldId sorting field ID
	 * @param string $sortingDirection sorting direction
	 * @return string
	 */
	public function getAdminActionLoadAjax($path, $action, $operation, $datasetKey, $datasetValue, $additionalParams = NULL,
		$limit = NULL, $current = NULL, $sortingFieldId = NULL, $sortingDirection = NULL)
	{
		$windowId = Core_Str::escapeJavascriptVariable($this->getWindowId());
		$datasetKey = Core_Str::escapeJavascriptVariable($datasetKey);
		$datasetValue = Core_Str::escapeJavascriptVariable($datasetValue);

		return "$('#{$windowId} #row_{$datasetKey}_{$datasetValue}').toggleHighlight(); "
			. "$.adminCheckObject({objectId: 'check_{$datasetKey}_{$datasetValue}', windowId: '{$windowId}'}); "
			. $this->getAdminLoadAjax($path, $action, $operation, $additionalParams, $limit, $current, $sortingFieldId, $sortingDirection);
	}

	/**
	 * Получение кода вызова adminLoad для события onclick
	 * @param string $path path
	 * @param string $action action
	 * @param string $operation operation
	 * @param string $additionalParams additional params
	 * @param string $limit limit
	 * @param mixed $current current
	 * @param int $sortingFieldId sorting field ID
	 * @param mixed $sortingDirection sorting direction
	 * @return string
	*/
	public function getAdminLoadAjax($path, $action = NULL, $operation = NULL, $additionalParams = NULL,
		$limit = NULL, $current = NULL, $sortingFieldId = NULL, $sortingDirection = NULL)
	{
		/*if ($path)
		{
			// Нельзя, т.к. при изменении у предыдущего параметра URL-а, то действия сломаются
			//$this->AAction = str_replace("'", "\'", $AAction);
		}
		else
		{
			$path = '';
		}*/

		$path = Core_Str::escapeJavascriptVariable($path);
		$action = Core_Str::escapeJavascriptVariable($action);
		$operation = Core_Str::escapeJavascriptVariable($operation);
		$windowId = Core_Str::escapeJavascriptVariable($this->getWindowId());

		$aData = array();

		$aData[] = "path: '{$path}'";

		/*if (is_null($action))
		{
			$action = $this->_action;
		}*/
		$aData[] = "action: '{$action}'";

		/*if (is_null($operation))
		{
			$operation = $this->_operation;
		}*/
		$aData[] = "operation: '{$operation}'";

		is_null($additionalParams) && $additionalParams = $this->_additionalParams;

		$additionalParams = Core_Str::escapeJavascriptVariable(
			str_replace(array('"'), array('&quot;'), $additionalParams)
		);
		$aData[] = "additionalParams: '{$additionalParams}'";

		if (is_null($limit))
		{
			$limit = $this->_limit;
		}
		$limit = intval($limit);
		$aData[] = "limit: '{$limit}'";

		if (is_null($current))
		{
			$current = $this->_current;
		}
		$current = intval($current);
		$aData[] = "current: '{$current}'";

		if (is_null($sortingFieldId))
		{
			$sortingFieldId = $this->_sortingFieldId;
		}
		$sortingFieldId = intval($sortingFieldId);
		$aData[] = "sortingFieldId: '{$sortingFieldId}'";

		if (is_null($sortingDirection))
		{
			$sortingDirection = $this->_sortingDirection;
		}
		$sortingDirection = intval($sortingDirection);
		$aData[] = "sortingDirection: '{$sortingDirection}'";

		$aData[] = "windowId: '{$windowId}'";

		return "$.adminLoad({" . implode(',', $aData) . "}); return false";
	}

	/**
	 * Backend callback method
	 * Для действия из списка элементов
	 * @param string $path path
	 * @param string $action action
	 * @param string $operation operation
	 * @param string $datasetKey dataset key
	 * @param string $datasetValue dataset value
	 * @param string $additionalParams additional params
	 * @param string $limit limit
	 * @param string $current current
	 * @param int $sortingFieldId sorting field ID
	 * @param string $sortingDirection sorting direction
	 * @return string
	 */
	public function getAdminActionLoadHref($path, $action, $operation, $datasetKey, $datasetValue, $additionalParams = NULL,
		$limit = NULL, $current = NULL, $sortingFieldId = NULL, $sortingDirection = NULL)
	{
		is_null($additionalParams) && $additionalParams .= $this->_additionalParams;

		$datasetKey = Core_Str::escapeJavascriptVariable($datasetKey);
		$datasetValue = Core_Str::escapeJavascriptVariable($datasetValue);

		$additionalParams .= '&hostcms[checked][' . $datasetKey . '][' . $datasetValue . ']=1';

		return $this->getAdminLoadHref($path, $action, $operation, $additionalParams, $limit, $current, $sortingFieldId, $sortingDirection);
	}

	/**
	* Получение кода вызова adminLoad для href
	* @param string $path path
	* @param string $action action name
	* @param string $operation operation name
	* @param string $additionalParams additional params
	* @param int $limit count of items on page
	* @param int $current current page number
	* @param int $sortingFieldId ID of sorting field
	* @param int $sortingDirection sorting direction
	* @return string
	*/
	public function getAdminLoadHref($path, $action = NULL, $operation = NULL, $additionalParams = NULL,
		$limit = NULL, $current = NULL, $sortingFieldId = NULL, $sortingDirection = NULL)
	{
		$aData = array();

		$action = rawurlencode($action);
		$aData[] = "hostcms[action]={$action}";

		$operation = rawurlencode($operation);
		$aData[] = "hostcms[operation]={$operation}";

		if (is_null($limit))
		{
			$limit = $this->_limit;
		}
		$limit = intval($limit);
		$aData[] = "hostcms[limit]={$limit}";

		if (is_null($current))
		{
			$current = $this->_current;
		}
		$current = intval($current);
		$aData[] = "hostcms[current]={$current}";

		if (is_null($sortingFieldId))
		{
			$sortingFieldId = $this->_sortingFieldId;
		}
		$sortingFieldId = intval($sortingFieldId);
		$aData[] = "hostcms[sortingfield]={$sortingFieldId}";

		if (is_null($sortingDirection))
		{
			$sortingDirection = $this->_sortingDirection;
		}
		$sortingDirection = intval($sortingDirection);
		$aData[] = "hostcms[sortingdirection]={$sortingDirection}";

		$windowId = rawurlencode($this->getWindowId());
		strlen($windowId) && $aData[] = "hostcms[window]={$windowId}";

		is_null($additionalParams) && $additionalParams = $this->_additionalParams;

		//$additionalParams = str_replace(array("'", '"'), array("\'", '&quot;'), $additionalParams);
		if ($additionalParams)
		{
			// Уже содержит перечень параметров, которые должны быть экранированы
			//$additionalParams = rawurlencode($additionalParams);
			$aData[] = $additionalParams;
		}

		return $path . '?' . implode('&', $aData);
	}

	/**
	* Получение кода вызова adminLoad для события onclick
	* @param string $action action name
	* @param string $operation operation name
	* @param string $additionalParams additional params
	* @param int $limit count of items on page
	* @param int $current current page number
	* @param int $sortingFieldId ID of sorting field
	* @param int $sortingDirection sorting direction
	* @return string
	*/
	public function getAdminSendForm($action = NULL, $operation = NULL, $additionalParams = NULL,
		$limit = NULL, $current = NULL, $sortingFieldId = NULL, $sortingDirection = NULL)
	{
		$aData = array();

		$aData[] = "buttonObject: this";

		// add
		if (is_null($action))
		{
			$action = $this->_action;
		}
		$action = Core_Str::escapeJavascriptVariable($action);
		$aData[] = "action: '{$action}'";

		/*if (is_null($operation))
		{
			$operation = $this->_operation;
		}*/
		$operation = Core_Str::escapeJavascriptVariable($operation);
		$aData[] = "operation: '{$operation}'";

		is_null($additionalParams) && $additionalParams = $this->_additionalParams;

		$additionalParams = Core_Str::escapeJavascriptVariable(
			str_replace(array('"'), array('&quot;'), $additionalParams)
		);
		// Выбранные элементы для действия
		foreach ($this->_checked as $datasetKey => $checkedItems)
		{
			foreach ($checkedItems as $checkedItemId => $v1)
			{
				$datasetKey = intval($datasetKey);
				$checkedItemId = htmlspecialchars($checkedItemId);

				$additionalParams .= empty($additionalParams) ? '' : '&';
				$additionalParams .= 'hostcms[checked][' . $datasetKey . '][' . $checkedItemId . ']=1';
			}
		}
		$aData[] = "additionalParams: '{$additionalParams}'";

		if (is_null($limit))
		{
			$limit = $this->_limit;
		}
		$limit = intval($limit);
		$aData[] = "limit: '{$limit}'";

		if (is_null($current))
		{
			$current = $this->_current;
		}
		$current = intval($current);
		$aData[] = "current: '{$current}'";

		if (is_null($sortingFieldId))
		{
			$sortingFieldId = $this->_sortingFieldId;
		}
		$sortingFieldId = intval($sortingFieldId);
		$aData[] = "sortingFieldId: '{$sortingFieldId}'";

		if (is_null($sortingDirection))
		{
			$sortingDirection = $this->_sortingDirection;
		}
		$sortingDirection = intval($sortingDirection);
		$aData[] = "sortingDirection: '{$sortingDirection}'";

		$windowId = Core_Str::escapeJavascriptVariable($this->getWindowId());
		$aData[] = "windowId: '{$windowId}'";

		return "$.adminSendForm({" . implode(',', $aData) . "}); return false";
	}

	/**
	 * Set dataset conditions
	 * @return self
	 */
	public function setDatasetConditions()
	{
		$aAdmin_Form_Fields = $this->_Admin_Form->Admin_Form_Fields->findAll();

		// Для каждого набора данных формируем свой фильтр,
		// т.к. использовать псевдонимы в SQL операторе WHERE нельзя!
		$aFilter = array();

		$oAdmin_Form_Field_Order = Core_Entity::factory('Admin_Form_Field', $this->_sortingFieldId);

		foreach ($this->_datasets as $datasetKey => $oAdmin_Form_Dataset)
		{
			$oEntity = $oAdmin_Form_Dataset->getEntity();

			if ($oAdmin_Form_Field_Order->allow_sorting)
			{
				// Check field exists in the model
				$fieldName = $oAdmin_Form_Field_Order->name;
				if (isset($oEntity->$fieldName) || method_exists($oEntity, $fieldName)
					// Для сортировки должно существовать св-во модели
					// || property_exists($oEntity, $fieldName)
				)
				{
					$oAdmin_Form_Dataset->addCondition(array(
							'orderBy' => array($oAdmin_Form_Field_Order->name, $this->_sortingDirection
								? 'DESC'
								: 'ASC'
							)
						)
					);
				}
			}

			foreach ($aAdmin_Form_Fields as $oAdmin_Form_Field)
			{
				if ($oAdmin_Form_Field->allow_filter)
				{
					// Имя поля.
					$fieldName = $oAdmin_Form_Field->name;

					$sFilterValue = Core_Array::get($this->request, "admin_form_filter_{$oAdmin_Form_Field->id}", NULL);

					if ($fieldName != '')
					{
						$sFilterType = $oAdmin_Form_Field->filter_type == 0
							? 'where'
							: 'having';

						// Если имя поля counter_pages.date, то остается date
						$sTmpFieldName = $fieldName;
						strpos($fieldName, '.') !== FALSE && list(, $sTmpFieldName) = explode('.', $fieldName);

						// для HAVING не проверяем наличие поля
						if ($oAdmin_Form_Field->filter_type == 1
							|| isset($oEntity->$sTmpFieldName) || method_exists($oEntity, $sTmpFieldName) || property_exists($oEntity, $sTmpFieldName))
						{

							// Тип поля.
							switch ($oAdmin_Form_Field->type)
							{
								case 1: // Строка
								case 2: // Поле ввода
								case 4: // Ссылка
								case 10: // Вычислимое поле
									if (is_null($sFilterValue) || $sFilterValue == '' || mb_strlen($sFilterValue) > 255)
									{
										break;
									}

									$sFilterValue = str_replace(array('*', '?'), array('%', '_'), trim($sFilterValue));

									$oAdmin_Form_Dataset->addCondition(
										array($sFilterType =>
											array($fieldName, 'LIKE', $sFilterValue)
										)
									);
								break;

								case 3: // Checkbox.
								{
									if (!$sFilterValue)
									{
										break;
									}

									if ($sFilterValue != 1)
									{
										$openName = ($oAdmin_Form_Field->filter_type == 0)
											? 'open'
											: 'havingOpen';

										$closeName = ($oAdmin_Form_Field->filter_type == 0)
											? 'close'
											: 'havingClose';

										$oAdmin_Form_Dataset
										->addCondition(array($openName => array()))
										->addCondition(
											array($sFilterType =>
												array($fieldName, '=', 0)
											)
										)
										->addCondition(array('setOr' => array()))
										->addCondition(
											array($sFilterType =>
												array($fieldName, 'IS', NULL)
											)
										)
										->addCondition(array($closeName => array()));
									}
									else
									{
										$oAdmin_Form_Dataset->addCondition(
											array($sFilterType =>
												array($fieldName, '!=', 0)
											)
										);
									}
									break;
								}
								case 5: // Дата-время.
								case 6: // Дата.

									// Дата от.
									$date = trim(Core_Array::get($this->request, "admin_form_filter_from_{$oAdmin_Form_Field->id}"));

									if (!empty($date))
									{
										$date = $oAdmin_Form_Field->type == 5
											? Core_Date::datetime2sql($date)
											: date('Y-m-d 00:00:00', Core_Date::date2timestamp($date));

										$oAdmin_Form_Dataset->addCondition(
											array($sFilterType =>
												array($fieldName, '>=', $date)
											)
										);
									}

									// Дата до.
									$date = trim(Core_Array::get($this->request, "admin_form_filter_to_{$oAdmin_Form_Field->id}"));
									if (!empty($date))
									{
										if ($oAdmin_Form_Field->type == 5)
										{
											// Преобразуем из d.m.Y H:i:s в SQL формат.
											$date = Core_Date::datetime2sql($date);
										}
										else
										{
											// Преобразуем из d.m.Y в SQL формат.
											$date = date('Y-m-d 23:59:59', Core_Date::date2timestamp($date));
										}

										$oAdmin_Form_Dataset->addCondition(
											array($sFilterType =>
												array($fieldName, '<=', $date)
											)
										);
									}
								break;

								case 8: // Список
								{
									if (is_null($sFilterValue))
									{
										break;
									}

									if ($sFilterValue != 'HOST_CMS_ALL')
									{
										$oAdmin_Form_Dataset->addCondition(
											array($sFilterType =>
												array($fieldName, 'LIKE', $sFilterValue)
											)
										);
									}

									break;
								}
							}
						}
					}
				}
			}
		}

		// begin
		$offset = $this->_limit * ($this->_current - 1);

		// Корректируем лимиты, если они указаны общие для N источников
		//if (isset($this->form_params['limit']['all']))
		//{
		// Сумируем общее количество элементов из разных источников
		// и проверяем, меньше ли они $this->form_params['limit']['all']['begin']
		// если меньше - то расчитываем корректный begin
		//if ($datasetKey == 0)
		//{
			if (count($this->_datasets) == 1)
			{
				reset($this->_datasets);
				list(, $oAdmin_Form_Dataset) = each($this->_datasets);

				$oAdmin_Form_Dataset
					->limit($this->_limit)
					->offset($offset)
					->load();

				// Данные уже были загружены при первом применении лимитов и одном источнике
				$bLoaded = TRUE;
			}
			else
			{
				$bLoaded = FALSE;
			}

			$iTotalCount = $this->getTotalCount();

			if ($iTotalCount < $offset)
			{
				$current = floor($iTotalCount / $this->_limit);

				if ($current <= 0)
				{
					$current = 1;
					$offset = 0;
					$bLoaded = FALSE;
				}

				$this->current($current);
			}
			elseif($iTotalCount == $offset && $offset >= $this->_limit)
			{
				$offset -= $this->_limit;
				$bLoaded = FALSE;
			}
		//}

		// При экспорте в CSV лимиты недействительны
		/*if ($this->export_csv)
		{
			if (isset($this->form_params['limit']))
			{
				unset($this->form_params['limit']);
			}
		}*/

		try
		{
			// $iTotalCount - 48
			foreach ($this->_datasets as $datasetKey => $oAdmin_Form_Dataset)
			{
				// 0й - 17
				$datasetCount = $oAdmin_Form_Dataset->getCount();

				// 17 >
				if ($datasetCount > $offset)
				{
					$oAdmin_Form_Dataset
						->limit($this->_limit)
						->offset($offset)
						->loaded($bLoaded);
				}
				else // Не показывать, т.к. очередь другого датасета
				{
					$oAdmin_Form_Dataset
						->limit(0)
						->offset(0)
						->loaded($bLoaded);
				}

				// Предыдущие можем смотреть только для 1-го источника и следующих
				if ($datasetKey > 0)
				{
					// Если число элементов предыдущего источника меньше текущего начала
					$prevDatasetCount = $this->_datasets[$datasetKey - 1]->getCount();

					if ($prevDatasetCount - $offset // 17 - 10 = 7
						< $this->_limit // 10
					)
					{
						$begin = $offset - $prevDatasetCount;

						if ($begin < 0)
						{
							$begin = 0;
						}

						$oAdmin_Form_Dataset
						->limit($this->_limit - ($prevDatasetCount - $offset) - $begin)
						->offset($begin)
						->loaded($bLoaded);
					}
					else
					{
						$oAdmin_Form_Dataset
						->limit(0)
						->offset(0)
						->loaded($bLoaded);
					}
				}
			}
		}
		catch (Exception $e)
		{
			Core_Message::show($e->getMessage(), 'error');
		}

		return $this;
	}

	/**
	 * Apply external changes for filter
	 * @param Admin_Form_Dataset $oAdmin_Form_Dataset dataset
	 * @param Admin_Form_Field $oAdmin_Form_Field field
	 * @return object
	 */
	protected function _changeField($oAdmin_Form_Dataset, $oAdmin_Form_Field)
	{
		// Проверяем, установлено ли пользователем перекрытие параметров для данного поля.
		$aChangedFields = $oAdmin_Form_Dataset->getFieldChanges($oAdmin_Form_Field->name);

		if ($aChangedFields)
		{
			$aChanged = $aChangedFields + $oAdmin_Form_Field->toArray();
			$oAdmin_Form_Field_Changed = (object)$aChanged;
		}
		else
		{
			$oAdmin_Form_Field_Changed = $oAdmin_Form_Field;
		}

		return $oAdmin_Form_Field_Changed;
	}
}