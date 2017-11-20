<?php

/**
 * Description of ConfigureBookingForm
 *
 * @author alonso
 */
class LicenseBookingForm extends sfForm {

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
    $actions = array(
      'activate' => 'Activate',
      'check' => 'Check',
      'delete' => 'Delete',
    );

    $this->setWidgets(array(
      'email' => new sfWidgetFormInputText(array(), array('placeholder' => __('Email'))),
      'licenseKey' => new sfWidgetFormInputText(array(), array('placeholder' => __('License Key'))),
      'licenseAction' => new sfWidgetFormChoice(array(
        'expanded' => true,
        'choices' => $actions,
        'default' => 'activate',
          )),
    ));

    $this->setDefaults(array(
      'email' => $this->getConfigBookingService()->getLicenseEmail(),
      'licenseKey' => $this->getConfigBookingService()->getLicenseKey(),
    ));

    $this->setValidators(array(
      'email' => new sfValidatorEmail(array('required' => true)),
      'licenseKey' => new sfValidatorString(array('required' => true)),
      'licenseAction' => new sfValidatorChoice(array(
        'choices' => array(
          'activate',
          'check',
          'delete',
        ),
          )),
    ));

    $formExtension = PluginFormMergeManager::instance();
    $formExtension->mergeForms($this, 'activateBooking', 'ActivateBookingForm');
    $this->getWidgetSchema()->setLabels($this->getFormLabels());
  }

  /**
   *
   * @return array
   */
  protected function getFormLabels() {

    $labels = array(
      'email' => __('Email'),
      'licenseKey' => __('License Key'),
      'licenseAction' => __('Action'),
    );
    return $labels;
  }

}
