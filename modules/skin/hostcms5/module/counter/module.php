<?php

/**
 * Counters.
 *
 * @package HostCMS 6\Skin
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Skin_Hostcms5_Module_Counter_Module extends Counter_Dataset
{
	/**
	 * Show admin widget
	 * @param int $type
	 * @param boolean $ajax
	 * @return self
	 */
	public function adminPage($type = 0, $ajax = FALSE)
	{
		?>
		<td width="50%" valign="top" class="index_table_td">
			<div class="main_div"><span class="div_title"><?php echo Core::_('Counter.index_all_stat')?></span>
				<div class="div_content">
					<table cellspacing="2" cellpadding="2" width="100%" class="admin_table">
						<tr class="admin_table_title">
							<td></td>
							<td align="center"><?php echo Core::_('Counter.index_all_stat_today')?></td>
							<td align="center"><?php echo Core::_('Counter.index_all_stat_yesterday')?></td>
							<td align="center"><?php echo Core::_('Counter.index_all_stat_7_day')?></td>
							<td align="center"><?php echo Core::_('Counter.index_all_stat_30_day')?></td>
							<td align="center"><?php echo Core::_('Counter.index_all_stat_all')?></td>
						</tr>

						<?php
						foreach ($this->_getCounter()->_objects as $key => $oCounterEntity)
						{
							Core::factory('Core_Html_Entity_Tr')
								->add(Core::factory('Core_Html_Entity_Td')->value($oCounterEntity->param))
								->add(Core::factory('Core_Html_Entity_Td')->value($oCounterEntity->today)->align('center'))
								->add(Core::factory('Core_Html_Entity_Td')->value($oCounterEntity->yesterday)->align('center'))
								->add(Core::factory('Core_Html_Entity_Td')->value($oCounterEntity->seven_day)->align('center'))
								->add(Core::factory('Core_Html_Entity_Td')->value($oCounterEntity->thirty_day)->align('center'))
								->add(Core::factory('Core_Html_Entity_Td')->value($oCounterEntity->all_days)->align('center'))
								->execute();
						}
						?>
					</table>
					<span style="margin: 5px 0px 5px 0px"><a 
						href="/admin/counter/index.php" onclick="$.adminLoad({path: '/admin/counter/index.php'}); return false">
						<?php echo Core::_('Counter.index_show_counter_link')?></a>
					</span>
				</div>
			</div>
		</td>
		<?php 				
		return true;
	}
}