<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Lib_Model
 *
 * @package HostCMS
 * @subpackage Lib
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2017 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Lib_Model extends Core_Entity
{
	/**
	 * Backend property
	 * @var int
	 */
	public $img = 1;

	/**
	 * Backend property
	 * @var int
	 */
	public $properties = 1;

	/**
	 * Belongs to relations
	 * @var array
	 */
	protected $_belongsTo = array(
		'lib_dir' => array(),
		'user' => array()
	);

	/**
	 * One-to-many or many-to-many relations
	 * @var array
	 */
	protected $_hasMany = array(
		'lib_property' => array(),
		'template_section_lib' => array()
	);

	/**
	 * Constructor.
	 * @param int $id entity ID
	 */
	public function __construct($id = NULL)
	{
		parent::__construct($id);

		if (is_null($id))
		{
			$oUserCurrent = Core_Entity::factory('User', 0)->getCurrent();
			$this->_preloadValues['user_id'] = is_null($oUserCurrent) ? 0 : $oUserCurrent->id;
		}
	}

	/**
	 * Get lib's directory path
	 * @return string
	 */
	public function getLibPath()
	{
		return CMS_FOLDER . "hostcmsfiles/lib/lib_" . intval($this->id) . DIRECTORY_SEPARATOR;
	}

	/**
	 * Get lib file path
	 * @return string
	 */
	public function getLibFilePath()
	{
		return $this->getLibPath() . "lib_" . intval($this->id) . ".php";
	}

	/**
	 * Get configuration file path
	 * @return string
	 */
	public function getLibConfigFilePath()
	{
		return $this->getLibPath() . "lib_config_" . intval($this->id) . ".php";
	}

	/**
	 * Get dat file path
	 * @param int $structure_id structure id
	 * @return string
	 */
	public function getLibDatFilePath($structure_id)
	{
		return $this->getLibPath() . "lib_values_" . intval($structure_id) . ".dat";
	}

	/**
	 * Save dat file
	 * @param array $array data
	 * @param int $structure_id structure id
	 */
	public function saveDatFile(array $array, $structure_id)
	{
		$this->save();

		$oStructure = Core_Entity::factory('Structure', $structure_id);
		$oStructure->options = json_encode($array);
		$oStructure->save();

		/*
		$sLibDatFilePath = $this->getLibDatFilePath($structure_id);
		Core_File::mkdir(dirname($sLibDatFilePath), CHMOD, TRUE);

		foreach ($array as $key => $value)
		{
			if (strtolower($value) == "false")
			{
				$values[$key] = FALSE;
			}
			elseif (strtolower($value) == "true")
			{
				$values[$key] = TRUE;
			}
		}

		$content = strval(serialize($array));
		Core_File::write($sLibDatFilePath, $content);*/
	}

	/**
	 * Get array for options
	 * @param int $structure_id structure id
	 * @return array
	 */
	public function getDat($structure_id)
	{
		$return = array();

		$oStructure = Core_Entity::factory('Structure', $structure_id);

		if (!is_null($oStructure->options))
		{
			$return = json_decode($oStructure->options, TRUE);
		}
		// Backward compatibility
		else
		{
			$datContent = $this->loadDatFile($structure_id);
			if ($datContent)
			{
				$array = @unserialize(strval($datContent));
				$return = Core_Type_Conversion::toArray($array);
			}
		}

		return $return;
	}

	/**
	 * Read dat file content
	 * @param int $structure_id structure id
	 * @return string|NULL
	 */
	public function loadDatFile($structure_id)
	{
		$path = $this->getLibDatFilePath($structure_id);

		return is_file($path)
			? Core_File::read($path)
			: NULL;
	}

	/**
	 * Delete object from database
	 * @param mixed $primaryKey primary key for deleting object
	 * @return self
	 * @hostcms-event lib.onBeforeRedeclaredDelete
	 */
	public function delete($primaryKey = NULL)
	{
		if (is_null($primaryKey))
		{
			$primaryKey = $this->getPrimaryKey();
		}

		$this->id = $primaryKey;

		Core_Event::notify($this->_modelName . '.onBeforeRedeclaredDelete', $this, array($primaryKey));
		
		if (Core::moduleIsActive('revision'))
		{
			Revision_Controller::delete($this->getModelName(), $this->id);
		}		
		
		// Удаляем код и настройки
		try
		{
			Core_File::delete($this->getLibFilePath());
		} catch (Exception $e) {}

		try
		{
			Core_File::delete($this->getLibConfigFilePath());
		} catch (Exception $e) {}

		try
		{
			Core_File::deleteDir($this->getLibPath());
		} catch (Exception $e) {}

		$this->Lib_Properties->deleteAll(FALSE);

		return parent::delete($primaryKey);
	}

	/**
	 * Save lib content
	 * @param string $content content
	 */
	public function saveLibFile($content)
	{
		$this->save();

		Core_File::mkdir(dirname($sLibFilePath = $this->getLibFilePath()), CHMOD, TRUE);

		$content = trim($content);
		Core_File::write($sLibFilePath, $content);
	}

	/**
	 * Save config content
	 * @param string $content content
	 */
	public function saveLibConfigFile($content)
	{
		$this->save();

		Core_File::mkdir(dirname($sLibConfigFilePath = $this->getLibConfigFilePath()), CHMOD, TRUE);

		$content = trim($content);
		Core_File::write($sLibConfigFilePath, $content);
	}

	/**
	 * Get lib file content
	 * @return string|NULL
	 */
	public function loadLibFile()
	{
		$path = $this->getLibFilePath();

		if (is_file($path))
		{
			return Core_File::read($path);
		}
		else
		{
			return NULL;
		}
	}

	/**
	 * Get config file content
	 * @return string|NULL
	 */
	public function loadLibConfigFile()
	{
		$path = $this->getLibConfigFilePath();

		if (is_file($path))
		{
			return Core_File::read($path);
		}
		else
		{
			return NULL;
		}
	}

	/**
	 * Executes the business logic.
	 * @hostcms-event lib.onBeforeExecute
	 * @hostcms-event lib.onAfterExecute
	 */
	public function execute()
	{
		Core_Event::notify($this->_modelName . '.onBeforeExecute', $this);

		include $this->getLibFilePath();

		Core_Event::notify($this->_modelName . '.onAfterExecute', $this);

		return $this;
	}

	/**
	 * Copy object
	 * @return Core_Entity
	 */
	public function copy()
	{
		$newObject = parent::copy();

		try
		{
			Core_File::copy($this->getLibFilePath(), $newObject->getLibFilePath());
		} catch (Exception $e) {}

		try
		{
			Core_File::copy($this->getLibConfigFilePath(), $newObject->getLibConfigFilePath());
		} catch (Exception $e) {}

		$aLibProperties = $this->lib_properties->findAll();

		foreach($aLibProperties as $oLibProperty)
		{
			$newObject->add($oLibProperty->copy());
		}

		return $newObject;
	}

	/**
	 * Backup revision
	 * @return self
	 */
	public function backupRevision()
	{
		if (Core::moduleIsActive('revision'))
		{
			$aBackup = array(
				'name' => $this->name,
				'lib_dir_id' => $this->lib_dir_id,
				'description' => $this->description,
				'lib' => $this->loadLibFile(),
				'lib_config' => $this->loadLibConfigFile(),
				'user_id' => $this->user_id
			);

			Revision_Controller::backup($this, $aBackup);
		}

		return $this;
	}

	/**
	 * Rollback Revision
	 * @param int $revision_id Revision ID
	 * @return self
	 */
	public function rollbackRevision($revision_id)
	{
		if (Core::moduleIsActive('revision'))
		{
			$oRevision = Core_Entity::factory('Revision', $revision_id);

			$aBackup = json_decode($oRevision->value, TRUE);

			if (is_array($aBackup))
			{
				$this->name = Core_Array::get($aBackup, 'name');
				$this->description = Core_Array::get($aBackup, 'description');
				$this->save();

				$this->saveLibFile(Core_Array::get($aBackup, 'lib'));
				$this->saveLibConfigFile(Core_Array::get($aBackup, 'lib_config'));
			}
		}

		return $this;
	}
}