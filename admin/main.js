
var Etk = Etk || {};
Etk.confirmDelete = function (url) {
	if (window.confirm("Opravdu si přejete smazat tento záznam?")) {
		window.location.href=url;
		//return true;
	}
	return false;
};

Etk.confirm = function (message, url) {
	if (window.confirm(message)) {
		window.location.href = url;
		//return true;
	}
	return false;
};

if ($.fn.select2 !== undefined) {
	$.fn.select2.defaults.set('language', 'cs');
}

$(function(){
	var body = $('body');
	var menu = $('#menu');

	$('#etk-switch-menu').click(function() {
		if (!(body.hasClass('etk-menu-loaded') && parseInt(menu.css('width'), 10) == 0)) { // mobile :)
			body.toggleClass('etk-menu-inactive');
		}
		body.removeClass('etk-menu-loaded');
		return false;
	});


	$('.menu-item-inner > a.active').parent().parent().addClass('menu-item-open');
	$('.menu-item-inner > ul a.active').parent().addClass('menu-item-open');
	$('.menu-items li > ul').siblings('a').click(function () {
		var par = $(this).parent();

		par.toggleClass('menu-item-open');
		return body.hasClass('etk-menu-inactive');
	});

	// check all inputs
	$('input[type=checkbox].form-check-all').click(function () {
		var $this = $(this);
		var name = $this.data('check-all-inputs');

		if (name !== undefined) {
			var elems = this.form.elements;
			var lastState;

			for (var i = 0; i < elems.length; i++) {
				if (name === elems[i].name) {
					lastState = elems[i].checked;
					elems[i].checked = this.checked;

					if (elems[i].checked !== lastState) {
						$(elems[i]).trigger('change');
					}
				}
			}
		}
	});

	$('form').on('click', 'input[type=checkbox]', function () {
		var $this = $(this);
		var checkerId = $this.data('check-all-checker');

		if (checkerId !== undefined) {
			var el = document.getElementById(checkerId);

			if (el !== undefined) {
				var lastState = el.checked;
				el.checked = false;

				if (el.checked !== lastState) {
					$(el).trigger('change');
				}
			}
		}
	});

	var datagridBulkButtonsActivator = function ($datagrid) {
		var isAnythingChecked = $('input[type=checkbox][name="selected[]"]:checked', $datagrid).length > 0;
		$('.toolbar input[type=submit]', $datagrid).each(function () {
			var $button = $(this);
			var bulkType = $button.data('bulk-type');

			if (bulkType === 'changeable') {
				$button.prop('disabled', !isAnythingChecked);

				if (isAnythingChecked) {
					$button.removeClass('button-disabled');

				} else {
					$button.addClass('button-disabled');
				}
			}
		});
	};

	$('.datagrid').on('change', 'input[type=checkbox][name="selected[]"]', function () {
		var $datagrid = $(this).parents('.datagrid');
		datagridBulkButtonsActivator($datagrid);
	});

	$('.datagrid').each(function () {
		datagridBulkButtonsActivator($(this));
	});

	$(".form__input--date").datepicker({ changeYear: true });
	$(".form__input--select2").select2();
});
