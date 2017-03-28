<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Search. Backend's Index Pages and Widget.
 *
 * @package HostCMS
 * @subpackage Skin
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2016 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Skin_Bootstrap_Module_Search_Module extends Search_Module
{
	/**
	 * Constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_adminPages = array(
			1 => array('title' => Core::_('Search.title'))
		);
	}

	public function widget()
	{
		?><!-- Search -->
		<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<div class="databox radius-bordered databox-shadowed hostcms-widget-databox">
				<div class="databox-left bg-palegreen">
					<div class="databox-piechart">
						<?php
						$iSearchPagesOnCurrentSite = Search_Controller::instance()->getPageCount(CURRENT_SITE);
						// Общее количество проиндексированных страниц
						//$iSearchPagesTotal = Search_Controller::instance()->getPageCount(NULL);

						/*$iPercent = $iSearchPagesTotal > 0
							? floor($iSearchPagesOnCurrentSite * 100 / $iSearchPagesTotal)
							: 0;
						?><div id="searchWidget" class="easyPieChart" data-barcolor="#fff" data-linecap="butt" data-percent="<?php echo $iPercent?>" data-animate="500" data-linewidth="3" data-size="47" data-trackcolor="rgba(255,255,255,0.1)"><span class="white font-90"><?php echo $iPercent?>%</span></div>*/
						?>
						<a href="/admin/search/index.php" onclick="$.adminLoad({path: '/admin/search/index.php'}); return false"><i class="fa fa-search fa-3x"></i></a>
					</div>
				</div>
				<div class="databox-right">
					<span class="databox-number palegreen"><?php echo number_format($iSearchPagesOnCurrentSite, 0, '.', ' ')?></span>
					<div class="databox-text"><?php echo Core::_('Search.indexed')?></div>
					<div class="databox-stat palegreen radius-bordered">
						<i class="stat-icon icon-lg fa fa-search"></i>
					</div>
				</div>
			</div>
		</div>
		
		<script>
		/*$(function() {
			setTimeout(function() {

					var searchWidget = $('#searchWidget');
				
					var barColor = getcolor(searchWidget.data('barcolor')) || themeprimary,
						trackColor = getcolor(searchWidget.data('trackcolor')) || false,
						scaleColor = getcolor(searchWidget.data('scalecolor')) || false,
						lineCap = searchWidget.data('linecap') || "round",
						lineWidth = searchWidget.data('linewidth') || 3,
						size = searchWidget.data('size') || 110,
						animate = searchWidget.data('animate') || false;

					searchWidget.easyPieChart({
						barColor: barColor,
						trackColor: trackColor,
						scaleColor: scaleColor,
						lineCap: lineCap,
						lineWidth: lineWidth,
						size: size,
						animate : animate
					});

			}, 500);
		});*/
		</script>
		<?php
	}
}