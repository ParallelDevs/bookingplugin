var calendarOptions = {
  schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
  now: moment().startOf('day'),
  selectable: false,
  selectHelper: false,
  editable: false,
  eventResourceEditable: false,
  aspectRatio: 2.25,
  firstDay: 1,
  slotEventOverlap: false,
  minTime: '00:00:00',
  maxTime: '23:59:59',
  businessHours: true,
  lazyFetching: false,
  resourceAreaWidth: '0%',
  themeSystem: 'standard',
  header: {
    left: 'prev,next today',
    center: 'title',
    right: 'month,timelineWeek'
  },
  defaultView: 'month',
  resources: {
    url: '',
    data: {
      bookableId: 0,
    },
    type: 'POST',
    error: resourceErrorHandler,
  },
  events: {
    url: '',
    data: {
      bookableId: 0
    },
    type: 'POST',
    error: eventErrorHandler,
  },
  resourceRender: resourceRenderHandler,
  eventRender: eventRenderHandler,
  eventMouseover: eventMouseoverHandler,
  eventMouseout: eventMouseoutHandler,
  selectAllow: false,
  views: {
    timelineWeek: {
      slotDuration: {
        days: 1
      }
    },
    timelineFilter: {
      type: 'timeline',
      duration: {
        days: 1
      },
      slotDuration: {
        days: 1
      }
    }
  }
};

$(document).ready(function () {
  $("#calendar").on('click', ".fc-timelineMonth-button, .fc-timelineWeek-button", function () {
    $("#searchStartDate").val('')
            .change();
    $("#searchEndDate").val('')
            .change();
    $('.btn.clear').addClass('disabled')
            .attr('disabled', 'disabled');
  });

  $(".btn.filter").click(filterBookings);
  $(".btn.clear").click(clearFilter);
  $('.btn.clear').addClass('disabled')
          .attr('disabled', 'disabled');
});
