function resourceErrorHandler () {

}
function resourceRenderHandler (resourceObj, labelTds, bodyTds) {
  $(".fc-resource-area.fc-widget-header").addClass("booking-resource-header")
          .addClass("hidden");
  $(".fc-resource-area.fc-widget-content").addClass("booking-resource-content")
          .addClass("hidden");
  $(".fc-col-resizer").remove();
}

function resourceRenderAdminHandler (resourceObj, labelTds, bodyTds) {
  $(".fc-resource-area.fc-widget-header").addClass("booking-resource-header");
  $(".fc-resource-area.fc-widget-content").addClass("booking-resource-content");

  if (resourceObj.isActive) {
    labelTds.addClass('booking-resource-active');
  } else {
    labelTds.addClass('booking-resource-inactive');
    labelTds.tipTip({
      content: inactiveResourceTooltip
    });

    if (!jQuery.isEmptyObject(bodyTds)) {
      bodyTds.addClass('fc-nonbusiness');
    }
  }
}
