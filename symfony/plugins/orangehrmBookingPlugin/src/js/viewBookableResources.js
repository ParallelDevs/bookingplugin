$(document).ready(function () {
    $('#searchBtn').click(function () {
	$("#bookablesearch_isSubmitted").val('yes');
	$('#search_form input.inputFormatHint').val('');
	$('#search_form input.ac_loading').val('');
	$('#search_form').submit();
    });

    $('#resetBtn').click(function () {
	$("#bookablesearch_isSubmitted").val('yes');
	$("#bookablesearch_employee_name_empName").val('');
	$("#bookablesearch_employee_name_empId").val('');
	$("#bookablesearch_bookableId").val('');
	$("#bookablesearch_employeeId").val('');
	$('#search_form input.inputFormatHint').val('');
	$('#search_form input.ac_loading').val('');
	$('#search_form').submit();
    });    

});

