function fillProjectSelect(e,n){var o=$(e);o.find("option").remove(),$("<option>").val("").text("").appendTo(o),$.each(n,function(e,n){$("<option>").val(n.projectId).text(n.name).appendTo(o)})}function customerChangeHandler(){var e=$(this).val();""!==e&&$.ajax({type:"POST",url:customerProjectUrl,data:{customerId:e},cache:!1,success:function(e){fillProjectSelect("#projectId",e)}})}function startDateChangeHandler(e,n){var o=$(e).val();""===$(n).val()&&$(n).val(o)}function initDateField(e){jQuery(e).datetimepicker({timepicker:!1,format:"Y-m-d",formatDate:"Y-m-d",dayOfWeekStart:firstDayOfWeek})}function initModalFields(){initDateField("#startDate"),initDateField("#endDate")}function ajaxLoadNewBooking(){$.ajax({type:"POST",url:bookingFormUrl,data:{bookableId:bookableId,bookableName:bookableName,startDate:startDate,endDate:endDate,minStartTime:minStartTime,maxEndTime:maxEndTime},success:function(e){$("#addBooking").find(".modal-body").html(e),initModalFields(),$("#addBooking").modal("show")}})}function ajaxLoadEditBooking(e){$.ajax({type:"POST",url:bookingFormUrl,data:{bookingId:bookingId,bookableId:bookableId,startDate:startDate,endDate:endDate,minStartTime:minStartTime,maxEndTime:maxEndTime},success:function(e){$("#editBooking").find(".modal-body").html(e),initModalFields(),$("#editBooking").modal("show")},fail:function(){revertCalendar(e)}})}function ajaxSaveBooking(){$.ajax({type:"POST",url:saveBookingUrl,data:$(".form-booking-plugin").serialize(),cache:!1,success:successBookingForm,dataType:"json"})}function successBookingForm(e){if(e.success)$(activeModalId).modal("hide");else for(var n=e.errors.length,o=0;o<n;o++){var a=$("#"+e.errors[o].field);$("<span>").addClass("validation-error").addClass(e.errors[o].field).attr("generated","true").text(e.errors[o].message).insertAfter(a),a.addClass("error-field")}}function refreshBookings(){$("#calendar").fullCalendar("refetchEvents"),holidayEvent=null}function resourceErrorHandler(){}function resourceRenderHandler(e,n,o){e.isActive?n.addClass("booking-resource-active"):(n.addClass("booking-resource-inactive"),n.tipTip({content:inactiveResourceTooltip}),jQuery.isEmptyObject(o)||o.addClass("fc-nonbusiness"))}function eventErrorHandler(){}function eventRenderHandler(e,n,o){if(e.isHoliday)n.tipTip({content:holidayLabel+" "+e.title}),n.addClass("fc-nonbusiness booking-holiday");else if(n.tipTip({content:e.customerName+" - "+e.title}),o)switch(o.type){case"timelineMonth":n.find(".fc-time").text(e.duration+"h p/d"),n.find(".fc-title").remove();break;case"timelineWeek":n.find(".fc-title").text(e.duration+"h p/d");break;case"timelineDay":break;case"month":n.find(".fc-time").text(e.duration+"h p/d");break;case"agendaWeek":n.find(".fc-time").remove()}}function eventMouseoverHandler(e,n,o){$(this).addClass("fc-highlighted")}function eventMouseoutHandler(e,n,o){$(this).removeClass("fc-highlighted")}function loadVarsFromEvent(e){var n=$("#calendar").fullCalendar("getResourceById",e.resourceId);bookingId=e.id,startDate=e.start.format("YYYY-MM-DD"),endDate=e.end.format("YYYY-MM-DD"),bookableId=n.id,bookableName=n.title,minStartTime=n.businessHours[0].start,maxEndTime=n.businessHours[0].end}function revertCalendar(e){jQuery.isFunction(e)?e():refreshBookings(),holidayEvent=null}function addBookingConfirmBusinessDays(e,n,o){var a=$("#calendar").fullCalendar("getResourceById",o),t=jQuery.inArray(e.day(),a.businessHours[0].dow)>=0,i=jQuery.inArray(n.day(),a.businessHours[0].dow)>=0,r=!(!holidayEvent||!e.isSame(holidayEvent.start,"day")),d=!(!holidayEvent||!n.isSame(holidayEvent.start,"day")),s="";t?i?(r||d)&&(s=confirmBookingHoliday):s=confirmEndBookingNonBusiness:s=confirmStartBookingNonBusiness,!r&&!d&&t&&i?ajaxLoadNewBooking():confirm(s)?ajaxLoadNewBooking():($("#calendar").fullCalendar("unselect"),holidayEvent=null)}function editBookingConfirmNonBusinessDays(e,n){var o=e.start,a=e.end,t=$("#calendar").fullCalendar("getResourceById",e.resourceId),i=jQuery.inArray(o.day(),t.businessHours[0].dow)>=0,r=jQuery.inArray(a.day(),t.businessHours[0].dow)>=0,d=!(!holidayEvent||!o.isSame(holidayEvent.start,"day")),s=!(!holidayEvent||!a.isSame(holidayEvent.start,"day")),l="";i?r?(d||s)&&(l=confirmBookingHoliday):l=confirmEndBookingNonBusiness:l=confirmStartBookingNonBusiness,!d&&!s&&i&&r?ajaxLoadEditBooking(n):confirm(l)?ajaxLoadEditBooking(n):revertCalendar(n)}function eventAfterRenderHandler(e,n,o){e.editable&&n.bind("dblclick",function(){eventDblClickHandler(e)})}function eventResizeHandler(e,n,o,a,t,i){loadVarsFromEvent(e),editBookingConfirmNonBusinessDays(e,o)}function eventDropHandler(e,n,o,a,t,i,r,d){0!==n.asDays()&&(loadVarsFromEvent(e),editBookingConfirmNonBusinessDays(e,t))}function eventDblClickHandler(e){loadVarsFromEvent(e),ajaxLoadEditBooking(refreshBookings)}function eventOverlapHandler(e,n){return holidayEvent=e.isHoliday?e:null,!0}function selectHandler(e,n,o,a,t){bookableId=t.id,bookableName=t.title,minStartTime=t.businessHours[0].start,maxEndTime=t.businessHours[0].end,startDate=e.format("YYYY-MM-DD");var i=n.subtract(1,"days");i.isBefore(startDate)&&(i=e),endDate=i.format("YYYY-MM-DD"),addBookingConfirmBusinessDays(e,i,t.id)}function selectAllowHandler(e){var n=$("#calendar").fullCalendar("getResourceById",e.resourceId);return!(n&&!n.isActive)}function selectOverlapHandler(e){return holidayEvent=e.isHoliday?e:null,!0}var activeModalId="";jQuery(document).ready(function(){$("#addBooking, #editBooking").on("hide.bs.modal",function(){$(this).find(".modal-body").empty(),refreshBookings()}),$("#addBooking, #editBooking").on("change","#customerId",customerChangeHandler),$("#addBooking, #editBooking").on("change","#startDate",function(){startDateChangeHandler("#startDate","#endDate")}),$("#addBooking").on("click",".btn.save",function(){activeModalId="#addBooking",$("#addBooking .form-booking-plugin").find(".validation-error").remove(),$("#addBooking .form-booking-plugin input").removeClass("error-field"),ajaxSaveBooking()}),$("#editBooking").on("click",".btn.save",function(){activeModalId="#editBooking",$("#editBooking .form-booking-plugin").find(".validation-error").remove(),$("#editBooking .form-booking-plugin input").removeClass("error-field"),ajaxSaveBooking()})});var bookingId="",bookableId="",bookableName="",startDate="",endDate="",minStartTime="",maxEndTime="",holidayEvent=null;$(function(){$("#calendar").fullCalendar({schedulerLicenseKey:"GPL-My-Project-Is-Open-Source",now:moment().startOf("day"),selectable:!0,selectHelper:!0,editable:!0,eventResourceEditable:!1,aspectRatio:4,firstDay:firstDayOfWeek,slotEventOverlap:!1,minTime:calendarMinTime,maxTime:calendarMaxTime,header:{left:"prev,next today",center:"title",right:"timelineDay,timelineWeek,timelineMonth"},defaultView:"timelineMonth",resourceAreaWidth:"25%",resourceLabelText:bookableResourceTitle,resources:{url:bookableResourcesUrl,type:"POST",error:resourceErrorHandler},events:{url:bookingResourcesUrl,data:{mode:"timeline"},type:"POST",error:eventErrorHandler},resourceRender:resourceRenderHandler,eventRender:eventRenderHandler,eventAfterRender:eventAfterRenderHandler,eventMouseover:eventMouseoverHandler,eventMouseout:eventMouseoutHandler,eventResize:eventResizeHandler,eventDrop:eventDropHandler,eventOverlap:eventOverlapHandler,select:selectHandler,selectAllow:selectAllowHandler,selectOverlap:selectOverlapHandler})});