//=include _booking.form.js

jQuery(document).ready(function () {    
    $("#btnSave").click(function () {
        $("#frmBooking").submit();
    });

    if ($("#bookableId").val() !== '') {
        $("#bookableId").change();
    }

    if ($("#customerId").val() !== '') {
        $("#customerId").change();
    }
});