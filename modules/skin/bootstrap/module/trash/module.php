<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Trash. Backend's Index Pages and Widget.
 *
 * @package HostCMS
 * @subpackage Skin
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2016 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Skin_Bootstrap_Module_Trash_Module extends Trash_Module
{
	/**
	 * Constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_adminPages = array(
			1 => array('title' => Core::_('Trash.menu'))
		);
	}

	public function widget()
	{
		?><!-- Trash -->
		<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<div class="databox radius-bordered databox-shadowed hostcms-widget-databox">
				<div class="databox-left bg-themesecondary">
					<div class="databox-piechart">
						<a href="/admin/trash/index.php" onclick="$.adminLoad({path: '/admin/trash/index.php'}); return false"><i class="fa fa-trash fa-3x"></i></a>
					</div>
				</div>
				<?php
				$iDeleted = 0;

				$oDataBase = Core_DataBase::instance();
				$aTables = $oDataBase->getTables();
				$queryBuilder = Core_QueryBuilder::select();

				foreach ($aTables as $key => $name)
				{
					$aColumns = $oDataBase->getColumns($name);

					if (isset($aColumns['deleted']))
					{
						$row = $queryBuilder
							->clear()
							->select(array('COUNT(*)', 'count'))
							->from($name)
							->where('deleted', '=', 1)
							->execute()
							->asAssoc()
							->current();

						if ($row['count'])
						{
							$iDeleted += $row['count'];
						}
					}
				}
				?>
				<div class="databox-right">
					<span class="databox-number themesecondary"><?php echo number_format($iDeleted, 0, '.', ' ')?></span>
					<div class="databox-text"><?php echo $iDeleted
						? Core::_('Trash.mark-deleted')
						: Core::_('Trash.empty')?></div>
					<div class="databox-stat themesecondary radius-bordered">
						<i class="stat-icon icon-lg fa fa-trash"></i>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}