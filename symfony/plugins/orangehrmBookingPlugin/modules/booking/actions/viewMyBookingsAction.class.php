<?php

/**
 * Description of viewMyBookingsAction
 *
 * @author amora
 */
class viewMyBookingsAction extends baseBookingUIAction {

  /**
   *
   * @param type $request
   */
  public function execute($request) {
    $this->bookingPermissions = $this->getDataGroupPermissions('booking_my_booking');
    if ($this->bookingPermissions->canRead()) {
      $loggedInEmpNum = $this->getUser()->getEmployeeNumber();

      try {
        $bookableResource = $this->getBookableService()->getBookableResource($loggedInEmpNum);
        if ($bookableResource instanceof BookableResource) {
          $this->bookableId = $bookableResource->getBookableId();
        }
        else {
          $this->bookableId = '';
        }
      }
      catch (Exception $e) {
        $this->bookableId = '';
      }

      $limitHours = BusinessBookingPluginService::getCompanyBusinessLimitHoursForCalendar();
      $firstDay = BusinessBookingPluginService::getCompanyFirstBusinessDay();
      $this->minTime = (!empty($limitHours) && isset($limitHours['minHour'])) ? $limitHours['minHour'] : "09:00:00";
      $this->maxTime = (!empty($limitHours) && isset($limitHours['maxHour'])) ? $limitHours['maxHour'] : "18:00:00";
      $this->firstDayOfWeek = $firstDay;
    }
  }

}
