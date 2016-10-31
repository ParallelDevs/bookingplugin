<?php

/**
 * Description of configureBookingAction
 *
 * @author alonso
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

  public function execute($request) {
    $this->setForm(new ConfigBookingForm(array(), array(), false));

    if ($request->isMethod('post')) {

      $this->form->bind($request->getPostParameters(), $request->getFiles());
      if ($this->form->isValid()) {

        $breaks = $this->form->getValue('breaksTime');
        try {
          $this->getConfigBookingService()->setCompanyBreaksTime($breaks);
          $this->getUser()->setFlash('success', __(TopLevelMessages::SAVE_SUCCESS));
        }
        catch (Exception $e) {
          print($e->getMessage());
        }
      }
    }
  }

}
