(function($){
	$.extend({
		widgetLoad: function(settings)
		{
			// add ajax '_'
			var data = $.getData({});

			settings = $.extend({
				'button': null
			}, settings);

			settings.button && settings.button.addClass('fa-spin');

			$.ajax({
				context: settings.context,
				url: settings.path,
				data: data,
				dataType: 'json',
				type: 'POST',
				success: function(data){
					this.html(data.form_html);
				}
			});
		},
		ajaxCallbackSkin: function(data, status, jqXHR)
		{
			if (typeof data.module != 'undefined')
			{
				// Выделить текущий пункт левого бокового меню
				$.currentMenu(data.module);
			}
		},
		currentMenu: function(moduleName)
		{
			$('#sidebar li').removeClass('active').removeClass('open');

			$('#menu-'+moduleName).addClass('active')
				.parents('li').addClass('active').addClass('open');

			$('#sidebar li[class != open] ul.submenu').hide();
		},
		afterContentLoad: function(jWindow, data)
		{
			data = typeof data !== 'undefined' ? data : {};

			if (typeof data.title != 'undefined' && data.title != '' && jWindow.attr('id') != 'id_content')
			{
				var jSpanTitle = jWindow.find('span.ui-dialog-title');
				if (jSpanTitle.length)
				{
					jSpanTitle.empty().html(data.error);
				}
			}
		},
		windowSettings: function(settings)
		{
			return jQuery.extend({
				Closable: true
			}, settings);
		},
		openWindow: function(settings)
		{
			settings = jQuery.windowSettings(
				jQuery.requestSettings(settings)
				//settings
			);

			settings = $.extend({
				open: function( event, ui ) {
					var uiDialog = $(this).parent('.ui-dialog');
					uiDialog.width(uiDialog.width()).height(uiDialog.height());
				},
				close: function( event, ui ) {
					$(this).dialog('destroy').remove();
				}
			}, settings);

			var cmsrequest = settings.path;
			if (settings.additionalParams != ' ' && settings.additionalParams != '')
			{
				cmsrequest += '?' + settings.additionalParams;
			}

			var windowCounter = $('body').data('windowCounter');
			if (windowCounter == undefined) { windowCounter = 0 }
			$('body').data('windowCounter', windowCounter + 1);

			var jDivWin = $('<div>')
				.addClass("hostcmsWindow")
				.attr("id", "Window" + windowCounter)
				.appendTo($(document.body))
				.dialog(settings)/*
				.dialog('open')*/;

			var data = jQuery.getData(settings);
			// Change window id
			data['hostcms[window]'] = jDivWin.attr('id');

			jQuery.ajax({
				context: jDivWin,
				url: cmsrequest,
				data: data,
				dataType: 'json',
				type: 'POST',
				success: jQuery.ajaxCallback
			});

			return jDivWin;
		},
		openWindowAddTaskbar: function(settings)
		{
			return jQuery.adminLoad(settings);
		},
		ajaxCallbackModal: function(data, status, jqXHR) {
			$.loadingScreen('hide');
			if (data == null || data.form_html == null)
			{
				alert('AJAX response error.');
				return;
			}

			var jObject = jQuery(this),
				jBody = jObject.find(".modal-body")

			if (data.form_html != '')
			{
				jQuery.beforeContentLoad(jBody, data);
				jQuery.insertContent(jBody, data.form_html);
				jQuery.afterContentLoad(jBody, data);
			}

			var jMessage = jBody.find("#id_message");

			if (jMessage.length === 0)
			{
				jMessage = jQuery("<div>").attr('id', 'id_message');
				jBody.prepend(jMessage);
			}

			jMessage.empty().html(data.error);

			if (typeof data.title != 'undefined' && data.title != '')
			{
				jObject.find(".modal-title").html(data.title);
			}
		},
		widgetRequest: function(settings){
			$.loadingScreen('show');

			// add ajax '_'
			var data = jQuery.getData({});

			jQuery.ajax({
				context: settings.context,
				url: settings.path,
				data: data,
				dataType: 'json',
				type: 'POST',
				success: function() {
					//jQuery(this).HostCMSWindow('reload');
					// add ajax '_'
					var data = jQuery.getData({});
					jQuery.ajax({
						context: this,
						url: this.data('hostcmsurl'),
						data: data,
						dataType: 'json',
						type: 'POST',
						//success: jQuery.ajaxCallback
						success: [jQuery.ajaxCallback, function(returnedData)
						{
							if (returnedData == null || returnedData.form_html == null)
							{
								return;
							}

							// Clear widget place
							if (returnedData.form_html == '')
							{
								$(this).empty();
							}
						}]
					});
				}
			});
		},
		cloneProperty: function(windowId, index)
		{
			var jProperies = jQuery('#' + windowId + ' #property_' + index),

			//Объект окна настроек большого изображения
			oSpanFileSettings =  jProperies.find("span[id ^= 'file_large_settings_']");

			// Закрываем окно настроек большого изображения
			if (oSpanFileSettings.length && oSpanFileSettings.children('i').hasClass('fa-times'))
			{
				oSpanFileSettings.click();
			}

			//Объект окна настроек малого изображения
			oSpanFileSettings =  jProperies.find("span[id ^= 'file_small_settings_']");
			// Закрываем окно настроек малого изображения
			if (oSpanFileSettings.length && oSpanFileSettings.children('i').hasClass('fa-times'))
			{
				oSpanFileSettings.click();
			}

			var jNewObject = jProperies.eq(0).clone(),
			iRand = Math.floor(Math.random() * 999999);

			jNewObject.insertAfter(
				jQuery('#' + windowId).find('div[id="property_' + index + '"],div[id^="property_' + index + '_"]').eq(-1)
			);

			jNewObject.attr('id', 'property_' + index + '_' + iRand);

			// Change item_div ID
			jNewObject.find("div[id^='file_']").each(function(index, object){
				jQuery(object).prop('id', jQuery(object).prop('id') + '_' + iRand);

				// Удаляем скопированные элементы popover'а
				jQuery(object).find("div[id ^= 'popover']").remove();
			});

			//jNewObject.find("span[id^='file_large_settings_']").data('container', jNewObject.find("span[id^='file_large_settings_']").data('container') + '_' + iRand);
			//jNewObject.find("span[id^='file_small_settings_']").data('container', jNewObject.find("span[id^='file_small_settings_']").data('container') + '_' + iRand);

			/*
			var oPropertyField = jProperies.eq(0).find("input[id ^= 'property_" + index + "_'][type='file']");

				//originalHtmlLargeFileSettings = jNewObject.find("div[id *='_watermark_property_']").html(),
				//originalHtmlSmallFileSettings = jNewObject.find("div[id *='_watermark_small_property_']").html();

			// Копирование доп. свойства у редактируемого элемента системы

			if (oPropertyField.length)
			{
				var aInputId = oPropertyField.attr('id').split('property_'),
					suffix = aInputId[1];

					jNewObject.find("div[id *='_watermark_property_']")
						.attr('id', jNewObject.find("div[id *='_watermark_property_']").attr('id').replace('_watermark_property_' + suffix, '_watermark_property_' + index + '_'+ iRand))
						.html(originalHtmlLargeFileSettings.replace(new RegExp(suffix,'g'), index + '[]'));

					//.replace('_watermark_property_' + suffix, '_watermark_property_' + index + '_'+ iRand).replace(new RegExp(suffix,'g'), index + '[]');

				aInputId = jProperies.eq(0).find("input[id ^= 'small_property_" + index + "_']").attr('id').split('small_property_');
				suffix = aInputId[1];


				jNewObject.find("div[id *='_watermark_small_property_']")
						.attr('id', jNewObject.find("div[id *='_watermark_small_property_']").attr('id').replace('_watermark_small_property_' + suffix, '_watermark_small_property_' + index + '_'+ iRand))
						.html(originalHtmlSmallFileSettings.replace(new RegExp(suffix,'g'), index + '[]'));
			}
			else
			{
				jNewObject.find("div[id *='_watermark_property_']").html(originalHtmlLargeFileSettings);
				jNewObject.find("div[id *='_watermark_small_property_']").html(originalHtmlSmallFileSettings);
			}*/

			jNewObject.find("div[id *='_watermark_property_']").html(jNewObject.find("div[id *='_watermark_property_']").html());
			jNewObject.find("div[id *='_watermark_small_property_']").html(jNewObject.find("div[id *='_watermark_small_property_']").html());

			// Для скопированного элемента создаем временные popover'ы для настроек большого и малого изображений
			/*
			jNewObject.find('[id ^= \'file_\'][id *= \'_settings_\']')
				.popover({
					placement: 'left',
					html: true,
					trigger: 'manual',
					temporary: 1
				})
				.popover('show')
				.each(function(){
					$(this).data('bs.popover').$tip.hide()
				});
			*/

			// Удаляем элементы просмотра и удаления загруженнного изображения
			jNewObject.find("[id ^= 'preview_large_property_'], [id ^= 'delete_large_property_'], [id ^= 'preview_small_property_'], [id ^= 'delete_small_property_']").remove();
			// Удаляем скрипт просмотра загуженного изображения
			jNewObject.find("input[id ^= 'property_" + index + "_'][type='file'] ~ script").remove();


			//

			jNewObject.find("input[id^='field_id'],select,textarea").attr('name', 'property_' + index + '[]');
			jNewObject.find("div[id^='file_small'] input[id^='small_field_id']").attr('name', 'small_property_' + index + '[]').val('');
			jNewObject.find("input[id^='field_id'][type!=checkbox],input[id^='property_'][type!=checkbox],select,textarea").val('');

			jNewObject.find("input[id^='create_small_image_from_large_small_property']").attr('checked', true);

			// Change input name
			jNewObject.find(':regex(name, ^\\S+_\\d+_\\d+$)').each(function(index, object){
				var reg = /^(\S+)_(\d+)_(\d+)$/;
				var arr = reg.exec(object.name);
				jQuery(object).prop('name', arr[1] + '_' + arr[2] + '[]');
			});

			jNewObject.find("div.img_control div,div.img_control div").remove();
			jNewObject.find("input[type='text']#description_large").attr('name', 'description_property_' + index + '[]');
			jNewObject.find("input[type='text']#description_small").attr('name', 'description_small_property_' + index + '[]');

			//jNewObject.find("img#delete").attr('onclick', "jQuery.deleteNewProperty(this)");

			var oDateTimePicker = jProperies.find('div[id ^= "div_property_' + index + '_"], div[id ^= "div_field_id_"]').data('DateTimePicker');

			if(oDateTimePicker)
			{
				jNewObject.find('script').remove();
				jNewObject.find('div[id ^= "div_property_' + index + '_"], div[id ^= "div_field_id_"]').datetimepicker({locale: 'ru', format: oDateTimePicker.format()});
			}
			//jNewObject.find('input.hasDatepicker').attr('id', 'date_id_' + iRand).datepicker();

			// После внесения в DOM
			/*
 			jNewObject.find("a[onclick*='watermark_property_'],a[onclick*='watermark_small_property_']").each(function(index, object){
				var jObject = $(object), tmp = $(object).attr('onclick');
				jObject.attr('onclick', tmp.replace('_property_', '_property_' + iRand + '_'));
			});


			jNewObject.find("div[id*='watermark_property_'],div[id*='watermark_small_property_']").each(function(index, object){
				var jObject = $(object), tmp = $(object).attr('id');
				jObject.attr('id', tmp.replace('_property_', '_property_' + iRand + '_'));

				jObject.HostCMSWindow({ autoOpen: false, destroyOnClose: false, AppendTo: '#' + jNewObject.prop('id'), width: 360, height: 230, addContentPadding: true, modal: false, Maximize: false, Minimize: false });
			});

			jNewObject.find("div[aria-labelledby*='watermark_property_'],div[aria-labelledby*='watermark_small_property_']").each(function(index, object){
				var jObject = $(object), tmp = $(object).attr('aria-labelledby');
				jObject.attr('aria-labelledby', tmp.replace('_property_', '_property_' + iRand + '_'));
			});
			*/
		}
	});

	jQuery.fn.extend({
		refreshEditor: function()
		{
			return this.each(function(){
				//this.disabled = !this.disabled;
				jQuery(this).find(".CodeMirror").each(function(){
					this.CodeMirror.refresh();
				});
			});
		},
		HostCMSWindow: function(settings)
		{
			var object = $(this);

			settings = jQuery.extend({
				title: ''
			}, settings);

			var dialog = bootbox.dialog({
				message: object.html(),
				title: settings.title
			}),
			modalBody = dialog.find('.modal-body');

			// Calculate window ID
			dialog.attr('id', object.attr('id'));

			if (typeof settings.width != 'undefined')
			{
				dialog.find('.modal-dialog').width(settings.width);
			}

			if (typeof settings.height != 'undefined')
			{
				modalBody.height(settings.height);
			}

			object.remove();
		}
	});

})(jQuery);


