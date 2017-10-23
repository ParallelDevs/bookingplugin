<?php

/**
 * Description of BookableResourceSearch
 *
 * @author amora
 */
class SearchBookableResourceForm extends BaseForm {

  public function configure() {
    $this->setWidgets($this->getWidgets());

    $this->setValidators($this->getValidators());

    $formExtension = PluginFormMergeManager::instance();
    $formExtension->mergeForms($this, 'viewBookableResources', 'BookableSearchForm');

    $this->widgetSchema->setNameFormat('bookablesearch[%s]');
    $this->getWidgetSchema()->setLabels($this->getFormLabels());
  }

  /**
   *
   * @return array
   */
  protected function getFormLabels() {

    $labels = array(
      'employee_list' => __('Employee Name'),
      'bookableId' => __('Bookable Id'),
      'employeeId' => __('Employee Id'),
    );
    return $labels;
  }

  /**
   * 
   * @return array
   */
  protected function getWidgets() {
    $widgets = array(
      'bookableId' => new sfWidgetFormInputText(),
      'employeeId' => new sfWidgetFormInputText(),
      'employee_list' => new ohrmWidgetEmployeeNameAutoFill(array('loadingMethod' => 'ajax')),
      'isSubmitted' => new sfWidgetFormInputHidden(array(), array()),
    );
    return $widgets;
  }

  /**
   * 
   * @return array
   */
  protected function getValidators() {
    $validators = array(
      'employee_list' => new ohrmValidatorEmployeeNameAutoFill(),
      'bookableId' => new sfValidatorString(array('required' => false)),
      'employeeId' => new sfValidatorString(array('required' => false)),
      'isSubmitted' => new sfValidatorString(array('required' => false)),
    );
    return $validators;
  }

}
