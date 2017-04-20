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

    $start = $request->hasParameter('start') ? $request->getParameter('start') : date('Y-m-d');
    $end = $request->hasParameter('end') ? $request->getParameter('end') : date('Y-m-d');
    $bookableId = $request->hasParameter('bookableId') ? $request->getParameter('bookableId') : '';
    $mode = $request->hasParameter('mode') ? $request->getParameter('mode') : '';

    $searchMonths = Booking::calculateAvailibity($start, $end);
    $this->setFilters(array('months' => $searchMonths, 'bookableId' => $bookableId));

    $parameterHolder = new BookingSearchParameterHolder();
    $parameterHolder->setOrderField('bookingId');
    $parameterHolder->setOrderBy('ASC');
    $parameterHolder->setLimit(NULL);
    $parameterHolder->setOffset(0);
    $parameterHolder->setFilters($this->getFilters());
    $parameterHolder->setReturnType(BookingSearchParameterHolder::RETURN_TYPE_CALENDAR_EVENT);

    $bookings = $this->getBookingService()->searchBookingsList($parameterHolder);

    if ('timeline' !== $mode) {
      foreach ($bookings as &$booking) {
        $booking['editable'] = false;
      }
    }

    $holidays = BusinessBookingPluginService::getHolidaysAsCalendarEvents($start, $end);
    $this->result = array_merge($bookings, $holidays);
  }

}
