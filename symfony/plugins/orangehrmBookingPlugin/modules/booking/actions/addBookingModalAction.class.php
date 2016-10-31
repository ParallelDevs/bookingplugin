<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddBookingAjaxAction
 *
 * @author amora
 */
class AddBookingModalAction extends baseBookingAction {

  public function execute($request) {
    $this->setLayout(false);
    sfConfig::set('sf_web_debug', false);
    sfConfig::set('sf_debug', false);
    $response = array();


    if ($request->isMethod('post')) {
      $this->form = new AddBookingFormModal(array(), array(), true);
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
