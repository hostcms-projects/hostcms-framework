<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Frontend data, e.g. title, description, template and data hierarchy
 *
 * <code>
 * // Get Title
 * $title = Core_Page::instance()->title;
 * </code>
 *
 * <code>
 * // Set Title
 * Core_Page::instance()->title('New title');
 * </code>
 *
 * <code>
 * // Get description
 * $description = Core_Page::instance()->description;
 * </code>
 *
 * <code>
 * // Set description
 * Core_Page::instance()->description('New description');
 * </code>
 *
 * <code>
 * // Get keywords
 * $keywords = Core_Page::instance()->keywords;
 * </code>
 *
 * <code>
 * // Set keywords
 * Core_Page::instance()->keywords('New keywords');
 * </code>
 *
 * <code>
 * // Get Template object
 * $oTemplate = Core_Page::instance()->template;
 * var_dump($oTemplate->id);
 * </code>
 *
 * <code>
 * // Get Structure object
 * $oStructure = Core_Page::instance()->structure;
 * var_dump($oStructure->id);
 * </code>
 *
 * <code>
 * // Get Core_Response object
 * $oCore_Response = Core_Page::instance()->response;
 * // Set HTTP status
 * $oCore_Response->status(404);
 * </code>
 *
 * <code>
 * // Get array of lib params
 * $array = Core_Page::instance()->libParams;
 * </code>
 *
 *
 * <code>
 * // Get controller object
 * $object = Core_Page::instance()->object;
 *
 * if (is_object(Core_Page::instance()->object)
 * && get_class(Core_Page::instance()->object) == 'Informationsystem_Controller_Show')
 * {
 *    $Informationsystem_Controller_Show = Core_Page::instance()->object;
 * }
 * </code>
 *
 * @package HostCMS 6\Core
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Core_Page extends Core_Servant_Properties
{
	/**
	 * Allowed object properties
	 * @var array
	 */
	protected $_allowedProperties = array(
		'title',
		'description',
		'keywords',
		'template',
		'structure',
		'response',
		'libParams',
		'object',
		'buildingPage'
	);

	/**
	 * Children entities
	 * @var array
	 */
	protected $_children = array();

	/**
	 * Add child to an hierarchy
	 * @param object $object object
	 * @return Core_Page
	 */
	public function addChild($object)
	{
		array_unshift($this->_children, $object);
		return $this;
	}

	/*public function addLastChild($object)
	{
		$this->_children[] = $object;
		return $this;
	}*/

	/**
	 * Delete first child
	 * @return Core_Page
	 */
	public function deleteChild()
	{
		array_shift($this->_children);
		return $this;
	}

	/**
	 * Executes the business logic.
	 */
	public function execute()
	{
		if (count($this->_children))
		{
			$object = array_shift($this->_children);
			return $object->execute();
		}

		return $this;
	}

	/**
	 * Get children
	 * @return array
	 */
	public function getChildren()
	{
		return $this->_children;
	}

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->libParams = array();
		$this->buildingPage = FALSE;
	}

	/**
	 * The singleton instances.
	 * @var mixed
	 */
	static public $instance = NULL;

	/**
	 * Register an existing instance as a singleton.
	 * @return object
	 */
	static public function instance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Linking css
	 * @var array
	 */
	public $css = array();

	/**
	 * Link $css to the beginning of list
	 * @param string $css path
	 * @return Core_Page
	 */
	public function prependCss($css)
	{
		array_unshift($this->css, $css);
		return $this;
	}

	/**
	 * Link $css onto the end of array
	 * @param string $css path
	 * @return Core_Page
	 */
	public function css($css)
	{
		$this->css[] = $css;
		return $this;
	}

	/**
	 * Get block of linked css
	 * @param boolean $bExternal add as link
	 * @return string
	 */
	protected function _getCss($bExternal = TRUE)
	{
		$sReturn = '';

		foreach ($this->css as $css)
		{
			if ($bExternal)
			{
				$sReturn .= '<link rel="stylesheet" type="text/css" href="' . $css . '?' . Core_Date::sql2timestamp($this->template->timestamp) . '" />' . "\n";
			}
			else
			{
				$sPath = CMS_FOLDER . ltrim($css, DIRECTORY_SEPARATOR);
				$sReturn .= "<style type=\"text/css\">\n";
				is_file($sPath) && $sReturn .= Core_File::read($sPath);
				$sReturn .= "\n</style>\n";
			}
		}

		return $sReturn;
	}

	/**
	 * Get block of linked compressed css
	 * @return string
	 */
	protected function _getCssCompressed()
	{
		try
		{
			$sReturn = '';

			$oCompression_Controller = Compression_Controller::instance('css');

			foreach ($this->css as $css)
			{
				$oCompression_Controller->addCss($css);
			}

			$sPath = $oCompression_Controller->getPath();
			$sReturn .= '<link rel="stylesheet" type="text/css" href="' . $sPath . '?' . Core_Date::sql2timestamp($this->template->timestamp) . '" />' . "\n";
		}
		catch (Exception $e)
		{
			$sReturn = $this->_getCss();
		}

		return $sReturn;
	}

	/**
	 * Get block of linked css
	 * @param boolean $bExternal add as link
	 * @return string
	 */
	public function getCss($bExternal = TRUE)
	{
		return Core::moduleIsActive('compression')
			? $this->_getCssCompressed()
			: $this->_getCss($bExternal);
	}

	/**
	 * Show block of linked css
	 * @param boolean $bExternal add as link
	 * @return Core_Page
	 */
	public function showCss($bExternal = TRUE)
	{
		echo $this->getCss($bExternal);
		return $this;
	}

	/**
	 * Linking js
	 * @var array
	 */
	public $js = array();

	/**
	 * Link js
	 * @param string $js path
	 * @param boolean $async Run asynchronously, default FALSE
	 * @return Core_Page
	 */
	public function js($js, $async = FALSE)
	{
		$this->js[] = array($js, $async);
		return $this;
	}

	/**
	 * Get block of linked js
	 * @return string
	 */
	protected function _getJs()
	{
		$sReturn = '';

		foreach ($this->js as $aJs)
		{
			$sAsync = $aJs[1] ? ' async="async"' : '';
			$sReturn .= '<script type="text/javascript"' . $sAsync . ' src="' . $aJs[0] . '"></script>' . "\n";
		}

		return $sReturn;
	}

	/**
	 * Get block of linked compressed js
	 * @param boolean $async Run asynchronously, default FALSE
	 * @return string
	 */
	protected function _getJsCompressed($async = FALSE)
	{
		try
		{
			$sReturn = '';

			$oCompression_Controller = Compression_Controller::instance('js');

			foreach ($this->js as $aJs)
			{
				$oCompression_Controller->addJs($aJs[0]);
			}

			$sAsync = $async ? ' async="async"' : '';

			$sPath = $oCompression_Controller->getPath();
			$sReturn .= '<script type="text/javascript"' . $sAsync . ' src="' . $sPath . '"></script>' . "\n";
		}
		catch (Exception $e)
		{
			$sReturn = $this->_getJs();
		}

		return $sReturn;
	}

	/**
	 * Get block of linked js
	 * @param boolean $async Run asynchronously, default FALSE
	 * @return string
	 */
	public function getJs($async = FALSE)
	{
		return Core::moduleIsActive('compression')
			? $this->_getJsCompressed($async)
			: $this->_getJs();
	}

	/**
	 * Show block of linked js
	 * @param boolean $async Run asynchronously, default FALSE
	 * @return Core_Page
	 */
	public function showJs($async = FALSE)
	{
		echo $this->getJs($async);
		return $this;
	}

	/**
	 * Show page title
	 * @return Core_Page
	 */
	public function showTitle()
	{
		echo str_replace('&amp;', '&', htmlspecialchars($this->title));
		return $this;
	}

	/**
	 * Show page description
	 * @return Core_Page
	 */
	public function showDescription()
	{
		echo htmlspecialchars($this->description);
		return $this;
	}

	/**
	 * Show page keywords
	 * @return Core_Page
	 */
	public function showKeywords()
	{
		echo htmlspecialchars($this->keywords);
		return $this;
	}

	/**
	 * Add templates
	 * @param Template_Model $oTemplate Template
	 * @return Core_Page
	 */
	public function addTemplates(Template_Model $oTemplate)
	{
		$aCss = array();

		do {
			$this
				//->css($oTemplate->getTemplateCssFileHref())
				->addChild($oTemplate);

			$aCss[] = $oTemplate->getTemplateCssFileHref();

		} while($oTemplate = $oTemplate->getParent());

		$this->css = array_merge($this->css, array_reverse($aCss));

		return $this;
	}
}