$(document).ready(function(){
	$('.js-tagsinput').tagsinput();

/**
 * редактирование поля формы юзера
 */
$('.js-profile-form-field').focusout(function(){

	var csrf = $('input[name="csrf"]').serialize();
	$.ajax({
		url:'/profile-ajax/editField',
		cache: false,
		data: $(this).serialize() + '&'+ csrf,
		dataType: 'json',
		success: function(data, status) {
			console.log(data);
		}

	})
});

});