<?php

/**
 * Description of deleteBookingAction
 *
 * @author amora
 */
class deleteBookingAction extends baseBookingAction {

  /**
   *
   * @param type $request
   */
  public function execute($request) {
    $this->bookingPermissions = $this->getDataGroupPermissions('booking_bookings');
    if ($this->bookingPermissions->canDelete()) {
      $response = array();

      $bookingId = $request->getParameter('bookingId');
      try {
        $this->getBookingService()->deleteBooking($bookingId);
        $response['success'] = true;
        $response['errors'] = array();
      }
      catch (Exception $e) {
        $response['success'] = false;
        $response['errors'] = array($e->getMessage());
        sfContext::getInstance()->getLogger()->err($e->getMessage());
      }

      $this->result = $response;
    }
  }

}
