<?php

/**
 * Description of AddBookingForm
 *
 * @author amora
 */
class AddBookingForm extends sfForm {

  private $bookingService;
  private $bookableService;

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
    }
    return $this->bookingService;
  }

  /**
   *
   * @param BookableResourceService $bookableService
   */
  public function setBookableService(BookableResourceService $bookableService) {
    $this->bookableService = $bookableService;
  }

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
   */
  public function configure() {
    $this->setWidgets($this->getWidgets());
    $this->setValidators($this->getValidators());

    $customerId = $this->getValue('customerId');
    if (!empty($customerId)) {
      $this->setWidget('projectId', new sfWidgetFormDoctrineChoice($this->getProjectOptions($customerId)));
    }
    $this->getValidatorSchema()->setPostValidator($this->getPostValidator());

    $formExtension = PluginFormMergeManager::instance();
    $formExtension->mergeForms($this, 'addBooking', 'AddBookingForm');
    $this->getWidgetSchema()->setLabels($this->getFormLabels());
  }

  /**
   *
   * @return \Booking
   */
  public function getBooking() {
    $posts = $this->getValues();
    $startDate = date('Y-m-d', strtotime($posts['startAt']));
    $endDate = date('Y-m-d', strtotime($posts['endAt']));
    $booking = new Booking();
    $booking->bookableId = $posts['bookableId'];
    $booking->projectId = $posts['projectId'];
    $booking->customerId = $posts['customerId'];
    $booking->startAt = $posts['startAt'];
    $booking->endAt = $posts['endAt'];
    $booking->allDay = isset($posts['allDay']) && !empty($posts['allDay']) ? Booking::ALL_DAY_ON : Booking::ALL_DAY_OFF;
    $booking->availableOn = Booking::calculateAvailibity($startDate, $endDate);
    return $booking;
  }

  /**
   *
   * @return type
   */
  public function save() {
    $booking = $this->getBooking();
    $service = $this->getBookingService();
    $service->saveBooking($booking);
    $bookingId = $booking->bookingId;
    return $bookingId;
  }

  /**
   *
   * @return type
   */
  protected function getWidgets() {
    return array(
      'bookableId' => new sfWidgetFormDoctrineChoice($this->getBookableOptions(), array()),
      'customerId' => new sfWidgetFormDoctrineChoice($this->getCustomerOptions(), array()),
      'projectId' => new sfWidgetFormChoice(array('choices' => array()), array()),
      'startAt' => new sfWidgetFormInputText(array(), array()),
      'endAt' => new sfWidgetFormInputText(array(), array()),
      'allDay' => new sfWidgetFormInputCheckbox(array(), array()),
    );
  }

  /**
   *
   * @return type
   */
  protected function getValidators() {
    return array(
      'bookableId' => new sfValidatorDoctrineChoice(array('model' => 'BookableResource')),
      'customerId' => new sfValidatorDoctrineChoice(array('model' => 'Customer')),
      'projectId' => new sfValidatorDoctrineChoice(array('model' => 'Project')),
      'startAt' => new sfValidatorDateTime(array('required' => true), array()),
      'endAt' => new sfValidatorDateTime(array('required' => true), array()),
      'allDay' => new sfValidatorBoolean(array(), array()),
    );
  }

  /**
   *
   * @return \sfValidatorAnd
   */
  protected function getPostValidator() {
    return new sfValidatorAnd(array(
      new sfValidatorSchemaCompare('startAt', sfValidatorSchemaCompare::LESS_THAN_EQUAL, 'endAt', array(
          ), array(
        'invalid' => 'The start ("%left_field%") must be before the end  ("%right_field%")',
          )),
    ));
  }

  /**
   *
   * @return array
   */
  protected function getFormLabels() {
    return array(
      'bookableId' => __('Resource'),
      'customerId' => __('Customer'),
      'projectId' => __('Project'),
      'startAt' => __('From'),
      'endAt' => __('To'),
      'allDay' => __('All Day'),
    );
  }

  /**
   *
   * @return type
   */
  protected function getBookableOptions() {
    return array(
      'model' => 'BookableResource',
      'add_empty' => true,
      'method' => 'getEmployeeName',
    );
    ;
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

  protected function getProjectOptions($customerId) {
    $query = Doctrine::getTable('Project')
        ->createQuery()
        ->where('customer_id = ?', $customerId);
    return array(
      'model' => 'Project',
      'add_empty' => true,
      'method' => 'getName',
      'query' => $query,
    );
  }

}
