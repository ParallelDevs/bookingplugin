<?php

/**
 * Description of ConfigureBookingForm
 *
 * @author alonso
 */
class ConfigBookingForm extends sfForm {

  private $configBookingService;
  private $breaksTime;

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

  public function configure() {
    $this->breaksTime = $this->getConfigBookingService()->getCompanyBreaksTime();
    $this->setWidgets(array(
      'breaksTime' => new sfWidgetFormInputText(array(), array('value' => $this->breaksTime)),
    ));

    $this->setValidators(array(
      'breaksTime' => new sfValidatorNumber(array('required' => true)),
    ));

    $formExtension = PluginFormMergeManager::instance();
    $formExtension->mergeForms($this, 'configureBooking', 'ConfigBookingForm');
    $this->getWidgetSchema()->setLabels($this->getFormLabels());
  }

  /**
   *
   * @return array
   */
  protected function getFormLabels() {

    $labels = array(
      'breaksTime' => __('Breaks Time'),
    );
    return $labels;
  }

}
