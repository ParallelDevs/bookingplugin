<?php

/**
 * Description of viewMyBookingsAction
 *
 * @author amora
 */
class viewNoBookableResourceAction extends baseBookingAction {

  public function execute($request) {
    $loggedInEmpNum = $this->getUser()->getEmployeeNumber();
  }

}
