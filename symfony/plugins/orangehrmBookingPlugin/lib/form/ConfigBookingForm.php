<?php

/**
 * Description of ConfigureBookingForm
 *
 * @author alonso
 */
class ConfigBookingForm extends sfForm {

  public function configure() {
    $this->setWidgets(array(
      'breaksTime' => new sfWidgetFormInputText(),
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
