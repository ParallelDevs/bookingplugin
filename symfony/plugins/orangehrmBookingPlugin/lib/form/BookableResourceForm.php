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
    $this->bookableResource = $this->getBookableService()->getBookableResourceById($bookableId);

    $this->setWidgets($this->getWidgets());
    $this->setValidators($this->getValidators());

    $this->setDefault('status', $this->bookableResource->isActive);

    $formExtension = PluginFormMergeManager::instance();
    $formExtension->mergeForms($this, 'saveBookableResource', 'BookableResourceForm');
    $this->getWidgetSchema()->setLabels($this->getFormLabels());
  }

  /**
   *
   * @return \BookableResource
   */
  public function getBookableResource() {
    $posts = $this->getValues();
    $bookable = $this->getBookableService()->getBookableResourceById($posts['bookableId']);
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
      'empNum' => new sfWidgetFormInputHidden(array(), array('value' => $this->bookableResource->empNumber)),
      'status' => new sfWidgetFormSelect(array('choices' => $status), array("class" => "formInputText editable")),
      'bookableColor' => new sfWidgetFormInputText(array(), array('value' => $this->bookableResource->bookableColor, 'class' => 'editable')),
    );
  }

  /**
   *
   * @return type
   */
  protected function getValidators() {
    return array(
      'bookableId' => new sfValidatorDoctrineChoice(array('model' => 'BookableResource')),
      'empNum' => new sfValidatorString(array('required' => true)),
      'status' => new sfValidatorString(array('required' => true)),
      'bookableColor' => new sfValidatorString(array('required' => false)),
    );
  }

  /**
   *
   * @return array
   */
  protected function getFormLabels() {

    $labels = array(
      'status' => __('Status'),
      'bookableColor' => __('Color'),
    );
    return $labels;
  }

}
