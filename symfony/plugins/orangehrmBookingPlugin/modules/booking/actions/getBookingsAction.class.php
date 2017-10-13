<?php

/**
 * Description of getBookingsAction
 *
 * @author amora
 */
class getBookingsAction extends baseBookingAction {

  /**
   *
   * @param type $request
   */
  public function execute($request) {
    $this->bookingPermissions = $this->getDataGroupPermissions('booking_bookings');
    if ($this->bookingPermissions->canRead()) {

      $start = $request->hasParameter('start') ? $request->getParameter('start') : date('Y-m-d');
      $end = $request->hasParameter('end') ? $request->getParameter('end') : date('Y-m-d');
      $bookableId = $request->hasParameter('bookableId') ? $request->getParameter('bookableId') : '';

      $this->setFilters(array(
        'range' => array('start' => $start, 'end' => $end),        
        'bookableId' => $bookableId,
      ));
      $parameterHolder = $this->getSearchParameterHolder();
      $bookings = $this->getBookingService()->searchBookingsList($parameterHolder);

      $this->checkPermissions($bookings);

      $holidays = BusinessBookingPluginService::getHolidaysAsCalendarEvents($start, $end);
      $this->result = array_merge($bookings, $holidays);
    }
  }

  /**
   * 
   * @return \BookingSearchParameterHolder
   */
  protected function getSearchParameterHolder() {
    $parameterHolder = new BookingSearchParameterHolder();
    $parameterHolder->setOrderField('bookingId');
    $parameterHolder->setOrderBy('ASC');
    $parameterHolder->setLimit(NULL);
    $parameterHolder->setOffset(0);
    $parameterHolder->setFilters($this->getFilters());
    $parameterHolder->setReturnType(BookingSearchParameterHolder::RETURN_TYPE_CALENDAR_EVENT);
    return $parameterHolder;
  }

  /**
   *
   * @param type $bookings
   */
  private function checkPermissions(&$bookings) {
    if (!$this->bookingPermissions->canCreate() && !$this->bookingPermissions->canUpdate()) {
      foreach ($bookings as &$booking) {
        $booking['editable'] = false;
      }
    }
  }

}
