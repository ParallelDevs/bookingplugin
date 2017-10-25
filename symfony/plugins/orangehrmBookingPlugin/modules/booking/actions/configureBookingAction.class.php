<?php

/**
 * Description of configureBookingAction
 *
 * @author amora
 */
class configureBookingAction extends baseBookingAction {

  private $configBookingService;

  /**
   *
   * @param ConfigBookingService $configService
   */
  public function setConfigBookingService(ConfigBookingService $configService) {
    $this->configBookingService = $configService;
  }

  /**
   *
   * @return type
   */
  public function getConfigBookingService() {
    if (!$this->configBookingService instanceof ConfigBookingService) {
      $this->configBookingService = new ConfigBookingService();
      $this->configBookingService->setConfigDao(new ConfigDao());
    }
    return $this->configBookingService;
  }

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
    $this->bookingConfigurationPermissions = $this->getDataGroupPermissions('booking_configuration');
    if ($this->bookingConfigurationPermissions->canRead()) {
      $this->setForm(new ConfigBookingForm(array(), array(), false));

      if ($request->isMethod('post')) {
        $this->saveConfiguration($request);
      }
    }
  }

  /**
   * 
   * @param type $request
   */
  private function saveConfiguration(&$request) {
    if ($this->bookingConfigurationPermissions->canCreate() || $this->bookingConfigurationPermissions->canUpdate()) {
      $this->form->bind($request->getPostParameters(), $request->getFiles());
      if ($this->form->isValid()) {

        $breaks = $this->form->getValue('breaksTime');
        
        try {
          $this->getConfigBookingService()->setCompanyBreaksTime($breaks);          
          $this->getUser()->setFlash('success', __(TopLevelMessages::SAVE_SUCCESS));
          $this->redirect('booking/configureBooking');
        }
        catch (Exception $e) {
          print($e->getMessage());
        }
      }
    }
  }

}
