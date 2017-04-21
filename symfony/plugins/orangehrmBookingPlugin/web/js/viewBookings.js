function fillProjectSelect(e,a){var n=$(e);n.find("option").remove(),$("<option>").val("").text("").appendTo(n),$.each(a,function(e,a){$("<option>").val(a.projectId).text(a.name).appendTo(n)})}function customerChangeHandler(){var e=$(this).val();""!==e&&$.ajax({type:"POST",url:customerProjectUrl,data:{customerId:e},cache:!1,success:function(e){fillProjectSelect("#projectId",e)}})}function dateChangeHandler(e,a){var n=moment(a.val(),"YYYY-MM-DD");if(!dateIsValid(a.val())&&!confirm(confirmBookingNonBusiness)){var o=n.subtract(1,"days").format("YYYY-MM-DD");a.val(o).change()}}function dateIsValid(e){var a=moment(e,"YYYY-MM-DD"),n=jQuery.inArray(a.day(),workingDays)>=0,o=jQuery.inArray(e,holidays)>=0;return n&&!o}function startDateChangeHandler(e,a){var n=$(e).val();""===$(a).val()&&$(a).val(n)}function initDateField(e){jQuery(e).datetimepicker({timepicker:!1,format:"Y-m-d",formatDate:"Y-m-d",dayOfWeekStart:firstDayOfWeek,onSelectDate:dateChangeHandler})}function initModalFields(){initDateField("#startDate"),initDateField("#endDate")}function ajaxLoadNewBooking(){$.ajax({type:"POST",url:bookingFormUrl,data:{bookableId:bookableId,bookableName:bookableName,startDate:startDate,endDate:endDate,minStartTime:minStartTime,maxEndTime:maxEndTime,workingDays:workingDays.join()},success:function(e){$("#addBooking").find(".modal-body").html(e),initModalFields(),$("#addBooking").modal("show")}})}function ajaxLoadEditBooking(e){$.ajax({type:"POST",url:bookingFormUrl,data:{bookingId:bookingId,bookableId:bookableId,startDate:startDate,endDate:endDate,minStartTime:minStartTime,maxEndTime:maxEndTime,workingDays:workingDays.join()},success:function(e){$("#editBooking").find(".modal-body").html(e),initModalFields(),$("#editBooking").modal("show")},fail:function(){revertCalendar(e)}})}function ajaxSaveBooking(){$.ajax({type:"POST",url:saveBookingUrl,data:$(".form-booking-plugin").serialize(),cache:!1,success:successBookingForm,dataType:"json"})}function ajaxDeleteBooking(){bookingId=$("#bookingId").val(),$.ajax({type:"POST",url:deleteBookingUrl,data:{bookingId:bookingId},cache:!1,success:successBookingForm,dataType:"json"})}function successBookingForm(e){if(e.success)$(activeModalId).modal("hide");else for(var a=e.errors.length,n=0;n<a;n++){var o=$("#"+e.errors[n].field);$("<span>").addClass("validation-error").addClass(e.errors[n].field).attr("generated","true").text(e.errors[n].message).insertAfter(o),o.addClass("error-field")}}function refreshBookings(){$("#calendar").fullCalendar("refetchEvents"),holidayEvent=null}function resourceErrorHandler(){}function resourceRenderHandler(e,a,n){}function resourceRenderAdminHandler(e,a,n){e.isActive?a.addClass("booking-resource-active"):(a.addClass("booking-resource-inactive"),a.tipTip({content:inactiveResourceTooltip}),jQuery.isEmptyObject(n)||n.addClass("fc-nonbusiness"))}function eventErrorHandler(){}function eventRenderHandler(e,a,n){if(e.isHoliday)a.tipTip({content:holidayLabel+" "+e.title}),a.addClass("fc-nonbusiness booking-holiday");else if(a.tipTip({content:e.customerName+" - "+e.title}),a.addClass("booking"),n)switch(n.type){case"timelineMonth":case"timelineWeek":case"timelineFilter":a.find(".fc-time").text(e.duration+"h p/d"),a.find(".fc-title").remove();break;case"month":case"basicWeek":a.find(".fc-time").text(e.duration+"h p/d")}}function eventMouseoverHandler(e,a,n){$(this).addClass("fc-highlighted")}function eventMouseoutHandler(e,a,n){$(this).removeClass("fc-highlighted")}function loadVarsFromEvent(e){var a=$("#calendar").fullCalendar("getResourceById",e.resourceId);bookingId=e.id,startDate=e.start.format("YYYY-MM-DD"),endDate=e.end.format("YYYY-MM-DD"),bookableId=a.id,bookableName=a.title,minStartTime=a.businessHours[0].start,maxEndTime=a.businessHours[0].end,workingDays=a.businessHours[0].dow}function revertCalendar(e){jQuery.isFunction(e)?e():refreshBookings(),holidayEvent=null}function addBookingConfirmNonBusinessDays(e,a,n){var o=$("#calendar").fullCalendar("getResourceById",n),t=jQuery.inArray(e.day(),o.businessHours[0].dow)>=0,i=jQuery.inArray(a.day(),o.businessHours[0].dow)>=0,r=!(!holidayEvent||!e.isSame(holidayEvent.start,"day")),d=!(!holidayEvent||!a.isSame(holidayEvent.start,"day")),s="";t?i?(r||d)&&(s=confirmBookingHoliday):s=confirmEndBookingNonBusiness:s=confirmStartBookingNonBusiness,!r&&!d&&t&&i?ajaxLoadNewBooking():confirm(s)?ajaxLoadNewBooking():($("#calendar").fullCalendar("unselect"),holidayEvent=null)}function editBookingConfirmNonBusinessDays(e,a){var n=e.start,o=e.end,t=$("#calendar").fullCalendar("getResourceById",e.resourceId),i=jQuery.inArray(n.day(),t.businessHours[0].dow)>=0,r=jQuery.inArray(o.day(),t.businessHours[0].dow)>=0,d=!(!holidayEvent||!n.isSame(holidayEvent.start,"day")),s=!(!holidayEvent||!o.isSame(holidayEvent.start,"day")),l="";i?r?(d||s)&&(l=confirmBookingHoliday):l=confirmEndBookingNonBusiness:l=confirmStartBookingNonBusiness,!d&&!s&&i&&r?ajaxLoadEditBooking(a):confirm(l)?ajaxLoadEditBooking(a):revertCalendar(a)}function eventAfterRenderHandler(e,a,n){if(e.isHoliday){var o=e.start.format("YYYY-MM-DD");jQuery.inArray(o,holidays)<0&&holidays.push(o)}e.editable&&a.bind("dblclick",function(){eventDblClickHandler(e)})}function eventResizeHandler(e,a,n,o,t,i){loadVarsFromEvent(e),editBookingConfirmNonBusinessDays(e,n)}function eventDropHandler(e,a,n,o,t,i,r,d){0!==a.asDays()&&(loadVarsFromEvent(e),editBookingConfirmNonBusinessDays(e,t))}function eventDblClickHandler(e){loadVarsFromEvent(e),ajaxLoadEditBooking(refreshBookings)}function eventOverlapHandler(e,a){return holidayEvent=e.isHoliday?e:null,!0}function selectHandler(e,a,n,o,t){bookableId=t.id,bookableName=t.title,minStartTime=t.businessHours[0].start,maxEndTime=t.businessHours[0].end,workingDays=t.businessHours[0].dow,startDate=e.format("YYYY-MM-DD");var i=a.subtract(1,"days");i.isBefore(startDate)&&(i=e),endDate=i.format("YYYY-MM-DD"),addBookingConfirmNonBusinessDays(e,i,t.id)}function selectAllowHandler(e){var a=$("#calendar").fullCalendar("getResourceById",e.resourceId);return!(a&&!a.isActive)}function selectOverlapHandler(e){return holidayEvent=e.isHoliday?e:null,!0}function filterBookings(e){var a=moment($("#searchStartDate").val(),"YYYY-MM-DD",!0),n=moment($("#searchEndDate").val(),"YYYY-MM-DD",!0);if(a.isValid()&&n.isValid()&&n.isSameOrAfter(a)){var o=n.add(1,"days").diff(a,"days");calendarOptions.views[e].duration.days=o,$("#calendar").fullCalendar("destroy"),$("#calendar").fullCalendar(calendarOptions),$("#calendar").fullCalendar("changeView",e,a)}}function changeSearchStartDate(){if(""===$("#searchEndDate").val()){var e=moment($(this).val(),"YYYY-MM-DD",!0),a="";switch($("#calendar").fullCalendar("getView").name){case"timelineMonth":case"month":a=e.add(1,"month").format("YYYY-MM-DD");break;case"timelineWeek":case"basicWeek":a=e.add(1,"week").format("YYYY-MM-DD");break;default:a=e.format("YYYY-MM-DD")}$("#searchEndDate").val(a)}}var workingDays=[],holidays=[],activeModalId="";jQuery(document).ready(function(){$("#addBooking, #editBooking").on("hide.bs.modal",function(){$(this).find(".modal-body").empty(),refreshBookings()}),$("#addBooking, #editBooking").on("change","#customerId",customerChangeHandler),$("#addBooking, #editBooking").on("change","#startDate",function(){startDateChangeHandler("#startDate","#endDate")}),$("#addBooking").on("click",".btn.save",function(){activeModalId="#addBooking",$("#addBooking .form-booking-plugin").find(".validation-error").remove(),$("#addBooking .form-booking-plugin").find(".error-field").removeClass("error-field"),$("#addBooking .form-booking-plugin input").removeClass("error-field"),ajaxSaveBooking()}),$("#editBooking").on("click",".btn.save",function(){activeModalId="#editBooking",$("#editBooking .form-booking-plugin").find(".validation-error").remove(),$("#editBooking .form-booking-plugin").find(".error-field").removeClass("error-field"),$("#editBooking .form-booking-plugin input").removeClass("error-field"),ajaxSaveBooking()}),$("#editBooking").on("click",".btn.delete",function(){activeModalId="#editBooking",confirm(confirmDeleteBooking)&&ajaxDeleteBooking()})});var bookingId="",bookableId="",bookableName="",startDate="",endDate="",minStartTime="",maxEndTime="",holidayEvent=null;$(document).ready(function(){jQuery("#searchStartDate").datetimepicker({timepicker:!1,format:"Y-m-d",formatDate:"Y-m-d",dayOfWeekStart:firstDayOfWeek}),jQuery("#searchEndDate").datetimepicker({timepicker:!1,format:"Y-m-d",formatDate:"Y-m-d",dayOfWeekStart:firstDayOfWeek}),$("#searchStartDate").change(changeSearchStartDate)});var calendarOptions={schedulerLicenseKey:"GPL-My-Project-Is-Open-Source",now:moment().startOf("day"),defaultDate:moment().format("YYYY-MM-DD"),firstDay:1,selectable:!0,selectHelper:!0,editable:!0,eventResourceEditable:!1,aspectRatio:2.5,slotEventOverlap:!1,minTime:"00:00:00",maxTime:"23:59:59",header:{left:"prev,next today",center:"title",right:"timelineMonth,timelineWeek"},defaultView:"timelineMonth",resourceAreaWidth:"25%",resourceLabelText:"",resources:{url:"",type:"POST",error:resourceErrorHandler},events:{url:"",data:{mode:"timeline"},type:"POST",error:eventErrorHandler},resourceRender:resourceRenderAdminHandler,eventRender:eventRenderHandler,eventAfterRender:eventAfterRenderHandler,eventMouseover:eventMouseoverHandler,eventMouseout:eventMouseoutHandler,eventResize:eventResizeHandler,eventDrop:eventDropHandler,eventOverlap:eventOverlapHandler,select:selectHandler,selectAllow:selectAllowHandler,selectOverlap:selectOverlapHandler,views:{timelineDay:{slotDuration:{days:1}},timelineWeek:{slotDuration:{days:1}},timelineFilter:{type:"timeline",duration:{days:1},slotDuration:{days:1}}}};$(document).ready(function(){$("#calendar").on("click",".fc-timelineMonth-button, .fc-timelineWeek-button",function(){$("#searchStartDate").val("").change(),$("#searchEndDate").val("").change()}),$(".btn.filter").click(function(){filterBookings("timelineFilter")})}),$(function(){calendarOptions.firstDay=firstDayOfWeek,calendarOptions.minTime=calendarMinTime,calendarOptions.maxTime=calendarMaxTime,calendarOptions.resourceLabelText=bookableResourceTitle,calendarOptions.resources.url=bookableResourcesUrl,calendarOptions.events.url=bookingResourcesUrl,$("#calendar").fullCalendar(calendarOptions)});