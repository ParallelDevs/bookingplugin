<?php

/**
 * Description of baseBookingUIAction
 *
 * @author pdev
 */
abstract class baseBookingUIAction extends baseBookingAction {

  /**
   *
   */
  public function preExecute() {
    parent::preExecute();
    $licenseIsSet = $this->getConfigBookingService()->isLicenseSet();
    if (!$licenseIsSet) {
      $this->licenseIsValid = false;
      $this->forward('booking', 'licenseBooking');
    }
    else {
      $this->checkLocalLicense();
    }

    //$this->forward404Unless($this->licenseIsValid, 'License has not been activated');
    $this->forwardUnless($this->licenseIsValid, 'booking', 'invalidBooking');
  }

}