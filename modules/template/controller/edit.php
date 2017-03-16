<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Templates.
 *
 * @package HostCMS 6\Template
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2013 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Template_Controller_Edit extends Admin_Form_Action_Controller_Type_Edit
{
	/**
	 * Set object
	 * @param object $object object
	 * @return self
	 */
	public function setObject($object)
	{
		$modelName = $object->getModelName();

		if (is_null($object->id))
		{
			$object->site_id = CURRENT_SITE;

			if ($modelName == 'template')
			{
				$object->template_id = intval(Core_Array::getGet('template_id', 0));
				$object->template_dir_id = intval(Core_Array::getGet('template_dir_id', 0));
			}
		}

		$this
			->addSkipColumn('timestamp')
			->addSkipColumn('data_template_id');

		parent::setObject($object);

		$oMainTab = $this->getTab('main');
		$oAdditionalTab = $this->getTab('additional');

		/*$oMainTab->move(
			$this->getField('timestamp')
			//->divAttr(array('style' => 'display: none'))
			, $oAdditionalTab
		);*/

		$oSelect_Dirs = new Admin_Form_Entity_Select();

		switch($modelName)
		{
			case 'template':
				$title = $this->_object->id
					? Core::_('Template.title_edit', $this->_object->name)
					: Core::_('Template.title_add');

				$oTemplateTab = Core::factory('Admin_Form_Entity_Tab')
					->caption(Core::_('Template.tab_1'))
					->name('Template');

				$oCssTab = Core::factory('Admin_Form_Entity_Tab')
					->caption(Core::_('Template.tab_2'))
					->name('Css');

				$this
					->addTabAfter($oTemplateTab, $oMainTab)
					->addTabAfter($oCssTab, $oTemplateTab);

				// Удаляем стандартный <input>
				$oAdditionalTab->delete(
					 $this->getField('template_dir_id')
				);

				// Селектор с группой
				$oSelect_Dirs
					->options(
						array(' … ') + $this->fillTemplateDir()
					)
					->name('template_dir_id')
					->value($this->_object->template_dir_id)
					->caption(Core::_('Template.template_dir_id'));

				$oMainTab->addAfter(
					$oSelect_Dirs, $this->getField('name')
				);

				$this->getField('sorting')
					->style('width: 220px');

				$oTemplate_Textarea = new Admin_Form_Entity_Textarea();

				$oTemplate_Textarea
					->value(
						$this->_object->loadTemplateFile()
					)
					->cols(140)
					->rows(30)
					->caption(Core::_('Template.template'))
					->name('template');

				$oTemplateTab->add($oTemplate_Textarea);

				$oCss_Textarea = new Admin_Form_Entity_Textarea();

				$oCss_Textarea
					->value(
						$this->_object->loadTemplateCssFile()
					)
					->cols(140)
					->rows(30)
					->caption(Core::_('Template.css'))
					->name('css');

				$oCssTab->add($oCss_Textarea);

			break;
			case 'template_dir':
			default:
				$title = $this->_object->id
					? Core::_('Template_Dir.title_edit')
					: Core::_('Template_Dir.title_add');

				// Значения директории для добавляемого объекта
				if (is_null($this->_object->id))
				{
					$this->_object->parent_id = Core_Array::getGet('template_dir_id');
				}

				// Удаляем стандартный <input>
				$oAdditionalTab->delete(
					 $this->getField('parent_id')
				);

				$oSelect_Dirs
					->options(
						array(' … ') + $this->fillTemplateDir(0, $this->_object->id)
					)
					->name('parent_id')
					->value($this->_object->parent_id)
					->caption(Core::_('Template_Dir.parent_id'));

				$oMainTab->addAfter($oSelect_Dirs,  $this->getField('name'));

				$this->getField('sorting')
					->style('width: 220px');
			break;
		}

		$this->title(
			html_entity_decode($title)
		);

		return $this;
	}

	/**
	 * Processing of the form. Apply object fields.
	 * @return this
	 */
	protected function _applyObjectProperty()
	{
		parent::_applyObjectProperty();

		$modelName = $this->_object->getModelName();

		if ($modelName == 'template')
		{
			$this->_object->saveTemplateFile(Core_Array::getPost('template'));
			$this->_object->saveTemplateCssFile(Core_Array::getPost('css'));
		}

		return $this;
	}

	/**
	 * Build visual representation of templates tree
	 * @param int $iSiteId site ID
	 * @param int $itemplateId start template ID
	 * @param int $iLevel current nesting level
	 * @return array
	 */
	public function fillTemplateList($iSiteId, $itemplateId = 0, $iLevel = 0)
	{
		$iSiteId = intval($iSiteId);

		$aTemplates = Core_Entity::factory('Template');

		$aTemplates->queryBuilder()
			//->clear()
			->where('site_id', '=', $iSiteId)
			->where('template_id', '=', $itemplateId)
			->orderBy('templates.sorting', 'ASC')
			->orderBy('templates.name', 'ASC');

		$aTemplates = $aTemplates->findAll();

		$aReturn = array();
		if (count($aTemplates))
		{
			foreach ($aTemplates as $children)
			{
				$aReturn[$children->id] = str_repeat('  ', $iLevel) . $children->name;
				$aReturn += $this->fillTemplateList($iSiteId, $children->id, $iLevel + 1);
			}
		}

		return $aReturn;
	}

	/**
	 * Create visual tree of the directories
	 * @param int $iTemplateDirParentId parent directory ID
	 * @param boolean $bExclude exclude group ID
	 * @param int $iLevel current nesting level
	 * @return array
	 */
	public function fillTemplateDir($iTemplateDirParentId = 0, $bExclude = FALSE, $iLevel = 0)
	{
		$iTemplateDirParentId = intval($iTemplateDirParentId);
		$iLevel = intval($iLevel);

		$oTemplate_Dir = Core_Entity::factory('Template_Dir', $iTemplateDirParentId);

		$aReturn = array();

		// Дочерние разделы
		$childrenDirs = $oTemplate_Dir->Template_Dirs;
		$childrenDirs->queryBuilder()
			->where('site_id', '=', CURRENT_SITE);

		$childrenDirs = $childrenDirs->findAll();

		if (count($childrenDirs))
		{
			foreach ($childrenDirs as $childrenDir)
			{
				if ($bExclude != $childrenDir->id)
				{
					$aReturn[$childrenDir->id] = str_repeat('  ', $iLevel) . $childrenDir->name;
					$aReturn += $this->fillTemplateDir($childrenDir->id, $bExclude, $iLevel + 1);
				}
			}
		}

		return $aReturn;
	}
}