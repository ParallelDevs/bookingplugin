function resourceError(){}function resourceRender(e,t,r){e.isActive?t.addClass("booking-resource-active"):(t.addClass("booking-resource-inactive"),t.tipTip({content:inactiveResourceTooltip}),jQuery.isEmptyObject(r)||r.addClass("fc-nonbusiness"))}function eventError(){}function eventRender(e,t,r){if(e.isHoliday)t.tipTip({content:holidayLabel+" "+e.title}),t.addClass("fc-nonbusiness booking-holiday");else if(t.tipTip({content:e.customerName+" - "+e.title}),r)switch(r.type){case"timelineMonth":t.find(".fc-time").text(e.duration+"h p/d"),t.find(".fc-title").remove();break;case"timelineWeek":t.find(".fc-title").text(e.duration+"h p/d");break;case"timelineDay":}}function eventAfterRender(e,t,r){}$(function(){$("#calendar").fullCalendar({schedulerLicenseKey:"GPL-My-Project-Is-Open-Source",now:moment().startOf("day"),selectable:!0,selectHelper:!0,editable:!0,aspectRatio:4,firstDay:firstDayOfWeek,slotEventOverlap:!1,minTime:calendarMinTime,maxTime:calendarMaxTime,header:{left:"prev,next today",center:"title",right:"timelineDay,timelineWeek,timelineMonth"},defaultView:"timelineMonth",resourceAreaWidth:"25%",resourceLabelText:bookableResourceTitle,resources:{url:bookableResourcesUrl,type:"POST",error:resourceError},events:{url:bookingResourcesUrl,data:{mode:"timeline"},type:"POST",error:eventError},resourceRender:resourceRender,eventRender:eventRender,eventAfterRender:eventAfterRender})});