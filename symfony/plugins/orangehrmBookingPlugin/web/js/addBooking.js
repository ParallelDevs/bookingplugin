function fillProjectSelect(e,t){var a=$(e);a.find("option").remove(),$.each(t,function(e,t){$("<option>").val(t.projectId).text(t.name).appendTo(a)})}function lockForm(){$("#startDate").attr("disabled","disabled"),$("#endDate").attr("disabled","disabled"),$("#startTime").attr("disabled","disabled"),$("#endTime").attr("disabled","disabled"),$("#hours").attr("disabled","disabled"),$("#minutes").attr("disabled","disabled"),$("#customerId").attr("disabled","disabled"),$("#projectId").attr("disabled","disabled")}function unlockForm(){$("#startDate").removeAttr("disabled"),$("#endDate").removeAttr("disabled"),$("#startTime").removeAttr("disabled"),$("#endTime").removeAttr("disabled"),$("#hours").removeAttr("disabled"),$("#minutes").removeAttr("disabled"),$("#customerId").removeAttr("disabled"),$("#projectId").removeAttr("disabled")}function setBookableWorkShift(e){var t=moment(e.minTime,"H:m"),a=moment(e.maxTime,"H:m");a.add(15,"minutes"),jQuery("#minStartTime").val(t.format("HH:mm:ss")),jQuery("#startTime").datetimepicker({minTime:t.toDate(),maxTime:a.toDate()}),jQuery("#endTime").datetimepicker({minTime:t.toDate(),maxTime:a.toDate()})}jQuery(document).ready(function(){$(".specific-time").hide();var e=$("#bookableId").val();""===e&&lockForm(),$("#bookableId").change(function(){var e=$(this).val();""!==e&&$.ajax({type:"POST",url:bookableWorkShiftsUrl,data:{bookableId:e},cache:!1,success:function(e){unlockForm(),setBookableWorkShift(e)}})}),$("#customerId").change(function(){var e=$(this).val();""!==e&&$.ajax({type:"POST",url:customerProjectUrl,data:{customerId:e},cache:!1,success:function(e){fillProjectSelect("#projectId",e)}})}),jQuery("#startDate").datetimepicker({timepicker:!1,format:"Y-m-d",formatDate:"Y-m-d",dayOfWeekStart:firstDayOfWeek}),jQuery("#endDate").datetimepicker({timepicker:!1,format:"Y-m-d",formatDate:"Y-m-d",dayOfWeekStart:firstDayOfWeek}),jQuery("#startTime").datetimepicker({datepicker:!1,timepicker:!0,format:"H:i",formatTime:"H:i",step:15,dayOfWeekStart:firstDayOfWeek}),jQuery("#endTime").datetimepicker({datepicker:!1,timepicker:!0,format:"H:i",formatTime:"H:i",step:15,dayOfWeekStart:firstDayOfWeek}),$("#btn-booking-time").click(function(){$(".duration").fadeOut(800,function(){$(".specific-time").fadeIn(400)}),$("#bookingType").val(BOOKING_SPECIFIC_TIME)}),$("#btn-booking-duration").click(function(){$(".specific-time").fadeOut(800,function(){$(".duration").fadeIn(400)}),$("#bookingType").val(BOOKING_HOURS)})}),jQuery(document).ready(function(){$("#bookableId").change(function(){var e=$(this).val();""!=e&&$.ajax({type:"POST",url:bookableWorkShiftsUrl,data:{bookableId:e},cache:!1,success:function(e){setBookableWorkShift(e)}})}),$("#btnSave").click(function(){$("#frmBooking").submit()}),""!==$("#bookableId").val()&&$("#bookableId").change(),""!==$("#customerId").val()&&$("#customerId").change()});