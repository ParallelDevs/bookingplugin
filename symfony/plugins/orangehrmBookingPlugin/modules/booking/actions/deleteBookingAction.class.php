<?php

/**
 * Description of AddBookingAjaxAction
 *
 * @author amora
 */
class deleteBookingAction extends baseBookingAction {

  public function execute($request) {
    $this->setLayout(false);
    sfConfig::set('sf_web_debug', false);
    sfConfig::set('sf_debug', false);
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
