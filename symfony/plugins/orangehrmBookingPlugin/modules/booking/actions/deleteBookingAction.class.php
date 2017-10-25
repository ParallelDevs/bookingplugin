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

      if ($request->isMethod('post')) {
        $this->form = new BookingForm(array(), array(), true);
        $this->form->bind($request->getPostParameters(), $request->getFiles());
        $this->deleteBooking($response);
      }

      $this->result = $response;
    }
  }

  /**
   *
   * @param type $response
   */
  private function deleteBooking(&$response) {
    if ($this->form->isValid()) {
      $bookingId = $this->form->getValue('bookingId');
      try {
        $this->getBookingService()->deleteBooking($bookingId);
        $response['success'] = true;
        $response['errors'] = array();
        $this->sendNotification();
      }
      catch (Exception $e) {
        $response['success'] = false;
        $response['errors'] = array($e->getMessage());
        sfContext::getInstance()->getLogger()->err($e->getMessage());
      }
    }
  }

  /**
   *
   */
  private function sendNotification() {
    $notify = $this->form->getValue('notify');

    if (!empty($notify)) {
      $eventType = BookingEvents::BOOKING_DELETE;
      $eventData = array(
        'bookableId' => $this->form->getValue('bookableId'),
        'projectId' => $this->form->getValue('projectId'),
        'actionType' => $eventType,
      );
      $this->getDispatcher()->notify(new sfEvent($this, $eventType, $eventData));
    }
  }

}
