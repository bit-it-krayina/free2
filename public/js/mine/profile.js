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
	});
}

function savePicture(form) {
	$.ajax({
		url:'/profile-ajax/saveFile',
		cache: false,
		data: $(form).serialize() ,
		dataType: 'json',
		method: 'post',
		success: function(data, status) {
			console.log(data);
		}
	});
}

$(document).ready(function(){

	/**
	 * Редактирование навыков
	 */
	/**
	 * Init Bloodhound
	 * @type {Bloodhound}
	 */
	var skills = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		prefetch: '/profile-ajax/getavailabletags'
	});

	//TODO: убрать очистку кеша.
	skills.clearPrefetchCache();
	skills.initialize();

	/**
	 * Init Tag Input
	 * @type {*|jQuery|HTMLElement}
	 */
	var $allInOne = $('.skills-typehead-together');
	$allInOne.tagsinput({
		itemValue: 'id',
		itemText: 'text'
	});
	var $tagsInputEl = $allInOne.tagsinput('input');

	/**
	 * Init Typehead
	 */
	$tagsInputEl.typeahead({}, {
		displayKey: 'text',
		source: skills.ttAdapter()
	});

	$tagsInputEl.bind('typeahead:selected', function(obj, datum) {
		$allInOne.tagsinput('add', datum);
		//$tagsInputEl.typeahead('close');
		//$tagsInputEl.typeahead('setQuery', '');
		console.log($allInOne.val());
	});





	$('.js-datepicker').each(function(){
		$(this).attr('data-date', $(this).val() );
		$(this).datepicker({
			format: 'yyyy-mm-dd',
			viewMode: 'years'
		}).on('hide', function(ev) {
			saveField($(this));
		}).data('datepicker');
	});

	/**
	 * редактирование поля формы юзера
	 */
	$('.js-profile-form-field').not('.js-datepicker').focusout(function(){
		saveField($(this));
	});


	$('.profile-header').on('click', '.js-dropdown-item', function () {
		$.ajax({
			url:'/profile-ajax/changeStatus',
			cache: false,
			data: 'user=' + $(this).data('user') + '&status=' + $(this).data('status'),
			dataType: 'json',
			method: 'post',
			success: function(data, status) {
				if ( typeof  data.profileDropdownBlock != 'undefined') {
					$('#profileDropdownBlock').replaceWith( data.profileDropdownBlock)
				}
			}
		});
	});

	$('.profile-photo-delete').on('click', function(){
		$.ajax({
			url:'/profile-ajax/deletePhoto',
			cache: false,
			data: 'user=' + $(this).data('user') ,
			dataType: 'json',
			method: 'post',
			success: function(data, status) {
				if ( typeof data.photo_block != 'undefined') {
					$('.profile-photo').replaceWith( data.photo_block);
				}
			}
		});
	});

});
