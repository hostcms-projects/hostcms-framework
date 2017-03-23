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
		// Создание заметки
		createNote: function(settings)
		{
			var jObj = jQuery(object), text = jObj.text();

			// add ajax '_'
			var data = jQuery.getData({});

			jQuery.ajax({
				context: jObj,
				url: '/admin/index.php?ajaxCreateNote',
				data: data,
				dataType: 'json',
				type: 'POST',
				success: function(data) {
					$.changeNote(data.form_html);
					$("#user_notes").prepend('<div id=note_' + data.form_html + ' class="col-lg-3 col-sm-6 col-xs-12"><div class="databox databox-graded"><div class="databox padding-top-20 bg-whitesmoke"><div class="databox-stat orange radius-bordered margin-right-10"><a onclick="$.destroyNote(' + data.form_html + ')"><i class="stat-icon fa fa-remove" title="Удалить заметку"></i></a></div><div class="databox-text darkgray padding-left-10 note"><textarea></textarea></div></div></div></div>');
				}
			});
		},
		// Изменение заметки
		changeNote: function(noteId)
		{
			object = $("#note_" + noteId);

			object.on('change', function(){
				var object = jQuery(this), timer = object.data('timer');

				if (timer){
					clearTimeout(timer);
				}

				jQuery(this).data('timer', setTimeout(function() {
						textarea = object.find('textarea').addClass('ajax');

						// add ajax '_'
						var data = jQuery.getData({});
						data['value'] = textarea.val();

						jQuery.ajax({
							context: textarea,
							url: '/admin/index.php?' + 'ajaxNote&action=save'
								+ '&entity_id=' + noteId,
							type: 'POST',
							data: data,
							dataType: 'json',
							success: function(){
								this.removeClass('ajax');
							}
						});
					}, 1000)
				);
			});
		},
		// Удаление заметки
		destroyNote: function(noteId)
		{
			jQuery.ajax({
				url: '/admin/index.php?' + 'ajaxNote&action=delete'
					+ '&entity_id=' + noteId,
				type: 'get',
				dataType: 'json',
				success: function(){}
			});

			$("#note_" + noteId).remove();
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

			jNewObject.find("div[id *='_watermark_property_']").html(jNewObject.find("div[id *='_watermark_property_']").html());
			jNewObject.find("div[id *='_watermark_small_property_']").html(jNewObject.find("div[id *='_watermark_small_property_']").html());

			// Удаляем элементы просмотра и удаления загруженнного изображения
			jNewObject.find("[id ^= 'preview_large_property_'], [id ^= 'delete_large_property_'], [id ^= 'preview_small_property_'], [id ^= 'delete_small_property_']").remove();
			// Удаляем скрипт просмотра загуженного изображения
			jNewObject.find("input[id ^= 'property_" + index + "_'][type='file'] ~ script").remove();

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

			var oDateTimePicker = jProperies.find('div[id ^= "div_property_' + index + '_"], div[id ^= "div_field_id_"]').data('DateTimePicker');

			if(oDateTimePicker)
			{
				jNewObject.find('script').remove();
				jNewObject.find('div[id ^= "div_property_' + index + '_"], div[id ^= "div_field_id_"]').datetimepicker({locale: 'ru', format: oDateTimePicker.format()});
			}
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
		$(this).find("i.fa").toggleClass("fa-times fa-cog");
	})
	.on('show.bs.popover', '[id ^= \'file_\'][id *= \'_settings_\']', function () {
		$(this).find("i.fa").toggleClass("fa-times fa-cog");
	});

	$('.page-content').on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
		$(e.target.getAttribute('href')).refreshEditor();
	});

	$('.page-container')
	.on('touchend', '.page-sidebar.menu-compact .sidebar-menu .submenu > li', function(e) {
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