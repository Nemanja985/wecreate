jQuery(document).ready(function($) {	
	// var recPage = 1;
	$(document).on('click', '.admin-sort', function(){

		$('#spinner').show();
		var paged = 1;
		var status = $(this).attr('data-id');
		var data = {
			'action': 'admin_sort_recipe',
			'status': status,
			'paged': paged
		};
		jQuery.post(ajax_admin_params.admin_ajax_url, data, function(response) {
			$('.recipe-item-wrapper').html(response);
			$('#spinner').hide();
			// recPage++;
		});
	});
	$(document).on('keypress', '#admin-search', function(){
		$('#spinner').show();
		var paged = 1;
		var status = $('#admin-sort-by').val();
		var data = {
			's': $(this).val(),
			'action': 'admin_sort_recipe',
			'status': status,
			'paged': paged
		};
		jQuery.post(ajax_admin_params.admin_ajax_url, data, function(response) {
			$('.recipe-item-wrapper').html(response);
			$('#spinner').hide();
			// recPage++;
		});
	});
	$(document).on('keyup', '#admin-search', function(e) {
		if (e.keyCode == 8 || e.keyCode == 46) { //backspace and delete key
            $('#spinner').show();
			var paged = 1;
			var status = $('#admin-sort-by').val();
			var data = {
				's': $(this).val(),
				'action': 'admin_sort_recipe',
				'status': status,
				'paged': paged
			};
			jQuery.post(ajax_admin_params.admin_ajax_url, data, function(response) {
				$('.recipe-item-wrapper').html(response);
				$('#spinner').hide();
				// recPage++;
			});
        } else { // rest ignore
            e.preventDefault();
        }
		
	});
	//var query = [];
	$(document).on('click', '.admin-prev-next-btn', function(){
		$('#spinner').show();
		$('.admin-prev-next-btn').removeClass('active');
		$(this).addClass('active');
		var paged = $(this).data('page');
		var status = $(this).data('status');
		var data = {
			'action': 'admin_sort_recipe',
			'status': status,
			'paged': paged
		};
		var me = $(this);
		jQuery.post(ajax_admin_params.admin_ajax_url, data, function(response) {
			$('.recipe-item-wrapper').html(response);
			$('#spinner').hide();
		});
	});
	// $(document).on('click', '#recipe-prev', function(){
	// 	$('#spinner').show();
	// 	var paged = $(this).data('page');
	// 	var status = $(this).val();
	// 	var data = {
	// 		'action': 'admin_sort_recipe',
	// 		'status': status,
	// 		'paged': paged
	// 	};
	// 	var me = $(this);
	// 	jQuery.post(ajax_admin_params.admin_ajax_url, data, function(response) {
	// 		$('.recipe-item-wrapper').html(response);
	// 		$('#spinner').hide();

	// 		var post_count = $(response).filter('#post-count').html();
	// 		var page = data.paged;
	// 		$('#recipe-next').prop('disabled', false);
	// 		$('#recipe-next').data('page', page);
	// 		if(post_count < 20){
	// 			$('#recipe-prev').prop('disabled', true);
	// 		}else{
	// 			$('#recipe-prev').data('page', Number(page) - 1);
	// 		}
	// 		// if(page < 2){
	// 		// 	$('#recipe-prev').prop('disabled', true);
	// 		// }else{
	// 		// 	$('#recipe-prev').prop('disabled', false);
	// 		// }
			
	// 		// recPage++;

	// 	});
	// });
});