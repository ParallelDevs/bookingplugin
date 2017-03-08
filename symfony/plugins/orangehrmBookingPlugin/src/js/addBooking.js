//=include _booking.form.js

jQuery(document).ready(function () {
    $("#bookableId").change(function () {
        var id = $(this).val();
        if (id != '') {
            $.ajax({
                type: "POST",
                url: bookableWorkShiftsUrl,
                data: {bookableId: id},
                cache: false,
                success: function (data)
                {
                    setBookableWorkShift(data);
                }
            });
        }
    });

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