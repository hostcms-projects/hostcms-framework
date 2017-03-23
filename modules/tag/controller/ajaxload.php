<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 *
 * Контроллер загрузки списка тегов(меток), соответствующих фильтру
 *
 * @package HostCMS 6\Tag
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Tag_Controller_Ajaxload extends Admin_Form_Action_Controller
{
	/**
	 * Tag filter
	 * @var string
	 */
	protected $_tagFilter = null;
	/**
	 * Set tag filter
	 * @param string $tagFilter tag filter
	 * @return self
	 */
	public function query($tagFilter = '')
	{
		$this->_tagFilter = $tagFilter;
		return $this;
	}
	/**
	 * Execute business logic
	 * @param string $operation operation
	 */
	public function execute($operation = NULL)
	{		
		$aReturn = array();
				
		$oTag = Core_Entity::factory('Tag');
		$oTag->queryBuilder()->orderBy('name');
		$aTags = $oTag->getAllByName('%' . $this->_tagFilter . '%', FALSE, 'LIKE');
		
		foreach ($aTags as $oTag)
		{
			$aReturn[] = $oTag->name;
		}

		Core::showJson($aReturn);
	}
}