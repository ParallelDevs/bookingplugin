<?php

/**
 * Description of AddBookingAjaxAction
 *
 * @author amora
 */
class saveBookingAction extends baseBookingAction {

  public function execute($request) {
    $this->setLayout(false);
    sfConfig::set('sf_web_debug', false);
    sfConfig::set('sf_debug', false);
    $response = array();


    if ($request->isMethod('post')) {
      $this->form = new BookingForm(array(), array(), true);
      $this->form->bind($request->getPostParameters(), $request->getFiles());
      if ($this->form->isValid()) {
        try {
          $this->form->save();
          $response['success'] = true;
          $response['errors'] = array();
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

    $this->result = $response;
  }

}
