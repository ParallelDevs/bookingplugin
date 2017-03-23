function fillProjectSelect(id, data) {
    var $select = $(id);
    $select.find('option').remove();
    $('<option>').val('').text('').appendTo($select);
    $.each(data, function (key, value) {
        $('<option>').val(value.projectId).text(value.name).appendTo($select);
    });
}

function customerChangeHandler() {
    var id = $(this).val();
    if (id !== '') {
        $.ajax({
            type: "POST",
            url: customerProjectUrl,
            data: {customerId: id},
            cache: false,
            success: function (data)
            {
                fillProjectSelect('#projectId', data);
            }
        });
    }
}

function startDateChangeHandler(startDateId, endDateId) {
    var startDate = $(startDateId).val();
    if ($(endDateId).val() === '') {
        $(endDateId).val(startDate);
    }
}

function initDateField(fieldId) {
    jQuery(fieldId).datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        formatDate: 'Y-m-d',
        dayOfWeekStart: firstDayOfWeek
    });
}
