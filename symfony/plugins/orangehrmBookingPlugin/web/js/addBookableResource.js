$(document).ready(function () {
    $("#btnSave").click(function () {
	$("#empNum").val($("#employee_empId").val());
	$("#frmBookable").submit();
    });

    $("#bookableColor").spectrum({
	preferredFormat: "hex",
    });

});

