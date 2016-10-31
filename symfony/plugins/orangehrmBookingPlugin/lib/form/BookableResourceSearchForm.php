<?php

/**
 * Description of BookableResourceSearch
 *
 * @author amora
 */
class BookableResourceSearchForm extends BaseForm {

  public function configure() {
    $this->setWidgets(array(
      'bookableId' => new sfWidgetFormInputText(),
      'employeeId' => new sfWidgetFormInputText(),
      'employee_list' => new ohrmWidgetEmployeeNameAutoFill(array('loadingMethod' => 'ajax')),
    ));

    $this->setWidget('isSubmitted', new sfWidgetFormInputHidden(array(), array()));
    $this->setValidator('isSubmitted', new sfValidatorString(array('required' => false)));
    $this->setValidator('employee_list', new ohrmValidatorEmployeeNameAutoFill());
    $this->setValidator('bookableId', new sfValidatorString(array('required' => false)));
    $this->setValidator('employeeId', new sfValidatorString(array('required' => false)));

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

}
