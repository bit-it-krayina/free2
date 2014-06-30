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

var userSkillsInput = {};
$(document).ready(function() {

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
		prefetch: '/profile-ajax/getavailableskills'
	});

	//TODO-yevgen-grytsay: убрать очистку кеша.
	skills.clearPrefetchCache();
	skills.initialize();

	/**
	 * Init Tag Input
	 * @type {*|jQuery|HTMLElement}
	 */
	var $tagInput = $('.skills-tag-input');
	$tagInput.tagsinput({
		itemValue: 'id',
		itemText: 'text'
	});
	var $tagsInputEl = $tagInput.tagsinput('input');

	/**
	 * Init Typeahead
	 */
	$tagsInputEl.typeahead({}, {
		displayKey: 'text',
        highlight: true,
		source: skills.ttAdapter()
	});

	$tagsInputEl.bind('typeahead:selected', function(obj, datum) {
		$tagInput.tagsinput('add', datum);
		$tagsInputEl.val('');
	});

    /**
     * Добавление произвольного тега, когда freeInput отключен.
     * Пришлось немного модифицировать bootstrap-tagsinput.js
     * (https://github.com/yevgen-grytsay/bootstrap-tagsinput)
     */
	$tagInput.bind('stringInputAttempt', function(event) {
		var value = event.value;
		var totalSuggs = 0;
        var exactSuggestion = null;
		skills.get(value, function(suggestions) {
			totalSuggs = suggestions.length;
            $.each(suggestions, function(index, suggestion) {
                if (suggestion.text.toLowerCase() === value.toLowerCase()) {
                    exactSuggestion = suggestion;
                    return false;
                }
            });
		});

        if (exactSuggestion) {
            $tagInput.tagsinput('add', exactSuggestion);

        } else {
			var object = {id: '_'+ value, text: value};
			skills.add([object]);
			$tagInput.tagsinput('add', object);
		}

        $tagsInputEl.val('').trigger('change');
        $tagsInputEl.typeahead('close');
	});

    userSkillsInput.$tagsInput = $tagInput;
    $(userSkillsInput).trigger('load');



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
