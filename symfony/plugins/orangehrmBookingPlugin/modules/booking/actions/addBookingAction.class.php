<?php

/**
 * Description of AddBookingAction
 *
 * @author amora
 */
class AddBookingAction extends baseBookingAction {

  /**
   *
   * @param sfForm $form
   */
  public function setForm(sfForm $form) {
    if (is_null($this->form)) {
      $this->form = $form;
    }
  }

  /**
   *
   * @param type $request
   */
  public function execute($request) {
    $this->bookingPermissions = $this->getDataGroupPermissions('booking_bookings');
    if ($this->bookingPermissions->canCreate() || $this->bookingPermissions->canUpdate()) {
      $postArray = array();
      if ($request->isMethod('post')) {
        $postArray = $request->getPostParameters();
        unset($postArray['_csrf_token']);
        $_SESSION['addBookingPost'] = $postArray;
      }

      if (isset($_SESSION['addBookingPost'])) {
        $postArray = $_SESSION['addBookingPost'];
      }

      $this->setForm(new BookingForm(array(), array('bookableSelectable' => true), true));

      if ($this->getUser()->hasFlash('templateMessage')) {
        unset($_SESSION['addBookingPost']);
        list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
      }

      $firstDay = BusinessBookingPluginService::getCompanyFirstBusinessDay();
      $this->firstDayOfWeek = $firstDay;
    }
  }

}
