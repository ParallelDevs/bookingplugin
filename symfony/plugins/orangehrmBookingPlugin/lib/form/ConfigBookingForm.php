<?php

/**
 * Description of ConfigureBookingForm
 *
 * @author alonso
 */
class ConfigBookingForm extends sfForm {

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

  public function configure() {
    $this->setWidgets(array(
      'breaksTime' => new sfWidgetFormInputText(array(), array('placeholder' => __('Breaks Time in Hours'))),      
    ));
    
    $this->setDefaults(array(
      'breaksTime' => $this->getConfigBookingService()->getCompanyBreaksTime(),      
    ));

    $this->setValidators(array(
      'breaksTime' => new sfValidatorNumber(array('required' => true, 'min' => 0)),      
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
