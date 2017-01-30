<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of viewBookingsAction
 *
 * @author amora
 */
class viewBookingsAction extends baseBookingAction {

  public function setBookingForm(sfForm $form) {
    if (is_null($this->bookingForm)) {
      $this->bookingForm = $form;
    }
  }

  public function execute($request) {
    $this->setBookingForm(new BookingForm(array(), array(), true));
    $limitHours = BusinessBookingPluginService::getCompanyBusinessLimitHoursForCalendar();
    $firstDay= BusinessBookingPluginService::getCompanyFirstBusinessDay();
    $this->minTime = (!empty($limitHours) && isset($limitHours['minHour'])) ? $limitHours['minHour'] : "00:00:00";
    $this->maxTime = (!empty($limitHours) && isset($limitHours['maxHour'])) ? $limitHours['maxHour'] : "24:00:00";
    $this->firstDayOfWeek = $firstDay;
  }

}
