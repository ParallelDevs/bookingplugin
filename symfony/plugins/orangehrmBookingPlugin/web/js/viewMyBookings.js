function resourceErrorHandler(){}function resourceRenderHandler(e,a,t){$(".fc-resource-area.fc-widget-header").hide(),$(".fc-resource-area.fc-widget-content").hide(),$(".fc-col-resizer").remove()}function resourceRenderAdminHandler(e,a,t){$(".fc-resource-area.fc-widget-header").addClass("booking-resource-header"),$(".fc-resource-area.fc-widget-content").addClass("booking-resource-content"),e.isActive?a.addClass("booking-resource-active"):(a.addClass("booking-resource-inactive"),a.tipTip({content:inactiveResourceTooltip}),jQuery.isEmptyObject(t)||t.addClass("fc-nonbusiness"))}function eventErrorHandler(){}function eventRenderHandler(e,a,t){if(e.isHoliday)a.tipTip({content:holidayLabel+" "+e.title}),a.addClass("fc-nonbusiness booking-holiday");else if(a.tipTip({content:e.customerName+" - "+e.title}),a.addClass("booking"),t)switch(t.type){case"timelineMonth":case"timelineWeek":case"timelineFilter":case"month":case"basicWeek":a.find(".fc-time").text(e.duration+"h p/d"),a.find(".fc-title").remove()}}function eventMouseoverHandler(e,a,t){$(this).addClass("fc-highlighted")}function eventMouseoutHandler(e,a,t){$(this).removeClass("fc-highlighted")}function filterBookings(){var e=moment($("#searchStartDate").val(),"YYYY-MM-DD",!0),a=moment($("#searchEndDate").val(),"YYYY-MM-DD",!0);if(e.isValid()&&a.isValid()&&a.isSameOrAfter(e)){var t=a.add(1,"days").diff(e,"days");calendarOptions.views.timelineFilter.duration.days=t,$("#calendar").fullCalendar("destroy"),$("#calendar").fullCalendar(calendarOptions),$("#calendar").fullCalendar("changeView","timelineFilter",e),$(".btn.clear").removeClass("disabled").removeAttr("disabled")}}function clearFilter(){$("#searchStartDate").val("").change(),$("#searchEndDate").val("").change(),$(".btn.clear").addClass("disabled").attr("disabled","disabled"),$("#calendar").fullCalendar("destroy"),$("#calendar").fullCalendar(calendarOptions)}function changeSearchStartDate(){if(""===$("#searchEndDate").val()){var e=moment($(this).val(),"YYYY-MM-DD",!0),a="";switch($("#calendar").fullCalendar("getView").name){case"timelineMonth":case"month":a=e.add(1,"month").format("YYYY-MM-DD");break;case"timelineWeek":case"basicWeek":a=e.add(1,"week").format("YYYY-MM-DD");break;default:a=e.format("YYYY-MM-DD")}$("#searchEndDate").val(a)}}$(document).ready(function(){jQuery("#searchStartDate").datetimepicker({timepicker:!1,format:"Y-m-d",formatDate:"Y-m-d",dayOfWeekStart:firstDayOfWeek}),jQuery("#searchEndDate").datetimepicker({timepicker:!1,format:"Y-m-d",formatDate:"Y-m-d",dayOfWeekStart:firstDayOfWeek}),$("#searchStartDate").change(changeSearchStartDate)});var calendarOptions={schedulerLicenseKey:"GPL-My-Project-Is-Open-Source",now:moment().startOf("day"),selectable:!1,selectHelper:!1,editable:!1,eventResourceEditable:!1,aspectRatio:3,firstDay:1,slotEventOverlap:!1,minTime:"00:00:00",maxTime:"23:59:59",businessHours:!0,lazyFetching:!1,resourceAreaWidth:"0%",header:{left:"prev,next today",center:"title",right:"timelineMonth,timelineWeek"},defaultView:"timelineMonth",resources:{url:"",data:{bookableId:0},type:"POST",error:resourceErrorHandler},events:{url:"",data:{bookableId:0,mode:"resource"},type:"POST",error:eventErrorHandler},resourceRender:resourceRenderHandler,eventRender:eventRenderHandler,eventMouseover:eventMouseoverHandler,eventMouseout:eventMouseoutHandler,selectAllow:!1,views:{timelineWeek:{slotDuration:{days:1}},timelineFilter:{type:"timeline",duration:{days:1},slotDuration:{days:1}}}};$(document).ready(function(){$("#calendar").on("click",".fc-timelineMonth-button, .fc-timelineWeek-button",function(){$("#searchStartDate").val("").change(),$("#searchEndDate").val("").change(),$(".btn.clear").addClass("disabled").attr("disabled","disabled")}),$(".btn.filter").click(filterBookings),$(".btn.clear").click(clearFilter),$(".btn.clear").addClass("disabled").attr("disabled","disabled")}),$(function(){calendarOptions.firstDay=firstDayOfWeek,calendarOptions.minTime=calendarMinTime,calendarOptions.maxTime=calendarMaxTime,calendarOptions.resources.url=bookableResourcesUrl,calendarOptions.resources.data.bookableId=bookableId,calendarOptions.events.url=bookingResourcesUrl,calendarOptions.events.data.bookableId=bookableId,$("#calendar").fullCalendar(calendarOptions)});