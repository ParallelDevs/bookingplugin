var workingDays = [];
var holidays = [];

function fillProjectSelect (id, data) {
  var $select = $(id);
  $select.find('option')
          .remove();
  $('<option>').val('')
          .text('')
          .appendTo($select);
  $.each(data, function (key, value) {
    $('<option>').val(value.projectId)
            .text(value.name)
            .appendTo($select);
  });
}

function customerChangeHandler () {
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

function dateChangeHandler (dp, $input) {
  var selectedDate = moment($input.val(), 'YYYY-MM-DD');

  if (!dateIsValid($input.val()) && !confirm(confirmBookingNonBusiness)) {
    var val = selectedDate.subtract(1, 'days')
            .format('YYYY-MM-DD');
    $input.val(val)
            .change();
  }
}

function dateIsValid (date) {
  var mDate = moment(date, 'YYYY-MM-DD');
  var isWorkingDay = jQuery.inArray(mDate.day(), workingDays) >= 0 ? true : false;
  var isHoliday = jQuery.inArray(date, holidays) >= 0 ? true : false;
  return (isWorkingDay && !isHoliday);
}

function startDateChangeHandler (startDateId, endDateId) {
  var startDate = $(startDateId).val();
  if ($(endDateId).val() === '') {
    $(endDateId).val(startDate);
  }
}

function initDateField (fieldId) {
  jQuery(fieldId).datetimepicker({
    timepicker: false,
    format: 'Y-m-d',
    formatDate: 'Y-m-d',
    dayOfWeekStart: firstDayOfWeek,
    onSelectDate: dateChangeHandler
  });
}
