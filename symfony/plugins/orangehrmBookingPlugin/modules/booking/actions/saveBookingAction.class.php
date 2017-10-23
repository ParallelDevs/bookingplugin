<?php

/**
 * Description of saveBookingAction
 *
 * @author amora
 */
class saveBookingAction extends baseBookingAction {

  /**
   *
   * @param type $request
   */
  public function execute($request) {
    $this->bookingPermissions = $this->getDataGroupPermissions('booking_bookings');
    if ($this->bookingPermissions->canCreate() || $this->bookingPermissions->canUpdate()) {
      $response = array();

      if ($request->isMethod('post')) {
        $this->form = new BookingForm(array(), array(), true);
        $this->form->bind($request->getPostParameters(), $request->getFiles());
        $this->saveBooking($response);
      }

      $this->result = $response;
    }
  }

  /**
   * 
   * @param type $response
   */
  private function saveBooking(&$response) {
    if ($this->form->isValid()) {
      try {
        $this->form->save();
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
    else {
      $response['success'] = false;
      foreach ($this->form->getErrors() as $name => $error) {
        $response['errors'][] = array(
          'field' => $name,
          'message' => $error->getMessage(),
        );
      }
    }
  }

  /**
   * 
   */
  private function sendNotification() {
    $notify = $this->form->getValue('notify');

    if (!empty($notify)) {
      $eventData = array(
        'bookableId' => $this->form->getValue('bookableId'),
        'projectId' => $this->form->getValue('projectId'),
      );
      $this->getDispatcher()->notify(new sfEvent($this, BookingEvents::BOOKING_SAVE, $eventData));
    }
  }

}