$(function(){
	$('.page-content').on('click', '[id ^= \'file_\'][id *= \'_settings_\']', function() {
		$(this)
		.popover({
			placement: 'left',
			content:  $(this).nextAll('div[id *= "_watermark_"]').show(),
			container: $(this).parents('div[id ^= "file_large_"], div[id ^= "file_small_"]'),
			html: true,
			trigger: 'manual'
		})
		.popover('toggle');
	});

	$('.page-content').on('hide.bs.popover', '[id ^= \'file_\'][id *= \'_settings_\']', function () {
		var popoverContent = $(this).data('bs.popover').$tip.find('.popover-content div[id *= "_watermark_"], .popover-content [id *= "_watermark_small_"]');

		if (popoverContent.length)
		{
			$(this).after(popoverContent.hide());
		}
	});

	$('.page-content').on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
		//console.log(e.target); // newly activated tab
		$(e.target.getAttribute('href')).refreshEditor();
		//console.log(e.target.getAttribute('href'));
	});

	$('.page-container')
/*
	.on('mouseenter', '.page-sidebar.menu-compact .sidebar-menu > li', function() {
		$(this).addClass('compact_submenu');
		//console.log('Показ наведением мыши', $(this).attr('class'));
	})
	.on('mouseleave', '.page-sidebar.menu-compact .sidebar-menu > li', function(e) {
		//console.log('Скрытие уводом мыши',  $(this).attr('class'));
		$(this).removeClass('compact_submenu');
	})
	.on('click', '.page-sidebar.menu-compact .sidebar-menu .submenu > li', function(e) {
		$(this).parents('li.compact_submenu').removeClass('compact_submenu');
		//console.log('Скрытие нажатием мыши', $(this).parents('li'));
	})
	*/
	/*
	.on('touchend', '.page-sidebar.menu-compact .sidebar-menu > li', function(e) {
		$(this).addClass('compact_submenu');
		console.log('Показ тачем', $(this).attr('class'));
		//e.preventDefault();
		//return false;
	})	*/
	.on('touchend', '.page-sidebar.menu-compact .sidebar-menu .submenu > li', function(e) {
		//$(this).parents('li.compact_submenu').removeClass('compact_submenu');
		//console.log('!!!!!Скрытие тачем', $(this).parents('li'));
		$(this).find('a').click();
	});

});


var methods = {
	show: function() {
		$('body').css('cursor', 'wait');
		$('.loading-container').removeClass('loading-inactive');
	},
	hide: function() {
		$('body').css('cursor', 'auto');
		setTimeout(function () {
			$('.loading-container').addClass('loading-inactive');
		}, 0);
	}
};
