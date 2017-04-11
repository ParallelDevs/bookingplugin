<?php

/**
 * Description of viewBookingsAction
 *
 * @author amora
 */
class viewBookingsAction extends baseBookingAction {

  /**
   *
   * @param sfForm $form
   */
  public function setBookingForm(sfForm $form) {
    if (is_null($this->bookingForm)) {
      $this->bookingForm = $form;
    }
  }

  /**
   *
   * @param type $request
   */
  public function execute($request) {
    $this->setBookingForm(new BookingForm(array(), array(), true));
    $limitHours = BusinessBookingPluginService::getCompanyBusinessLimitHoursForCalendar();
    $firstDay = BusinessBookingPluginService::getCompanyFirstBusinessDay();
    $this->minTime = (!empty($limitHours) && isset($limitHours['minHour'])) ? $limitHours['minHour'] : "00:00:00";
    $this->maxTime = (!empty($limitHours) && isset($limitHours['maxHour'])) ? $limitHours['maxHour'] : "24:00:00";
    $this->firstDayOfWeek = $firstDay;
  }

}
