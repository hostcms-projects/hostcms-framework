<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Google sitemap
 * http://www.sitemaps.org/protocol.html
 *
 * @package HostCMS 6\Core
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2013 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Core_Sitemap extends Core_Servant_Properties
{
	/**
	 * Allowed object properties
	 * @var array
	 */
	protected $_allowedProperties = array(
		'showInformationsystemGroups',
		'showInformationsystemItems',
		'showShopGroups',
		'showShopItems',
		'showModifications',
	);

	/**
	 * Site 
	 * @var Site_Model
	 */
	protected $_oSite = NULL;

	/**
	 * Constructor
	 * @param Site_Model $oSite Site object
	 */
	public function __construct(Site_Model $oSite)
	{
		parent::__construct();

		$this->_oSite = $oSite;

		$this->_aSiteuserGroups = array(0, -1);
		if (Core::moduleIsActive('siteuser'))
		{
			$oSiteuser = Core_Entity::factory('Siteuser')->getCurrent();

			if ($oSiteuser)
			{
				$aSiteuser_Groups = $oSiteuser->Siteuser_Groups->findAll();
				foreach ($aSiteuser_Groups as $oSiteuser_Group)
				{
					$this->_aSiteuserGroups[] = $oSiteuser_Group->id;
				}
			}
		}
	}

	/**
	 * List of user groups
	 * @var array
	 */
	protected $_aSiteuserGroups = NULL;

	/**
	 * List of information systems
	 * @var array
	 */
	protected $_Informationsystems = array();
	
	/**
	 * List of shops
	 * @var array
	 */
	protected $_Shops = array();

	/**
	 * Get site
	 * @return Site_Model
	 */
	public function getSite()
	{
		return $this->_oSite;
	}

	/**
	 * Add structure nodes by parent
	 * @param int $structure_id structure ID
	 * @return self
	 */
	protected function _structure($structure_id = 0)
	{
		$oSite = $this->getSite();

		$oStructures = $oSite->Structures;
		$oStructures
			->queryBuilder()
			->where('structures.parent_id', '=', $structure_id)
			->where('structures.active', '=', 1)
			->where('structures.indexing', '=', 1)
			->where('structures.siteuser_group_id', 'IN', $this->_aSiteuserGroups)
			->orderBy('sorting')
			->orderBy('name');

		$aStructure = $oStructures->findAll();

		$dateTime = Core_Date::timestamp2sql(time());

		$oSite_Alias = $oSite->getCurrentAlias();

		foreach ($aStructure as $oStructure)
		{
			$this->addNode('http://' . $oSite_Alias->name . $oStructure->getPath(), $oStructure->changefreq, $oStructure->priority);

			// Informationsystem
			if ($this->showInformationsystemGroups && isset($this->_Informationsystems[$oStructure->id]))
			{
				$oInformationsystem = $this->_Informationsystems[$oStructure->id];

				$oInformationsystem_Groups = $oInformationsystem->Informationsystem_Groups;
				$oInformationsystem_Groups->queryBuilder()
					->where('informationsystem_groups.siteuser_group_id', 'IN', $this->_aSiteuserGroups)
					->where('informationsystem_groups.active', '=', 1)
					->where('informationsystem_groups.indexing', '=', 1);
				$aInformationsystem_Groups = $oInformationsystem_Groups->findAll();

				$path = 'http://' . $oSite_Alias->name . $oInformationsystem->Structure->getPath();

				foreach ($aInformationsystem_Groups as $oInformationsystem_Group)
				{
					$this->addNode($path . $oInformationsystem_Group->getPath(), $oStructure->changefreq, $oStructure->priority);
				}

				// Informationsystem's items
				if ($this->showInformationsystemItems)
				{
					$oInformationsystem_Items = $oInformationsystem->Informationsystem_Items;
					$oInformationsystem_Items->queryBuilder()
						->select('informationsystem_items.*')
						->open()
						->where('informationsystem_items.start_datetime', '<', $dateTime)
						->setOr()
						->where('informationsystem_items.start_datetime', '=', '0000-00-00 00:00:00')
						->close()
						->setAnd()
						->open()
						->where('informationsystem_items.end_datetime', '>', $dateTime)
						->setOr()
						->where('informationsystem_items.end_datetime', '=', '0000-00-00 00:00:00')
						->close()
						->where('informationsystem_items.siteuser_group_id', 'IN', $this->_aSiteuserGroups)
						->where('informationsystem_items.active', '=', 1)
						->where('informationsystem_items.shortcut_id', '=', 0)
						->where('informationsystem_items.indexing', '=', 1);

					$aInformationsystem_Items = $oInformationsystem_Items->findAll();
					foreach ($aInformationsystem_Items as $oInformationsystem_Item)
					{
						$this->addNode($path . $oInformationsystem_Item->getPath(), $oStructure->changefreq, $oStructure->priority);
					}
				}
			}

			// Shop
			if ($this->showShopGroups && isset($this->_Shops[$oStructure->id]))
			{
				$oShop = $this->_Shops[$oStructure->id];

				$oShop_Groups = $oShop->Shop_Groups;
				$oShop_Groups->queryBuilder()
					->where('shop_groups.siteuser_group_id', 'IN', $this->_aSiteuserGroups)
					->where('shop_groups.active', '=', 1)
					->where('shop_groups.indexing', '=', 1);

				$aShop_Groups = $oShop_Groups->findAll();

				$path = 'http://' . $oSite_Alias->name . $oShop->Structure->getPath();
				foreach ($aShop_Groups as $oShop_Group)
				{
					$this->addNode($path . $oShop_Group->getPath(), $oStructure->changefreq, $oStructure->priority);
				}

				// Shop's items
				if ($this->showShopItems)
				{
					$oShop_Items = $oShop->Shop_Items;
					$oShop_Items->queryBuilder()
						->select('shop_items.*')
						->open()
						->where('shop_items.start_datetime', '<', $dateTime)
						->setOr()
						->where('shop_items.start_datetime', '=', '0000-00-00 00:00:00')
						->close()
						->setAnd()
						->open()
						->where('shop_items.end_datetime', '>', $dateTime)
						->setOr()
						->where('shop_items.end_datetime', '=', '0000-00-00 00:00:00')
						->close()
						->where('shop_items.siteuser_group_id', 'IN', $this->_aSiteuserGroups)
						->where('shop_items.active', '=', 1)
						->where('shop_items.shortcut_id', '=', 0)
						->where('shop_items.indexing', '=', 1);
						
						//Modifications
						if (!$this->showModifications)
						{
							$oShop_Items->queryBuilder()
								->where('shop_items.modification_id', '=', 0);
						}

					$aShop_Items = $oShop_Items->findAll();
					foreach ($aShop_Items as $oShop_Item)
					{
						$this->addNode($path . $oShop_Item->getPath(), $oStructure->changefreq, $oStructure->priority);
					}
				}
			}

			$this->_structure($oStructure->id);
		}

		return $this;
	}

	/**
	 * Fill nodes of structure
	 * @return self
	 */
	public function fillNodes()
	{
		$this->_Informationsystems = $this->_Shops = array();

		$oSite = $this->getSite();

		if ($this->showInformationsystemGroups || $this->showInformationsystemItems)
		{
			$aInformationsystems = $oSite->Informationsystems->findAll(FALSE);
			foreach ($aInformationsystems as $oInformationsystem)
			{
				$this->_Informationsystems[$oInformationsystem->structure_id] = $oInformationsystem;
			}
		}

		if ($this->showShopGroups || $this->showShopItems)
		{
			$aShops = $oSite->Shops->findAll(FALSE);
			foreach ($aShops as $oShop)
			{
				$this->_Shops[$oShop->structure_id] = $oShop;
			}
		}

		$this->_structure(0);

		return $this;
	}

	/**
	 * Split sitemap on the several files
	 * @var boolean
	 */
	protected $_createIndex = FALSE;

	/**
	 * Split sitemap on the several files
	 * @param boolean $createIndex create index mode
	 * @return self
	 */
	public function createIndex($createIndex)
	{
		$this->_createIndex = $createIndex;
		return $this;
	}

	/**
	 * Count of nodes per one file
	 * @var int
	 */
	protected $_perFile = NULL;

	/**
	 * Set URL count per file
	 * @param int $perFile count
	 * @return self
	 */
	public function perFile($perFile)
	{
		$this->_perFile = intval($perFile);
		return $this;
	}

	/**
	 * List of sitemap files
	 * @var array
	 */
	protected $_aIndexedFiles = array();

	/**
	 * Current output file 
	 * @var Core_Out_File
	 */
	protected $_currentOut = NULL;

	/**
	 * Get current output file
	 * @return Core_Out_File
	 */
	protected function _getOut()
	{
		if ($this->_createIndex)
		{
			if (is_null($this->_currentOut) || $this->_inFile > $this->_perFile)
			{
				$this->_getNewOutFile();
			}
		}
		elseif (is_null($this->_currentOut))
		{
			$this->_currentOut = new Core_Out_Std();
			$this->_open();
		}

		return $this->_currentOut;
	}

	/**
	 * Count URL in current file
	 * @var int
	 */
	protected $_inFile = 0;
	
	/**
	 * Sitemap files count
	 * @var int
	 */
	protected $_countFile = 0;

	/**
	 * Open current output file
	 * @return self
	 */
	protected function _open()
	{
		$this->_currentOut->open();
		$this->_currentOut->write('<?xml version="1.0" encoding="UTF-8"?>' . "\n")
			->write('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n");
		return $this;
	}

	/**
	 * Close current output file
	 * @return self
	 */
	protected function _close()
	{
		$this->_currentOut->write("</urlset>\n");
		$this->_currentOut->close();
		return $this;
	}

	/**
	 * Get new file for sitemap
	 */
	protected function _getNewOutFile()
	{
		if (!is_null($this->_currentOut))
		{
			$this->_close();

			$this->_countFile++;
			$this->_inFile = 0;
		}

		$this->_aIndexedFiles[] = $filename = "sitemap{$this->_countFile}.xml";

		$this->_currentOut = new Core_Out_File();
		$this->_currentOut->filePath(CMS_FOLDER . $filename);
		$this->_open();
	}

	/**
	 * Add node to sitemap
	 * @param string $loc location
	 * @param int $changefreq change frequency
	 * @param float $priority priority
	 * @return self
	 */
	public function addNode($loc, $changefreq, $priority)
	{
		switch ($changefreq)
		{
			case 0 : $sChangefreq = 'always'; break;
			case 1 : $sChangefreq = 'hourly'; break;
			default:
			case 2 : $sChangefreq = 'daily'; break;
			case 3 : $sChangefreq = 'weekly'; break;
			case 4 : $sChangefreq = 'monthly'; break;
			case 5 : $sChangefreq = 'yearly'; break;
			case 6 : $sChangefreq = 'never'; break;
		}

		$this->_getOut()->write(
			"<url>\n" .
			"<loc>{$loc}</loc>\n" .
			"<changefreq>" . Core_Str::xml($sChangefreq) . "</changefreq>\n" .
			"<priority>" . Core_Str::xml($priority) . "</priority>\n" .
			"</url>\n"
		);

		$this->_inFile++;

		return $this;
	}

	/**
	 * Executes the business logic.
	 */
	public function execute()
	{
		$this->_close();

		if ($this->_createIndex)
		{
			echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";

			$oSite_Alias = $this->_oSite->getCurrentAlias();

			echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
			foreach ($this->_aIndexedFiles as $filename)
			{
				echo "<sitemap>\n";
				echo "<loc>http://{$oSite_Alias->name}/{$filename}</loc>";
				echo "<lastmod>" . date('Y-m-d') . "</lastmod>";
				echo "</sitemap>\n";
			}

			echo '</sitemapindex>';
		}

		return $this;
	}
}