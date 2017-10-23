<?php

/**
 * Description of ConfigureBookingForm
 *
 * @author alonso
 */
class ConfigBookingForm extends sfForm {

  private $configBookingService;
  private $breaksTime;
  private $notificationEmail;
  private $notificationSubject;

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
      'notificationSubject' => new sfWidgetFormInputText(array(), array('placeholder' => __('Notification Subject'))),
      'notificationEmail' => new sfWidgetFormTextarea(array(), array())
    ));
    
    $this->setDefaults(array(
      'breaksTime' => $this->getConfigBookingService()->getCompanyBreaksTime(),
      'notificationEmail' => $this->getConfigBookingService()->getNotificationEmail(),
      'notificationSubject' => $this->getConfigBookingService()->getNotificationSubject(),
    ));

    $this->setValidators(array(
      'breaksTime' => new sfValidatorNumber(array('required' => true, 'min' => 0)),
      'notificationEmail' => new sfValidatorString(array('required' => true)),
      'notificationSubject' => new sfValidatorString(array('required' => true)),
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
      'notificationSubject' => __('Notification Subject'),
      'notificationEmail' => __('Notification Email'),
    );
    return $labels;
  }

}
