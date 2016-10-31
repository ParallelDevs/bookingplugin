<?php

/**
 * Description of BookableResourceAddForm
 *
 * @author amora
 */
class AddBookableResourceForm extends sfForm {

  private $bookableService;

  /**
   *
   * @return type
   */
  public function getBookableService() {
    if (!$this->bookableService instanceof BookableResourceService) {
      $this->bookableService = new BookableResourceService();
      $this->bookableService->setBookableDao(new BookableResourceDao());
    }
    return $this->bookableService;
  }

  /**
   *
   * @param BookableResourceService $bookableService
   */
  public function setBookableService(BookableResourceService $bookableService) {
    $this->bookableService = $bookableService;
  }

  public function configure() {
    $this->setWidgets($this->getWidgets());
    $this->setValidators($this->getValidators());

    $formExtension = PluginFormMergeManager::instance();
    $formExtension->mergeForms($this, 'addBookableResource', 'AddBookableResourceForm');
    $this->getWidgetSchema()->setLabels($this->getFormLabels());
  }

  /**
   *
   * @return \BookableResource
   */
  public function getBookableResource() {
    $posts = $this->getValues();
    $bookable = new BookableResource();
    $bookable->empNumber = $posts['empNum'];
    $bookable->isActive = $posts['status'];
    $bookable->bookableColor = $posts['bookableColor'];
    return $bookable;
  }

  /**
   *
   * @return type
   */
  public function save() {
    $bookable = $this->getBookableResource();
    $service = $this->getBookableService();
    $service->saveBookableResource($bookable);
    $bookableId = $bookable->bookableId;
    return $bookableId;
  }

  /**
   *
   * @return type
   */
  protected function getWidgets() {
    $status = array(
      BookableResource::STATUS_ACTIVE => __('Active'),
      BookableResource::STATUS_INACTIVE => __('Inactive'),
    );

    return array(
      'empNum' => new sfWidgetFormInputHidden(array(), array()),
      'employee' => new ohrmWidgetEmployeeNameAutoFill(array('loadingMethod' => 'ajax')),
      'status' => new sfWidgetFormSelect(array('choices' => $status), array("class" => "formInputText")),
      'bookableColor' => new sfWidgetFormInputText(array(), array()),
    );
  }

  /**
   *
   * @return type
   */
  protected function getValidators() {
    return array(
      'empNum' => new sfValidatorString(array('required' => false)),
      'employee' => new sfValidatorString(array('required' => true)),
      'status' => new sfValidatorString(array('required' => false)),
      'bookableColor' => new sfValidatorString(array('required' => false)),
    );
  }

  /**
   *
   * @return array
   */
  protected function getFormLabels() {

    $labels = array(
      'employee' => __('Employee'),
      'status' => __('Status'),
      'bookableColor' => __('Color'),
    );
    return $labels;
  }

}
