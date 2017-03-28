<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of viewMyBookingsAction
 *
 * @author amora
 */
class viewMyBookingsAction extends baseBookingAction {

  public function execute($request) {
    $loggedInEmpNum = $this->getUser()->getEmployeeNumber();

    try {
      $bookableResource = $this->getBookableService()->getBookableResource($loggedInEmpNum);
      if ($bookableResource instanceof BookableResource) {
        $this->bookableId = $bookableResource->getBookableId();
      }
    }
    catch (Exception $e) {
      $this->bookableId = '';
      print_r($e);
    }


    $limitHours = BusinessBookingPluginService::getCompanyBusinessLimitHoursForCalendar();
    $firstDay = BusinessBookingPluginService::getCompanyFirstBusinessDay();
    $this->minTime = (!empty($limitHours) && isset($limitHours['minHour'])) ? $limitHours['minHour'] : "00:00:00";
    $this->maxTime = (!empty($limitHours) && isset($limitHours['maxHour'])) ? $limitHours['maxHour'] : "24:00:00";
    $this->firstDayOfWeek = $firstDay;
  }

}