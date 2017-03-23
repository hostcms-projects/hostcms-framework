<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Skin.
 *
 * @package HostCMS 6\Skin
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Skin_Bootstrap extends Core_Skin
{
	/**
	 * Name of the skin
	 * @var string
	 */
	protected $_skinName = 'bootstrap';

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$lng = $this->getLng();

		$this
			->addJs('/modules/skin/' . $this->_skinName . '/js/skins.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/jquery-2.0.3.min.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/bootstrap.min.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/bootstrap-hostcms.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/main.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/datetime/moment.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/datetime/bootstrap-datetimepicker.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/datetime/ru.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/charts/flot/jquery.flot.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/charts/flot/jquery.flot.time.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/charts/flot/jquery.flot.tooltip.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/charts/flot/jquery.flot.crosshair.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/charts/flot/jquery.flot.resize.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/charts/flot/jquery.flot.selection.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/charts/flot/jquery.flot.pie.min.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/jquery.slimscroll.min.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/bootbox/bootbox.js')

			->addJs('/modules/skin/' . $this->_skinName . '/js/charts/easypiechart/jquery.easypiechart.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/charts/easypiechart/easypiechart-init.js')

			->addJs('/modules/skin/' . $this->_skinName . '/js/charts/sparkline/jquery.sparkline.js')

			->addJs('/modules/skin/' . $this->_skinName . '/js/jquery.form.js')

			//->addJs('/modules/skin/' . $this->_skinName . '/js/charts/morris/raphael-2.0.2.min.js')
			//->addJs('/modules/skin/' . $this->_skinName . '/js/charts/morris/morris.js')
			//->addJs('/modules/skin/' . $this->_skinName . '/js/charts/morris/morris-init.js')

			->addJs('/modules/skin/' . $this->_skinName . '/js/codemirror/lib/codemirror.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/codemirror/mode/css/css.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/codemirror/mode/htmlmixed/htmlmixed.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/codemirror/mode/javascript/javascript.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/codemirror/mode/clike/clike.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/codemirror/mode/php/php.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/codemirror/mode/xml/xml.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/codemirror/addon/selection/active-line.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/codemirror/addon/search/search.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/codemirror/addon/search/searchcursor.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/codemirror/addon/dialog/dialog.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/star-rating.min.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/typeahead-bs2.min.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/bootstrap-tag.js')
			->addJs('/modules/skin/' . $this->_skinName . '/js/ui/jquery-ui.min.js')
			//->addJs('/modules/skin/' . $this->_skinName . '/js/ace.js')
			;

		$this
			->addCss('/modules/skin/' . $this->_skinName . '/css/bootstrap.min.css')
			->addCss('/modules/skin/' . $this->_skinName . '/css/font-awesome.min.css')
			->addCss('/modules/skin/' . $this->_skinName . '/css/hostcms.min.css')
			->addCss('/modules/skin/' . $this->_skinName . '/css/animate.min.css')
			->addCss('/modules/skin/' . $this->_skinName . '/css/dataTables.bootstrap.css')
			->addCss('/modules/skin/' . $this->_skinName . '/css/bootstrap-datetimepicker.css')
			->addCss('/modules/skin/' . $this->_skinName . '/js/codemirror/lib/codemirror.css')
			->addCss('/modules/skin/' . $this->_skinName . '/js/codemirror/addon/dialog/dialog.css')
			->addCss('/modules/skin/' . $this->_skinName . '/css/star-rating.min.css')
			->addCss('/modules/skin/' . $this->_skinName . '/css/bootstrap-hostcms.css')
			;
	}

	/**
	 * Show HTML head
	 */
	public function showHead()
	{
		$timestamp = $this->_getTimestamp();

		$lng = $this->getLng();

		foreach ($this->_css as $sPath)
		{
			?><link type="text/css" href="<?php echo $sPath . '?' . $timestamp?>" rel="stylesheet" /><?php
			echo PHP_EOL;
		}?>

		<?php
		$this->addJs('/modules/skin/' . $this->_skinName . "/js/lng/{$lng}/{$lng}.js");
		foreach ($this->_js as $sPath)
		{
			Core::factory('Core_Html_Entity_Script')
				->type("text/javascript")
				->src($sPath . '?' . $timestamp)
				->execute();
		}
		?>
		<!--Fonts-->
		<link href="//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin,cyrillic" rel="stylesheet" type="text/css">

		<script type="text/javascript">
		$(function() {
		$('body').tooltip({ selector: 'acronym', template: '<div class="tooltip-magenta tooltip" role="tooltip"><div class="tooltip-inner"></div><div class="tooltip-arrow"></div></div>' });
		});
		<?php
		if (Core_Auth::logged())
		{
			// Получаем данные о корневом пути для группы, в которой размещен текущий пользователь
			$oUser = Core_Entity::factory('User')->getCurrent();
			?>var HostCMSFileManager = new HostCMSFileManager('<?php echo Core_Str::escapeJavascriptVariable($oUser ? $oUser->User_Group->root_dir : '')?>');

			<?php if (!defined('CONFIRM_CLOSE_BROWSER') || CONFIRM_CLOSE_BROWSER)
			{
				?>$(window).on('beforeunload', function () {return ' ';});<?php
			}
		}
		?>
		</script>

		<script type="text/javascript" src="/admin/wysiwyg/jquery.tinymce.js"></script>
		<?php

		return $this;
	}

	protected function _navBar()
	{
		$oUser = Core_Entity::factory('User')->getCurrent();

		?><!-- Navbar -->
		<div class="navbar">
			<div class="navbar-inner">
				<div class="navbar-container">
					<!-- Navbar Barnd -->
					<div class="navbar-header pull-left">
						<a href="/admin/" <?php echo isset($_SESSION['valid_user'])
							? 'onclick="'."$.adminLoad({path: '/admin/index.php'}); return false".'"'
							: ''?> class="navbar-brand"><?php
							$sLogoTitle = Core_Auth::logged() ? ' v. ' . htmlspecialchars(CURRENT_VERSION) : '';
							?><img src="/modules/skin/bootstrap/img/logo-white.png" alt="(^) HostCMS" title="HostCMS <?php echo $sLogoTitle?>" /></a>
					</div>
					<!-- /Navbar Barnd -->
					<!-- Sidebar Collapse -->
					<div class="sidebar-collapse" id="sidebar-collapse">
						<i class="collapse-icon fa fa-bars"></i>
					</div>
					<!-- /Sidebar Collapse -->
					<!-- Account Area and Settings -->
					<div class="navbar-header pull-right">
					<?php
					if (Core_Auth::logged())
					{
						?><div class="navbar-account">
							<ul class="account-area">
								<li>
								<?php
								$oCurrentSite = Core_Entity::factory('Site', CURRENT_SITE);
								$oAlias = $oCurrentSite->getCurrentAlias();

								if (!is_null($oAlias))
								{
									?><a title="<?php echo Core::_('Admin.viewSite')?>" target="_blank" href="//<?php echo Core_Str::escapeJavascriptVariable($oAlias->name)?>">
										<i class="icon fa fa-desktop"></i>
									</a><?php
								}
								?>
								</li>
								<li>
									<span class="account-area-site-name hidden-xs">
										<?php echo htmlspecialchars($oCurrentSite->name)?>
									</span>
									<?php
									$aSites = $oUser->getSites();
									?>
									<a class="dropdown-toggle" data-toggle="dropdown" title="Выберите сайт" href="#">
										<i class="icon fa fa-globe"></i>
										<span class="badge"><?php echo count($aSites)?></span>
									</a>
									<!--Tasks Dropdown-->
									<div id="sitesListBox" class="pull-right dropdown-menu dropdown-arrow dropdown-notifications">
										<div class="scroll-sites">
											<ul>
											<?php
											$aSiteColors = array(
												'bg-themeprimary',
												'bg-darkorange',
												'bg-warning',
												'bg-success'
											);
											$iCountColor = 0;
											$sListSitesContent = '';

											foreach ($aSites as $oSite)
											{
												$oSite_Alias = $oSite->Site_Aliases->getByCurrent(1);

												if ($oSite->id != CURRENT_SITE)
												{
													$sListSitesContent .= '<li>
														<a href="/admin/index.php?changeSiteId=' . $oSite->id . '"' . '>
															<div class="clearfix">
																<div class="notification-icon">
																	<i class="fa '. $aSiteColors[$iCountColor < 4 ? $iCountColor++ : $iCountColor = 0] . ' white hostcms-font"><b>' . $oSite->id . '</b></i>
																</div>
																<div class="notification-body">
																	<span class="title">' . Core_Str::cut($oSite->name, 35) . '</span>
																	<span class="description">' .

																	 (!is_null($oSite_Alias)
																		? htmlspecialchars($oSite_Alias->name)
																		: 'undefined' ) . '
																	</span>
																</div>
															</div>
														</a></li>';
												}
												else
												{
													$sListSitesContent = '<li>
														<a>
															<div class="clearfix">
																<div class="notification-icon">
																	<i class="fa ' . $aSiteColors[$iCountColor < 4 ? $iCountColor++ : $iCountColor = 0] . ' white hostcms-font"><b>' . $oSite->id . '</b></i>
																</div>
																<div class="notification-body">
																	<span class="title">' . Core_Str::cut($oSite->name, 35) . '</span>
																	<span class="description">' .
																	 (!is_null($oSite_Alias)
																		? htmlspecialchars($oSite_Alias->name)
																		: 'undefined' ) . '
																	</span>
																</div><div class="notification-extra"><i class="fa fa-check-circle-o green"></i></div>
															</div>
														</a></li>' . $sListSitesContent;
												}
											}
											echo $sListSitesContent;
											?>
											</ul>
										</div>
									</div>

									<script type="text/javascript">
										var sitesListBox = document.getElementById('sitesListBox');
										sitesListBox.onclick = function(event){
											event.stopPropagation ? event.stopPropagation() : (event.cancelBubble=true);
										};
										$('.scroll-sites').slimscroll({
										 // height: '215px',
										  height: 'auto',
										  color: 'rgba(0,0,0,0.3)',
										  size: '5px'
										});
									</script>
									<!--/Tasks Dropdown-->
								</li>
								<li>
									<a class="dropdown-toggle" data-toggle="dropdown" title="Languages" href="#">
										<i class="icon fa fa-flag"></i>
									</a>

									<div id="languagesListBox" class=" pull-right dropdown-menu dropdown-arrow dropdown-notifications">
										<div class="scroll-languages">
											<ul>
											<?php
											$oAdmin_Language = Core_Entity::factory('Admin_Language');
											$oAdmin_Language->queryBuilder()->where('active', '=', 1);

											$aAdmin_Languages = $oAdmin_Language->findAll();
											$i = 1;

											foreach ($aAdmin_Languages as $oAdmin_Language)
											{
											?>
											<li>
												<a <?php echo Core_Array::get($_SESSION, 'current_lng') != $oAdmin_Language->shortname ? 'href="/admin/index.php?lng_value=' . $oAdmin_Language->shortname . '"' : '';?>>

													<div class="clearfix">
														<div class="notification-icon">
															<img src="<?php echo '/modules/skin/bootstrap/img/flags/' . $oAdmin_Language->shortname . '.png'?>" class="message-avatar" alt="<?php echo $oAdmin_Language->name?>" />
														</div>
														<div class="notification-body">
															<?php echo $oAdmin_Language->name?>
														</div>
														<div class="notification-extra">
															<?php
															if (Core_Array::get($_SESSION, 'current_lng') == $oAdmin_Language->shortname)
															{
																?><i class="fa fa-check-circle-o pull-right green"></i><?php
															}
															?>
														</div>
													</div>
												</a>
											</li>
											<?php
											}
											?>
											</ul>
										</div>
									</div>
									<script type="text/javascript">
										var languagesListBox = document.getElementById('languagesListBox');
										languagesListBox.onclick = function(event){
											event.stopPropagation ? event.stopPropagation() : (event.cancelBubble=true);
										};
										$('.scroll-languages').slimscroll({
										 // height: '215px',
										  height: 'auto',
										  color: 'rgba(0, 0, 0, 0.3)',
										  size: '5px'
										});
									</script>
								</li>

								<li>
									<a class="dropdown-toggle" data-toggle="dropdown">
										<i class="icon fa fa-user"></i>
									</a>
									<!--Login Area Dropdown-->
									<ul class="pull-right dropdown-menu dropdown-arrow dropdown-login-area">
										<!--Avatar Area-->
										<li role="presentation" class="dropdown-header">
											<span><i class="fa fa-<?php echo $oUser->superuser ? 'graduation-cap' : 'user'?>"></i> <?php echo htmlspecialchars($_SESSION['valid_user'])?></span>
										</li>
										<!--Theme Selector Area-->
										<!-- <li class="theme-area">
											<ul class="colorpicker" id="skin-changer">
												<li><a class="colorpick-btn" href="#" style="background-color:#5DB2FF;" rel="/modules/skin/bootstrap/css/skins/blue.min.css"></a></li>
												<li><a class="colorpick-btn" href="#" style="background-color:#2dc3e8;" rel="/modules/skin/bootstrap/css/skins/azure.min.css"></a></li>
												<li><a class="colorpick-btn" href="#" style="background-color:#03B3B2;" rel="/modules/skin/bootstrap/css/skins/teal.min.css"></a></li>
												<li><a class="colorpick-btn" href="#" style="background-color:#53a93f;" rel="/modules/skin/bootstrap/css/skins/green.min.css"></a></li>
												<li><a class="colorpick-btn" href="#" style="background-color:#FF8F32;" rel="/modules/skin/bootstrap/css/skins/orange.min.css"></a></li>
												<li><a class="colorpick-btn" href="#" style="background-color:#cc324b;" rel="/modules/skin/bootstrap/css/skins/pink.min.css"></a></li>
												<li><a class="colorpick-btn" href="#" style="background-color:#AC193D;" rel="/modules/skin/bootstrap/css/skins/darkred.min.css"></a></li>
												<li><a class="colorpick-btn" href="#" style="background-color:#8C0095;" rel="/modules/skin/bootstrap/css/skins/purple.min.css"></a></li>
												<li><a class="colorpick-btn" href="#" style="background-color:#0072C6;" rel="/modules/skin/bootstrap/css/skins/darkblue.min.css"></a></li>
												<li><a class="colorpick-btn" href="#" style="background-color:#585858;" rel="/modules/skin/bootstrap/css/skins/gray.min.css"></a></li>
												<li><a class="colorpick-btn" href="#" style="background-color:#474544;" rel="/modules/skin/bootstrap/css/skins/black.min.css"></a></li>
												<li><a class="colorpick-btn" href="#" style="background-color:#001940;" rel="/modules/skin/bootstrap/css/skins/deepblue.min.css"></a></li>
											</ul>
										</li> -->
										<!--/Theme Selector Area-->
										<li class="dropdown-footer">
											<a href="/admin/logout.php"><?php echo Core::_('Admin.exit')?></a>
										</li>
									</ul>
									<!--/Login Area Dropdown-->
								</li>
								<!-- /Account Area -->
								<!--Note: notice that setting div must start right after account area list.
								no space must be between these elements-->
								<!-- Settings -->
							</ul><div class="setting">
								<a id="btn-setting" title="Setting" href="#">
									<i class="icon glyphicon glyphicon-cog"></i>
								</a>
							</div><div class="setting-container">
								<label>
									<span class="text"><?php echo Core::_('Admin.fixed')?></span>
								</label>
								<label>
									<input type="checkbox" id="checkbox_fixednavbar">
									<span class="text"><?php echo Core::_('Admin.fixed-navbar')?></span>
								</label>
								<label>
									<input type="checkbox" id="checkbox_fixedsidebar">
									<span class="text"><?php echo Core::_('Admin.fixed-sideBar')?></span>
								</label>
								<label>
									<input type="checkbox" id="checkbox_fixedbreadcrumbs">
									<span class="text"><?php echo Core::_('Admin.fixed-breadcrumbs')?></span>
								</label>
								<label>
									<input type="checkbox" id="checkbox_fixedheader">
									<span class="text"><?php echo Core::_('Admin.fixed-header')?></span>
								</label>
							</div>
							<!-- Settings -->
						</div>
					<?php
					}
					?></div>
					<!-- /Account Area and Settings -->
				</div>
			</div>
		</div>
		<!-- /Navbar -->
		<?php
	}

	protected function _pageSidebar()
	{
		?><!-- Page Sidebar -->
		<div class="page-sidebar" id="sidebar">
			<!-- Page Sidebar Header-->
			<div class="sidebar-header-wrapper">
				<input type="text" class="searchinput" disabled="disabled" />
				<i class="searchicon fa fa-search"></i>
				<div class="searchhelper">
				<!-- Search Reports, Charts, Emails or Notifications -->
				</div>
			</div>
			<!-- /Page Sidebar Header -->
			<!-- Sidebar Menu -->
			<ul class="nav sidebar-menu">
				<li id="menu-dashboard">
					<a href="/admin/index.php" onclick="$.adminLoad({path: '/admin/index.php'}); return false">
						<i class="menu-icon glyphicon glyphicon-home"></i>
						<span class="menu-text"><?php echo Core::_('Admin.home')?></span>
					</a>
				</li>
				<?php
				// Список основных меню скина
				$this->_config = Core_Config::instance()->get('skin_bootstrap_config');

				Core_Event::notify(get_class($this) . '.onLoadSkinConfig', $this);

				if (isset($this->_config['adminMenu']))
				{
					$aModules = $this->_getAllowedModules();

					$aModuleList = array();
					foreach ($aModules as $key => $oModule)
					{
						$aModuleList[$oModule->path] = $oModule;
					}
					unset($aModules);

					foreach ($this->_config['adminMenu'] as $key => $aAdminMenu)
					{
						$aAdminMenu += array('ico' => 'fa-file-o',
							'modules' => array()
						);

						$subItems = array();
						foreach($aAdminMenu['modules'] as $sModulePath)
						{
							if (isset($aModuleList[$sModulePath]))
							{
								$subItems[] = $aModuleList[$sModulePath];
								unset($aModuleList[$sModulePath]);
							}
						}

						if (count($subItems))
						{
							?><li>
								<a class="menu-dropdown">
									<i class="menu-icon <?php echo $aAdminMenu['ico']?>"></i>
									<span class="menu-text"> <?php echo nl2br(htmlspecialchars(Core::_("Skin_Bootstrap.admin_menu_{$key}"))) ?> </span>
									<i class="menu-expand"></i>
								</a>

								<ul class="submenu">
								<?php
								foreach ($subItems as $oModule)
								{
									$oCore_Module = Core_Module::factory($oModule->path);

									if ($oCore_Module && is_array($oCore_Module->menu))
									{
										foreach ($oCore_Module->menu as $aMenu)
										{
											$aMenu += array(
												'sorting' => 0,
												'block' => 0,
												'ico' => 'fa-file-o'
											);
											?><li id="menu-<?php echo $oCore_Module->getModuleName()?>">
												<a href="<?php echo $aMenu['href']?>" onclick="$.adminLoad({path: '<?php echo $aMenu['href']?>'}); return false">
													<i class="menu-icon <?php echo $aMenu['ico']?>"></i>
													<span class="menu-text"><?php echo $aMenu['name']?></span>
												</a>
											</li><?php
										}
									}
								}
								?></ul>
							</li><?php
						}
					}

					// Невошедшие в другие группы
					foreach ($aModuleList as $oModule)
					{
						$oCore_Module = Core_Module::factory($oModule->path);

						if ($oCore_Module && is_array($oCore_Module->menu))
						{
							foreach ($oCore_Module->menu as $aMenu)
							{
								if (isset($aMenu['name']))
								{
									$aMenu += array(
										'sorting' => 0,
										'block' => 0,
										'ico' => 'fa-file-o'
									);
									?><li>
										<a href="<?php echo $aMenu['href']?>" onclick="$.adminLoad({path: '<?php echo $aMenu['href']?>'}); return false" class="menu-icon">
											<i class="menu-icon fa <?php echo $aMenu['ico']?>"></i>
											<span class="menu-text"><?php echo $aMenu['name']?></span>
										</a>
									</li>
									<?php
								}
							}
						}
					}
				}

			?></ul>
		</div>
		<!-- /Page Sidebar -->
		<?php
	}

	public function loadingContainer()
	{
		?><!-- Loading Container -->
		<div class="loading-container">
			<div class="loader"></div>
		</div>
		<!--  /Loading Container --><?php

		return $this;
	}

	/**
	 * Show header
	 */
	public function header()
	{
		//$timestamp = $this->_getTimestamp();

		?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<title><?php echo $this->_title?></title>

<meta name="description" content="blank page" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="apple-touch-icon" href="/modules/skin/bootstrap/ico/icon-iphone-retina.png" />
<link rel="shortcut icon" type="image/x-icon" href="/modules/skin/bootstrap/ico/favicon.ico" />
<link rel="icon" type="image/png" href="/modules/skin/bootstrap/ico/favicon.png" />

<?php $this->showHead()?>
</head>
<body class="body-<?php echo htmlspecialchars($this->_mode)?> hostcms-bootstrap1">
		<?php
		if ($this->_mode != 'install')
		{
			if (Core_Auth::logged())
			{
				$this->loadingContainer();

				if (!in_array($this->_mode, array('blank', 'authorization')))
				{
					$this->_navBar();

					?><!-- Main Container -->
					<div class="main-container container-fluid">
						<!-- Page Container -->
						<div class="page-container">

							<?php $this->_pageSidebar()?>

							<!-- Page Content -->
							<div class="page-content">
					<?php
				}
			}
		}
		// Install mode
		else
		{
			$this->loadingContainer();
		}

		return $this;
	}

	/**
	 * Show authorization form
	 */
	public function authorization()
	{
		$this->_mode = 'authorization';

		?><div class="login-container animated fadeInDown">
		<div class="loginbox-largelogo">
			<img src="/modules/skin/bootstrap/img/large-logo.png">
		</div>

		<?php
		$message = Core_Skin::instance()->answer()->message;
		if ($message)
		{
			Core::factory('Core_Html_Entity_Div')
				->id('authorizationError')
				->value($message)
				->execute();

			// Reset message
			Core_Skin::instance()->answer()->message('');
		}
		?>

		<div class="loginbox">
			<form class="form-horizontal" action="/admin/index.php" method="post">
				<div class="loginbox-textbox">
					<span class="input-icon">
						<input type="text" name="login" class="form-control" placeholder="<?php echo Core::_('Admin.authorization_form_login')?>" />
						<i class="fa fa-user"></i>
					</span>
				</div>
				<div class="loginbox-textbox">
					<span class="input-icon">
						<input type="password" name="password" class="form-control" placeholder="<?php echo Core::_('Admin.authorization_form_password')?>" />
						<i class="fa fa-lock"></i>
					</span>
				</div>
				<div class="loginbox-forgot">
					<label>
						<input type="checkbox" checked="checked" name="ip" /><span class="text"><?php echo Core::_('Admin.authorization_form_ip')?></span>
					</label>
				</div>
				<div class="loginbox-submit">
					<input type="submit" name="submit" class="btn btn-danger btn-block" value="<?php echo Core::_('Admin.authorization_form_button')?>">
				</div>
			</form>
		</div>
		</div>

		<div class="widget hostcms-notice transparent">
		<div class="widget-body">
			<?php echo Core::_('Admin.authorization_notice')?>
		</div>
		<div class="widget-body">
			<?php echo Core::_('Admin.authorization_notice2')?>
		</div>
		</div>

		<script type="text/javascript">$("#authorization input[name='login']").focus();</script>
		<?php
		}

		/**
		 * Show footer
		 */
		public function footer()
		{
			if ($this->_mode != 'install')
			{
				if (Core_Auth::logged())
				{
					if ($this->_mode != 'blank')
					{
						?>		</div>
								<!-- /Page Content -->
							</div>
							<!-- /Page Container -->
							<!-- Main Container -->
						</div><?php
					}
				}
				else
				{
				?><footer>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<p class="copy pull-left copyright">Copyright © 2005–2015 <?php echo Core::_('Admin.company')?></p>
			<p class="copy text-right contacts">
				<?php echo Core::_('Admin.website')?> <a href="http://<?php echo Core::_('Admin.company-website')?>" target="_blank"><?php echo Core::_('Admin.company-website')?></a>
				<br/>
				<?php echo Core::_('Admin.support_email')?> <a href="mailto:<?php echo Core::_('Admin.company-support')?>"><?php echo Core::_('Admin.company-support')?></a>
			</p>
		</div>
	</div>
</div>
</footer><?php
				}
			}
			?><script src="/modules/skin/bootstrap/js/hostcms.js"></script>
</body>
</html><?php
	}

	/**
	 * Show back-end index page
	 * @return self
	 */
	public function index()
	{
		$this->_mode = 'index';

		$oUser = Core_Entity::factory('User')->getCurrent();

		if (is_null($oUser))
		{
			throw new Core_Exception('Undefined user.', array(), 0, FALSE, 0, FALSE);
		}

		$bAjax = Core_Array::getRequest('_', FALSE);

		// Widget settings
		if (!is_null(Core_Array::getGet('userSettings')))
		{
			if (!is_null(Core_Array::getGet('moduleId')))
			{
				$moduleId = intval(Core_Array::getGet('moduleId', 0));
				$type = intval(Core_Array::getGet('type', 0));
				$entity_id = intval(Core_Array::getGet('entity_id', 0));

				$oUser_Setting = $oUser->User_Settings->getByModuleIdAndTypeAndEntityId($moduleId, $type, $entity_id);
				is_null($oUser_Setting) && $oUser_Setting = Core_Entity::factory('User_Setting');

				$oUser_Setting->module_id = $moduleId;
				$oUser_Setting->type = $type;
				$oUser_Setting->entity_id = $entity_id;
				$oUser_Setting->position_x = intval(Core_Array::getGet('position_x'));
				$oUser_Setting->position_y = intval(Core_Array::getGet('position_y'));
				$oUser_Setting->width = intval(Core_Array::getGet('width'));
				$oUser_Setting->height = intval(Core_Array::getGet('height'));
				$oUser_Setting->active = intval(Core_Array::getGet('active', 1));

				$oUser->add($oUser_Setting);
			}

			// Shortcuts
			$aSh = Core_Array::getGet('sh');
			if ($aSh)
			{
				$type = 99;
				foreach ($aSh as $position => $moduleId)
				{
					$oUser_Setting = $oUser->User_Settings->getByModuleIdAndTypeAndEntityId($moduleId, 99, 0);

					is_null($oUser_Setting) && $oUser_Setting = Core_Entity::factory('User_Setting');

					$oUser_Setting->module_id = $moduleId;
					$oUser_Setting->type = $type;
					$oUser_Setting->position_x = Core_Array::getGet('blockId');
					$oUser_Setting->position_y = $position;

					$oUser->add($oUser_Setting);
				}
			}

			$oAdmin_Answer = Core_Skin::instance()->answer();
			$oAdmin_Answer
				->ajax($bAjax)
				->execute();
			exit();
		}

		// Widget ajax loading
		if (!is_null(Core_Array::getGet('ajaxWidgetLoad')))
		{
			ob_start();
			if (!is_null(Core_Array::getGet('moduleId')))
			{
				$moduleId = intval(Core_Array::getGet('moduleId'));
				$type = intval(Core_Array::getGet('type', 0));

				$oUser_Setting = $oUser->User_Settings->getByModuleIdAndTypeAndEntityId($moduleId, $type, 0);
				!is_null($oUser_Setting) && $oUser_Setting->active(1)->save();

				$modulePath = $moduleId == 0
					? 'core'
					: Core_Entity::factory('Module', $moduleId)->path;

				Core_Session::close();

				$Core_Module = $this->getSkinModule($modulePath);

				if (!is_null($Core_Module))
				{
					$Core_Module->adminPage($type, $bAjax && is_null(Core_Array::getGet('widgetAjax')));
				}
				else
				{
					throw new Core_Exception('SkinModuleName does not found.');
				}
			}
			else
			{
				throw new Core_Exception('moduleId does not exist.');
			}

			$oAdmin_Answer = Core_Skin::instance()->answer();
			$oAdmin_Answer
				->content(ob_get_clean())
				->ajax($bAjax)
				->execute();
			exit();
		}

		// Ajax note creating
		if (!is_null(Core_Array::getGet('ajaxCreateNote')))
		{
			$oUser_Note = Core_Entity::factory('User_Note')->save();

			$oAdmin_Answer = Core_Skin::instance()->answer();
			$oAdmin_Answer
				->content($oUser_Note->id)
				->ajax($bAjax)
				->execute();
			exit();
		}

		// Ajax note changing
		if (!is_null(Core_Array::getGet('ajaxNote')))
		{
			$oUser_Note = Core_Entity::factory('User_Note')->find(intval(Core_Array::getGet('entity_id')));

			if (!is_null($oUser_Note->id) && $oUser_Note->user_id == $oUser->id)
			{
				switch (Core_Array::getGet('action'))
				{
					case 'delete':
						$oUser_Note->markDeleted();
					break;
					case 'save':
						$oUser_Note->value(Core_Array::getPost('value', ''))->save();
					break;
				}
			}

			$oAdmin_Answer = Core_Skin::instance()->answer();
			$oAdmin_Answer
				->ajax($bAjax)
				->execute();
			exit();
		}

		$aModules = $this->_getAllowedModules();

		$oAdmin_View = Admin_View::create();
		$oAdmin_View->showFormBreadcrumbs();
		?>

		<div class="page-header position-relative">
			<div class="header-title">
				<h1><?php echo Core::_('Admin.dashboard')?></h1>
			</div>
			<!--Header Buttons-->
			<div class="header-buttons">
				<a href="#" class="sidebar-toggler">
					<i class="fa fa-arrows-h"></i>
				</a>
				<a href="" id="refresh-toggler" class="refresh">
					<i class="glyphicon glyphicon-refresh"></i>
				</a>
				<a href="#" id="fullscreen-toggler" class="fullscreen">
					<i class="glyphicon glyphicon-fullscreen"></i>
				</a>
			</div>
			<!--Header Buttons End-->
		</div>
		<div class="page-body">

			<div class="row">
				<?php
				// Other modules
				$oSite = Core_Entity::factory('Site', CURRENT_SITE);
				foreach ($aModules as $oModule)
				{
					$Core_Module = $this->getSkinModule($oModule->path);

					is_null($Core_Module) && $Core_Module = $oModule->Core_Module;

					if ($oModule->active
						&& !is_null($Core_Module)
						&& method_exists($Core_Module, 'widget')
						&& $oUser->checkModuleAccess(array($oModule->path), $oSite))
					{
						// 78 - informer(widget) settings
						$oUser_Setting = $oUser->User_Settings->getByModuleIdAndTypeAndEntityId($oModule->id, 78, 0);

						//$iStartTime = Core::getmicrotime();

						(is_null($oUser_Setting) || $oUser_Setting->active)
							&& $Core_Module->widget();

						//echo '<!-- Debug time "', $oModule->path, '": ', sprintf('%.3f', Core::getmicrotime() - $iStartTime), ' -->';
					}
				}
				?>
			</div>

			<div class="row">
				<?php
				// Other modules
				$oSite = Core_Entity::factory('Site', CURRENT_SITE);
				foreach ($aModules as $oModule)
				{
					$Core_Module = $this->getSkinModule($oModule->path);

					is_null($Core_Module) && $Core_Module = $oModule->Core_Module;

					if ($oModule->active
						&& !is_null($Core_Module)
						&& method_exists($Core_Module, 'adminPage')
						&& $oUser->checkModuleAccess(array($oModule->path), $oSite))
					{

						// 77 - widget settings
						$oUser_Setting = $oUser->User_Settings->getByModuleIdAndTypeAndEntityId($oModule->id, 77, 0);

						// Временно отключена проверка
						//if (is_null($oUser_Setting) || $oUser_Setting->active)
						if (TRUE)
						{
							$aTypes = $Core_Module->getAdminPages();
							foreach ($aTypes as $type => $title)
							{
								//$iStartTime = Core::getmicrotime();

								$Core_Module->adminPage($type);

								//echo '<!-- Debug time "', $oModule->path, '": ', sprintf('%.3f', Core::getmicrotime() - $iStartTime), ' -->';
							}
						}
					}
				}

				// Core
				$Core_Module = $this->getSkinModule('core');

				if (!is_null($Core_Module))
				{
					//$Core_Module = new $sSkinModuleName();
					$aTypes = $Core_Module->getAdminPages();

					foreach ($aTypes as $type => $title)
					{
						$oUser_Setting = $oUser->User_Settings->getByModuleIdAndTypeAndEntityId(0, $type, 0);

						// Временно отключена проверка
						//if (is_null($oUser_Setting) || $oUser_Setting->active)
						if (TRUE)
						{
							$Core_Module->adminPage($type);
						}
					}
				}
				?>
			</div>
		</div><?php

		return $this;
	}

	/**
	 * Get message.
	 *
	 * <code>
	 * echo Core_Message::get(Core::_('constant.name'));
	 * echo Core_Message::get(Core::_('constant.message', 'value1', 'value2'));
	 * </code>
	 * @param $message Message text
	 * @param $type Message type
	 * @see Core_Message::show()
	 * @return string
	 */
	public function getMessage($message, $type = 'message')
	{
		switch ($type)
		{
			case 'error':
				$class = 'alert alert-danger fade in';
			break;
			default:
				$class = 'alert alert-success fade in';
		}
		$return = '<div class="' . $class . '">
		<button type="button" class="close" data-dismiss="alert">&times;</button>' . $message . '</div>';
		return $return;
	}

	/**
	 * Change language
	 */
	public function changeLanguage()
	{
		?><form name="authorization" action="./index.php" method="post">
			<div class="row">
			<?php
			$aInstallConfig = Core_Config::instance()->get('install_config');
			$aLng = Core_Array::get($aInstallConfig, 'lng', array());

			Admin_Form_Entity::factory('Select')
				->name('lng_value')
				->caption(Core::_('Install.changeLanguage'))
				->options($aLng)
				->value(isset($_SESSION['LNG_INSTALL']) ? $_SESSION['LNG_INSTALL'] : DEFAULT_LNG)
				->divAttr(array('class' => 'form-group col-lg-6 col-md-6 col-sm-12 col-xs-12'))
				->execute();
			?>
			</div>

			<div class="row">
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-align-right">
					 <button name="step_0" type="submit" class="btn btn-info">
						<?php echo Core::_('Install.next')?> <i class="fa fa-arrow-right"></i>
					</button>
				</div>
			</div>
		</form>
		<?php
	}

}