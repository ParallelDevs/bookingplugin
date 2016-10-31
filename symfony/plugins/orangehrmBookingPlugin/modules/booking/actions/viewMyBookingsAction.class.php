<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of viewMyBookingsAction
 *
 * @author amora
 */
class viewMyBookingsAction extends baseBookingAction {

  public function execute($request) {
    $loggedInEmpNum = $this->getUser()->getEmployeeNumber();

    try {
      $bookableResource = $this->getBookableService()->getBookableResource($loggedInEmpNum);
      if ($bookableResource instanceof BookableResource) {
        $this->bookableId = $bookableResource->getBookableId();
      }
    }
    catch (Exception $e) {
      print_r($e);
    }
  }

}
