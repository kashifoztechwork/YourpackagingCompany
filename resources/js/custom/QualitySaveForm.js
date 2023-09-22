$(function () {

	$('.noclass').on('change', function () {
		$currentbutton = $(this);
		$('tr[data-parentid="' + $currentbutton.data('showchildof') + '"]').show();
		event.preventDefault();
	});
	$('.oclass').on('change', function () {
		$currentbutton = $(this);
		$('tr[data-parentid="' + $currentbutton.data('showchildof') + '"]').hide();
		event.preventDefault();
	});


	$("input[type=Radio][value='0']").attr('checked', true);

	$("input[type=Radio][value='0']").on('change', function () {
		$currentbutton = $(this);
		$('tr[data-parentid="' + $currentbutton.data('showchildof') + '"]').hide();
		event.preventDefault();
	});

	$("input[type=Radio][value='1']").on('change', function () {
		$currentbutton = $(this);
		$('tr[data-parentid="' + $currentbutton.data('showchildof') + '"]').show();
		event.preventDefault();
	});

	$("input[type=Radio][value='2']").on('change', function () {
		$currentbutton = $(this);
		$('tr[data-parentid="' + $currentbutton.data('showchildof') + '"]').hide();
		event.preventDefault();
	});



});