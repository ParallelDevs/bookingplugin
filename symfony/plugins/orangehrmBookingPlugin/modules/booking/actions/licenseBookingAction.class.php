<?php

/**
 * Description of addBookableResourceAction
 *
 * @author amora
 */
class licenseBookingAction extends baseBookingAction {

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
    $this->setForm(new LicenseBookingForm());

    if ($request->isMethod('post')) {
      $this->form->bind($request->getPostParameters(), $request->getFiles());
      if ($this->form->isValid()) {
        $this->activatePlugin($request);
      }
    }
  }

  /**
   * 
   * @param type $request
   */
  private function activatePlugin($request) {
    $email = $this->form->getValue('email');
    $licenseKey = $this->form->getValue('licenseKey');
    $licenseData = $this->getLicenseBookingService()->activateLicense($email,$licenseKey);
    
    if ($licenseData->result == 'success') {
      $this->getConfigBookingService()->setLicenseEmail($email);
      $this->getConfigBookingService()->setLicenseKey($licenseKey);
      $this->getConfigBookingService()->setLicenseSecret($licenseData->secret);
      $this->getUser()->setFlash('success', __($licenseData->message));
      $this->redirect('booking/configureBooking');
    }else {
      $this->getUser()->setFlash('error', __($licenseData->message));
    }
  }

}
