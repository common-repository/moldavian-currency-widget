(function ($) {
	"use strict";
	$(function () {


		// This code include bootsrap only for widget not entire admin panel
		var bootstrapCss = 'bootstrapCss';
	if (!document.getElementById(bootstrapCss))
	{
		var head = document.getElementsByTagName('head')[0];
		var bootstrapWrapper = document.createElement('link');
		bootstrapWrapper.id = bootstrapCss;
		bootstrapWrapper.rel = 'stylesheet/less';
		bootstrapWrapper.type = 'text/css';
		bootstrapWrapper.href = '../wp-content/plugins/mdl-currency/css/bootstrap-include.less';
		bootstrapWrapper.media = 'all';
		head.appendChild(bootstrapWrapper);

		var lessjs = document.createElement('script');
		lessjs.type = 'text/javascript';
		lessjs.src = '../wp-content/plugins/mdl-currency/js/less.min.js';
		head.appendChild(lessjs);

		//load other stylesheets that override bootstrap styles

		var adminCSS = document.createElement('link');
		adminCSS.id = "adminCSS";
		adminCSS.rel = 'stylesheet';
		adminCSS.type = 'text/css';
		adminCSS.href = '../wp-content/plugins/mdl-currency/css/admin.css';
		adminCSS.media = 'all';
		head.appendChild(adminCSS);
	}



		//Launch bootstrap-selectpicker
		$('.selectpicker').selectpicker();

		$(document).on('widget-added', function(e, widget) {
			$('.selectpicker').selectpicker();
			// fix duplicating of select fields
			widget.find('.btn.dropdown-toggle.btn-default:first').hide();
		})
		
		// prevents disappearance of selectpicker on updating widget
		$(document).on('widget-updated', function(e, widget) {
			$(widget).find('.selectpicker').selectpicker('refresh');
		});


		

	});




}(jQuery));