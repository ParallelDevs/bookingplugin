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
        $action = $this->form->getValue('licenseAction');
        $action .= 'License';
        $this->{$action}();
      }
    }
  }

  /**
   * 
   * @param type $request
   */
  private function activateLicense() {
    $email = $this->form->getValue('email');
    $licenseKey = $this->form->getValue('licenseKey');
    $response = $this->getLicenseBookingService()->activateLicense($email, $licenseKey);
    $licenseData = json_decode($response);

    $this->getConfigBookingService()->setLicenseEmail($email);
    $this->getConfigBookingService()->setLicenseKey($licenseKey);

    if ($licenseData->result == 'success') {
      $this->getConfigBookingService()->setLicenseSecret($licenseData->secret);
      $this->getUser()->setFlash('success', __($licenseData->message));
      $this->redirect('booking/configureBooking');
    }
    else {
      $this->getUser()->setFlash('error', __($licenseData->message));
    }
  }

  private function checkLicense() {
    $email = $this->form->getValue('email');
    $licenseKey = $this->form->getValue('licenseKey');
    $licenseSecret = $this->getConfigBookingService()->getLicenseSecret();
    $response = $this->getLicenseBookingService()->checkLicense($email, $licenseKey,$licenseSecret);
    $licenseData = json_decode($response);

    if ($licenseData->result == 'success') {
      $this->getUser()->setFlash('success', __($licenseData->message));
    }
    else {
      $this->getUser()->setFlash('error', __($licenseData->message));
    }
  }

}
