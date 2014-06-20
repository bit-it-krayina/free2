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
//	$('.js-tagsinput').tagsinput({
//		typeahead{
//			source: ['sql', 'symphony', 'mysql', 'mysqli']
//			source: function(query) {
//				console.log('123');
//				return $.getJSON('/profile-ajax/getAvailableTags');
//			}
//		}
//	});

	// $('.js-tagsinput').tagsinput({
	// 	typeahead: {
	// 		source: function(query) {
	// 			return $.getJSON('getAvailableTags.json');
	// 		}
	// 	}
	// });



// var substringMatcher = function(strs) {
//   return function findMatches(q, cb) {
//     var matches, substringRegex;

//     matches = [];
//     substrRegex = new RegExp(q, 'i');

//     $.each(strs, function(i, str) {
//       if (substrRegex.test(str)) {
//         matches.push({ value: str });
//       }
//     });

//     cb(matches);
//   };
// };

// var states = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
//   'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
//   'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
//   'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
//   'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
//   'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
//   'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
//   'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
//   'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
// ];


// 	$('.js-tagsinput').tagsinput();
// 	$('.bootstrap-tagsinput input').typeahead({} ,{
// 		source: substringMatcher(states)
// 	});


	//$('.js-tagsinput').tagsinput();

	var myTags = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('tags'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		prefetch: '/js/countries.json'
	});

	myTags.initialize();

	$('.js-tagsinput').typeahead(
		{},
		{
			source: myTags.ttAdapter()
		}
	);


	$('.js-datepicker').each(function(){
		$(this).attr('data-date', $(this).val() );
		$(this).datepicker({
			format: 'yyyy-mm-dd',
			viewMode: 'years',
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


});
