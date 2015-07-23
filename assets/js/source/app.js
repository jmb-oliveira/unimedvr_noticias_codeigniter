$(document).ready(function() {
	
	/*
	========== Popover ==========
	*/
	$('[data-toggle="popover"]').popover({trigger: 'hover','placement': 'top'});
	
	/*
	========== Tooltip ==========
	*/
	$('[data-toggle="tooltip"]').tooltip();
	
	/*
	========== Bootbox ==========
	*/
	bootbox.setLocale('br');
});