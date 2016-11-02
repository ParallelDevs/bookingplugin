<?php

/**
 * Description of AddBookingFormModal
 *
 * @author amora
 */
class BookingForm extends sfForm {

  private $bookingService;

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
   */
  public function configure() {
    $this->setWidgets($this->getWidgets());
    $this->setValidators($this->getValidators());

    $formExtension = PluginFormMergeManager::instance();
    $formExtension->mergeForms($this, 'updateBooking', 'UpdateBookingForm');
    $this->getWidgetSchema()->setLabels($this->getFormLabels());
  }

  /**
   *
   * @return \Booking
   */
  public function getBooking() {
    $posts = $this->getValues();
    $allDay = isset($posts['allDay']) && !empty($posts['allDay']) ? Booking::ALL_DAY_ON : Booking::ALL_DAY_OFF;
    $startDate = date('Y-m-d', strtotime($posts['startAt']));
    $endDate = date('Y-m-d', strtotime($posts['endAt']));
    $availableOn = Booking::calculateAvailibity($startDate, $endDate);

    try {
      $booking = !empty($posts['bookingId']) ? $this->getBookingService()->getBooking($posts['bookingId']) : new Booking();
    }
    catch (Exception $e) {
      $booking = new Booking();
      sfContext::getInstance()->getLogger()->err($e->getMessage());
    }

    $booking->setBookableId($posts['bookableId']);
    $booking->setProjectId($posts['projectId']);
    $booking->setCustomerId($posts['customerId']);
    $booking->setStartAt($posts['startAt']);
    $booking->setEndAt($posts['endAt']);
    $booking->setAllDay($allDay);
    $booking->setAvailableOn($availableOn);
    return $booking;
  }

  /**
   *
   * @return type
   */
  public function save() {
    $booking = $this->getBooking();
    $service = $this->getBookingService();
    $savedBooking = $service->saveBooking($booking);
    $bookingId = $savedBooking->getBookingId();
    return $bookingId;
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
   * @return array
   */
  protected function getFormLabels() {

    $labels = array(
      'bookingId' => __('Booking'),
      'bookableId' => __('Resource'),
      'bookableName' => __('Resource'),
      'customerId' => __('Customer'),
      'projectId' => __('Project'),
      'startAt' => __('From'),
      'endAt' => __('To'),
      'allDay' => __('All Day'),
    );
    return $labels;
  }

  /**
   *
   * @return type
   */
  protected function getWidgets() {
    return array(
      'bookingId' => new sfWidgetFormInputHidden(array(), array()),
      'bookableId' => new sfWidgetFormInputHidden(array(), array()),
      'bookableName' => new sfWidgetFormInputText(array(), array('readonly' => true)),
      'customerId' => new sfWidgetFormDoctrineChoice($this->getCustomerOptions(), array()),
      'projectId' => new sfWidgetFormChoice(array('choices' => array()), array()),
      'startAt' => new sfWidgetFormInputText(array(), array()),
      'endAt' => new sfWidgetFormInputText(array(), array()),
      'allDay' => new sfWidgetFormInputCheckbox(array(), array('value' => 1)),
    );
  }

  protected function getValidators() {
    return array(
      'bookingId' => new sfValidatorString(array('required' => false)),
      'bookableId' => new sfValidatorDoctrineChoice(array('model' => 'BookableResource', 'required' => true)),
      'bookableName' => new sfValidatorString(array('required' => false)),
      'customerId' => new sfValidatorDoctrineChoice(array('model' => 'Customer', 'required' => true)),
      'projectId' => new sfValidatorDoctrineChoice(array('model' => 'Project', 'required' => true)),
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
   * @return type
   */
  protected function getCustomerOptions() {
    return array(
      'model' => 'Customer',
      'add_empty' => true,
      'method' => 'getName',
    );
  }

}
