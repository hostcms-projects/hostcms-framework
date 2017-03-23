//(function($){
	// Функции для коллекции элементов
	hQuery.fn.extend({
		hostcmsEditable: function(settings){
			settings = hQuery.extend({
				save: function(item, settings){
					var data = {
						'id': item.attr('hostcms:id'),
						'entity': item.attr('hostcms:entity'),
						'field': item.attr('hostcms:field'),
						'value': item.html()
					};
					data['_'] = Math.round(new Date().getTime());

					hQuery.ajax({
						// ajax loader
						context: hQuery('<img>').addClass('img_line').prop('src', '/modules/skin/default/frontend/images/ajax-loader.gif').appendTo(item),
						url: settings.path,
						type: 'POST',
						data: data,
						dataType: 'json',
						success: function(){this.remove();}
					});
				},
				blur: function(jEditInPlace) {
					var item = jEditInPlace.prev();
					item.html(jEditInPlace.val()).css('display', '');
					jEditInPlace.remove();
					settings.save(item, settings);
				}
			}, settings);

			return this.each(function(index, object){
				hQuery(object).on('click', function(){
					var obj = hQuery(this), href = obj.attr('href');
					if (href != undefined && !obj.data('timer')) {
					   obj.data('timer', setTimeout(function(){window.location = href;}, 500));
					}
					return false;
				}).on('dblclick', function(){
					var item = hQuery(this), type = item.attr('hostcms:type'), jEditInPlace;

					clearTimeout(item.data('timer'));
					item.data('timer', null);

					switch(type)
					{
						case 'textarea':
						case 'wysiwyg':
							jEditInPlace = hQuery('<textarea>');
						break;
						case 'input':
						default:
							jEditInPlace = hQuery('<input>').prop('type', 'text');
					}

					if (type != 'wysiwyg')
					{
						jEditInPlace.on('blur', function(){settings.blur(jEditInPlace)});
					}

					jEditInPlace.on('keydown', function(e){
						if (e.keyCode == 13) {
							e.preventDefault();
							this.blur();
						}
						if (e.keyCode == 27) { // ESC
							e.preventDefault();
							var input = hQuery(this), item = input.prev();
							item.css('display', '');
							input.remove();
						}
					})/*.width('90%')*/.prop('name', item.parent().prop('id'))
					.css(hQuery(this).getStyleObject())
					.insertAfter(item).focus().val(item.html());

					if (type == 'wysiwyg')
					{
						setTimeout(function(){
							jEditInPlace.tinymce({
								mode: "exact",
								theme: "simple",
								setup : function(ed) {
									ed.onInit.add(function(ed, evt) {
										var dom = ed.dom, doc = ed.getDoc();

										//tinymce.dom.Event.add(doc, 'blur', function(e) {
										tinymce.dom.Event.add(tinymce.isGecko ? ed.getDoc() : ed.getWin(), 'blur', function(e) {
											settings.blur(jEditInPlace)
										});
									});
								},
								language: "ru", docs_language: "ru", script_url: "/admin/wysiwyg/tiny_mce.js"});
						}, 300);
					}

					item.css('display', 'none');
				}).addClass('hostcmsEditable');
			});
		},
		// http://upshots.org/javascript/jquery-copy-style-copycss
		getStyleObject: function() {
			var dom = this.get(0);
			var style;
			var returns = {};
			if (window.getComputedStyle){
				var camelize = function(a,b){
					return b.toUpperCase();
				};
				style = window.getComputedStyle(dom, null);
				for(var i = 0, l = style.length; i < l; i++){
					var prop = style[i];
					var camel = prop.replace(/\-([a-z])/g, camelize);
					var val = style.getPropertyValue(prop);
					returns[camel] = val;
				};
				return returns;
			};
			if (style = dom.currentStyle){
				for(var prop in style){
					returns[prop] = style[prop];
				};
				return returns;
			};
			if (style = dom.style){
				for(var prop in style){
					if(typeof style[prop] != 'function'){
						returns[prop] = style[prop];
					};
				};
				return returns;
			};
			return returns;
		}
	});

	hQuery.extend({
		createWindow: function(settings) {
			settings = hQuery.extend({
				open: function( event, ui ) {
					var uiDialog = hQuery(this).parent('.ui-dialog');
					uiDialog.width(uiDialog.width()).height(uiDialog.height());
				},
				close: function( event, ui ) {
					hQuery(this).dialog('destroy').remove();
				}
			}, settings);

			var windowCounter = hQuery('body').data('windowCounter');
			if (windowCounter == undefined) { windowCounter = 0 }
			hQuery('body').data('windowCounter', windowCounter + 1);

			return hQuery('<div>')
				.addClass("hostcmsWindow")
				.attr("id", "Window" + windowCounter)
				.appendTo(hQuery(document.body))
				.dialog(settings);
		},
		showWindow: function(windowId, content, settings) {
			settings = jQuery.extend({
				autoOpen: false, resizable: true, draggable: true, Minimize: false, Closable: true
			}, settings);

			var jWin = jQuery('#' + windowId);

			if (!jWin.length)
			{
				jWin = hQuery.createWindow(settings)
					.attr('id', windowId)
					.html(content);
			}

			jWin.dialog('open');

			return jWin;
		},
		openWindow: function(settings) {
			settings = hQuery.extend({
				width: /*'70%',*/hQuery(window).width() * 0.7,
				height: /*500,*/hQuery(window).height() * 0.7,
				path: '',
				additionalParams: ''
			}, settings);

			var jDivWin = hQuery.createWindow(settings), cmsrequest = settings.path;
			if (settings.additionalParams != ' ' && settings.additionalParams != '')
			{
				cmsrequest += '?' + settings.additionalParams;
			}
			jDivWin
				.append('<iframe src="' + cmsrequest + '&hostcmsMode=blank"></iframe>')
				.dialog('open');
			return jDivWin;
		}
	});
//})(hQuery);