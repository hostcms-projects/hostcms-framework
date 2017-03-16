<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Filemanager.
 *
 * @package HostCMS 6\Wysiwyg
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2013 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Wysiwyg_Filemanager_File extends Core_Entity
{
	/**
	 * Model name
	 * @var mixed
	 */
	protected $_modelName = 'wysiwyg_filemanager';

	/**
	 * Backend property
	 * @var string
	 */
	public $hash = NULL;

	/**
	 * Backend property
	 * @var string
	 */
	public $name = NULL;

	/**
	 * Backend property
	 * @var string
	 */
	public $type = NULL;

	/**
	 * Backend property
	 * @var string
	 */
	public $datetime = NULL;

	/**
	 * Backend property
	 * @var string
	 */
	public $size = NULL;

	/**
	 * Backend property
	 * @var string
	 */
	public $mode = NULL;

	/**
	 * Backend property
	 * @var string
	 */
	public $path = NULL;

	/**
	 * Backend property
	 * @var int
	 */
	public $user_id = 0;

	/**
	 * Backend property
	 * @var int
	 */
	public $download = 0;

	/**
	 * Load columns list
	 * @return self
	 */
	protected function _loadColumns()
	{
		return $this;
	}

	/**
	 * Sorting field
	 * @var string
	 */
	protected $_sortField = NULL;

	/**
	 * Set sorting field
	 * @param string $sortField
	 */
	public function setSortField($sortField)
	{
		$this->_sortField = $sortField;
	}

	/**
	 * Get sorting field
	 * @return string
	 */
	public function getSortField()
	{
		return $this->_sortField;
	}

	/**
	 * Get primary key name
	 * @return string
	 */
	public function getPrimaryKeyName()
	{
		return 'hash';
	}

	/**
	 * Delete object from database
	 * @param mixed $primaryKey primary key for deleting object
	 * @return Core_Entity
	 */
	public function delete($primaryKey = NULL)
	{
		$filePath = $this->path . DIRECTORY_SEPARATOR . Core_File::convertfileNameToLocalEncoding($this->name);

		$this->type == 'file'
			? Core_File::delete($filePath)
			: Core_File::deleteDir($filePath);

		return $this;
	}

	/**
	 * Callback function
	 * @return string
	 */
	public function adminDownload()
	{
		if ($this->type == 'file')
		{
			ob_start();
			$oCore_Html_Entity_Img = Core::factory('Core_Html_Entity_A')
				->add(
					Core::factory('Core_Html_Entity_Img')
						->src('/admin/images/disk.gif')
				)
				->href("/admin/filemanager/index.php?hostcms[action]=download&cdir=" . rawurlencode(Core_File::pathCorrection(Core_Array::getRequest('cdir'))) . "&dir=" . rawurlencode(Core_File::pathCorrection(Core_Array::getRequest('dir'))) ."&hostcms[checked][1][{$this->hash}]=1")
				->target('_blank')
				->execute();
			return ob_get_clean();
		}
	}

	/**
	 * Download file
	 */
	public function download()
	{
		$filePath = $this->path . DIRECTORY_SEPARATOR . Core_File::convertfileNameToLocalEncoding($this->name);

		Core_File::download($filePath, $this->name, array('content_disposition' => 'attachment'));
		exit();
	}

	/**
	 * Get table columns
	 * @return array
	 */
	public function getTableColums()
	{
		return array_flip(
			array('hash', 'name', 'type', 'datetime', 'size', 'mode', 'user_id')
		);
	}

	/**
	 * Get file image
	 */
	public function image()
	{
		if ($this->type == 'file')
		{
			// Ассоциированные иконки
			$ext = Core_File::getExtension($this->name);

			$icon_file = '/admin/images/icons/' . (isset(Core::$mainConfig['fileIcons'][$ext]) ? Core::$mainConfig['fileIcons'][$ext] : 'file.gif');
		}
		else
		{
			$icon_file = $this->name == '..'
				? '/admin/images/top_point.gif'
				: '/admin/images/folder.gif';
		}

		?><img src="<?php echo $icon_file?>" /><?php
	}

	/**
	 * Get file datetime
	 * @return string
	 */
	public function datetime()
	{
		return Core_Date::sql2datetime($this->datetime);
	}
}