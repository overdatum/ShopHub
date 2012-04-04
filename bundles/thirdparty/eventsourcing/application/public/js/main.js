$(document).ready(function() {
	$('#tabs a').click(function() {
		$('#tabs li').removeClass('active');
		$(this).parent().addClass('active');

		$('.tab').removeClass('active');
		$('.tab').eq($('#tabs a').index(this)).addClass('active');
	});
});