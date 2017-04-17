<?php

/**
 * Description of BaseBookingForm
 *
 * @author pdev
 */
class BaseBookingForm extends sfForm {

  protected $bookingService;
  protected $projectService;
  protected $booking;
  protected $isNew;
  protected $bookableSelectable = false;
  protected $bookableName;

  /**
   *
   * @param BookingService $bookingService
   */
  public function setBookingService(BookingService $bookingService) {
    $this->bookingService = $bookingService;
  }

  /**
   *
   * @return type
   */
  public function getBookingService() {
    if (!$this->bookingService instanceof BookingService) {
      $this->bookingService = new BookingService();
      $this->bookingService->setBookingDao(new BookingDao());
    }
    return $this->bookingService;
  }

  /**
   *
   * @param ProjectService $projectService
   */
  public function setProjectService(ProjectService $projectService) {
    $this->projectService = $projectService;
  }

  /**
   *
   * @return type
   */
  public function getProjectService() {
    if (!$this->projectService instanceof ProjectService) {
      $this->projectService = new ProjectService();
      $this->projectService->setProjectDao(new ProjectDao());
    }
    return $this->projectService;
  }

  /**
   *
   * @return type
   */
  public function getErrors() {
    return $this->getErrorSchema()->getErrors();
  }

  /**
   *
   * @return type
   */
  protected function getWidgets() {
    $widgets = array(
      'bookingId' => new sfWidgetFormInputHidden(array(), array()),
      'duration' => new sfWidgetFormInputHidden(array(), array()),
      'minStartTime' => new sfWidgetFormInputHidden(),
      'maxEndTime' => new sfWidgetFormInputHidden(),
      'bookingColor' => new sfWidgetFormInputHidden(),
      'startTime' => new sfWidgetFormInputHidden(),
      'endTime' => new sfWidgetFormInputHidden(),
      'workingDays' => new sfWidgetFormInputHidden(),
      'bookableId' =>
      $this->bookableSelectable ?
      new sfWidgetFormDoctrineChoice($this->getBookableOptions(), array()) :
      new sfWidgetFormInputHidden(array(), array()),
      'bookableName' => $this->bookableSelectable ?
      new sfWidgetFormInputHidden(array(), array()) :
      new sfWidgetFormInputText(array(), array('class' => 'text-read-only', 'readonly' => true,)),
      'startDate' => new sfWidgetFormInputText(array(), array('class' => 'input-date')),
      'endDate' => new sfWidgetFormInputText(array(), array('class' => 'input-date')),
      'customerId' => new sfWidgetFormDoctrineChoice($this->getCustomerOptions(), array()),
      'projectId' => new sfWidgetFormDoctrineChoice($this->getProjectOptions(), array()),
      'hours' => new sfWidgetFormInputText(array(), array('class' => 'input-hours', 'placeholder' => __('Hours'))),
      'minutes' => new sfWidgetFormInputText(array(), array('class' => 'input-minutes', 'placeholder' => __('Minutes'))),
    );
    return $widgets;
  }

  /**
   *
   * @return type
   */
  protected function getValidators() {
    $validators = array(
      'bookingId' => new sfValidatorString(array('required' => false)),
      'duration' => new sfValidatorNumber(array('required' => false), array()),
      'minStartTime' => new sfValidatorTime(array('required' => false)),
      'maxEndTime' => new sfValidatorTime(array('required' => false)),
      'bookingColor' => new sfValidatorString(array('required' => false)),
      'workingDays' => new sfValidatorString(array('required' => false)),
      'bookableId' =>
      $this->bookableSelectable ?
      new sfValidatorDoctrineChoice(array('model' => 'BookableResource', 'required' => true)) :
      new sfValidatorString(array('required' => true)),
      'bookableName' => new sfValidatorString(array('required' => false)),
      'startDate' => new sfValidatorDate(array('required' => true), array()),
      'endDate' => new sfValidatorDate(array('required' => true), array()),
      'customerId' => new sfValidatorDoctrineChoice(array('model' => 'Customer', 'required' => true)),
      'projectId' => new sfValidatorDoctrineChoice(array('model' => 'Project', 'required' => true)),
      'hours' => new sfValidatorInteger(array('required' => false, 'empty_value' => 0, 'min' => 0), array()),
      'minutes' => new sfValidatorInteger(array('required' => false, 'empty_value' => 0, 'min' => 0, 'max' => 59), array()),
      'startTime' => new sfValidatorTime(array('required' => false), array()),
      'endTime' => new sfValidatorTime(array('required' => false), array()),
    );
    return $validators;
  }

  /**
   *
   * @return array
   */
  protected function getFormLabels() {

    $labels = array(
      'bookingId' => __('Booking'),
      'bookableId' => __('Resource'),
      'bookableName' => __('Resource'),
      'customerId' => __('Customer'),
      'projectId' => __('Project'),
      'duration' => __('Duration'),
      'hours' => __('Hours'),
      'startDate' => __('From'),
      'endDate' => __('To'),
      'startTime' => __('Start At'),
      'endTime' => __('Up To'),
      'minStartTime' => __('Minimum Start Time'),
      'maxEndTime' => __('Maximum End Time'),
      'bookingColor' => __('Color'),
      'workingDays' => __('Working Days'),
    );
    return $labels;
  }

  /**
   *
   * @return type
   */
  protected function getBookableOptions() {
    $query = Doctrine_Query::create()
        ->select('*')
        ->from('BookableResource')
        ->where('is_active = ?', BookableResource::STATUS_ACTIVE);

    return array(
      'model' => 'BookableResource',
      'add_empty' => true,
      'method' => 'getEmployeeName',
      'query' => $query,
    );
  }

  /**
   *
   * @return type
   */
  protected function getCustomerOptions() {
    return array(
      'model' => 'Customer',
      'add_empty' => true,
      'method' => 'getName',
    );
  }

  /**
   *
   * @return array
   */
  protected function getProjectOptions() {
    $options = array(
      'model' => 'Project',
      'add_empty' => true,
      'method' => 'getName',
    );

    $customerId = $this->booking->getCustomerId();
    if (!empty($customerId)) {
      $options['query'] = Doctrine_Query::create()
          ->select('*')
          ->from('Project')
          ->where('customer_id = ?', $customerId);
    }

    return $options;
  }

}
