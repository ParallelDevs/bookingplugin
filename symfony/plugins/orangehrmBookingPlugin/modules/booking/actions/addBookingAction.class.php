<?php

/**
 * Description of AddBookingAction
 *
 * @author alonso
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

  public function execute($request) {
    $postArray = array();
    if ($request->isMethod('post')) {
      $postArray = $request->getPostParameters();
      unset($postArray['_csrf_token']);
      $_SESSION['addBookingPost'] = $postArray;
    }

    if (isset($_SESSION['addBookingPost'])) {
      $postArray = $_SESSION['addBookingPost'];
    }

    $this->setForm(new AddBookingForm(array(), array(), true));

    if ($this->getUser()->hasFlash('templateMessage')) {
      unset($_SESSION['addBookingPost']);
      list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
    }

    if ($request->isMethod('post')) {
      $this->form->bind($request->getPostParameters(), $request->getFiles());
      if ($this->form->isValid()) {
        try {
          $bookingId = $this->form->save();
          unset($_SESSION['addBookingPost']);
          $this->redirect('booking/viewBookings');
        }
        catch (Exception $e) {
          print($e->getMessage());
          sfContext::getInstance()->getLogger()->err($e->getMessage());
        }
      }
    }
  }

  private function validateBooking() {
    $this->getUser()->setFlash('warning', __('Failed To Save: Resource Already Exists'));
    return false;
  }

}
