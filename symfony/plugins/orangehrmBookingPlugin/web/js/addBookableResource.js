$(document).ready(function () {
    $("#btnSave").click(function () {
	$("#empNum").val($("#employee_empId").val());
	$("#frmAddBookable").submit();
    });

    $("#bookableColor").spectrum({
	preferredFormat: "hex",
    });

});

