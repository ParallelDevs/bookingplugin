<?php

/**
 * Description of BookableResourceForm
 *
 * @author amora
 */
class BookableResourceForm extends sfForm {

  private $bookableService;
  private $bookableResource;

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
    $bookableId = $this->getOption('bookableId');
    if (!empty($bookableId)) {
      $this->bookableResource = $this->getBookableService()->getBookableResourceById($bookableId);
    }
    else {
      $this->bookableResource = new BookableResource();
      $this->bookableResource->setIsActive(BookableResource::STATUS_ACTIVE);
    }


    $this->setWidgets($this->getWidgets());
    $this->setValidators($this->getValidators());

    $this->setDefault('status', $this->bookableResource->isActive);

    //$formExtension = PluginFormMergeManager::instance();
    //$formExtension->mergeForms($this, 'addBookableResource', '', 'saveBookableResource', 'BookableResourceForm');
    $this->getWidgetSchema()->setLabels($this->getFormLabels());
  }

  /**
   *
   * @return \BookableResource
   */
  public function getBookableResource() {
    $posts = $this->getValues();
    try {
      $bookable = !empty($posts['bookableId']) ? $this->getBookableService()->getBookableResourceById($posts['bookableId']) : new BookableResource();
    }
    catch (Exception $e) {
      $bookable = new BookableResource();
      sfContext::getInstance()->getLogger()->err($e->getMessage());
    }
    $bookable->setEmpNumber($posts['empNum']);
    $bookable->setIsActive($posts['status']);
    $bookable->setBookableColor($posts['bookableColor']);
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
      'bookableId' => new sfWidgetFormInputHidden(array(), array('value' => $this->bookableResource->bookableId)),
      'employee' => new ohrmWidgetEmployeeNameAutoFill(array('loadingMethod' => 'ajax')),
      'empNum' => new sfWidgetFormInputHidden(array(), array('value' => $this->bookableResource->empNumber)),
      'status' => new sfWidgetFormSelect(array('choices' => $status), array('value' => $this->bookableResource->isActive, "class" => "formInputText editable")),
      'bookableColor' => new sfWidgetFormInputText(array(), array('value' => $this->bookableResource->bookableColor, 'class' => 'editable')),
    );
  }

  /**
   *
   * @return type
   */
  protected function getValidators() {
    return array(
      'bookableId' => new sfValidatorDoctrineChoice(array('model' => 'BookableResource', 'required' => false)),
      'employee' => new sfValidatorString(array('required' => false)),
      'empNum' => new sfValidatorString(array('required' => false)),
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
