function saveField($field) {
	var csrf = $('input[name="csrf"]').serialize();
	$.ajax({
		url:'/profile-ajax/editField',
		cache: false,
		data: $field.serialize() + '&'+ csrf,
		dataType: 'json',
		method: 'post',
		success: function(data, status) {
			console.log(data);
		}

	})
}

$(document).ready(function(){
	$('.js-tagsinput').tagsinput();

	$('.js-datepicker').each(function(){
		$(this).attr('data-date', $(this).val() );
		$(this).datepicker({
			format: 'yyyy-mm-dd',
			viewMode: 'years',
		}).on('hide', function(ev) {
			saveField($(this));
		}).data('datepicker');;
	})

/**
 * редактирование поля формы юзера
 */
$('.js-profile-form-field').not('.js-datepicker').focusout(function(){
	saveField($(this));
});


});