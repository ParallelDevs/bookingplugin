<?php

/**
 * Description of AddBookingFormModal
 *
 * @author amora
 */
class BookingForm extends sfForm {

  private $bookingService;
  private $bookableSelectable = false;

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
    }
    return $this->bookingService;
  }

  /**
   *
   */
  public function configure() {
    $bookableSelectable = $this->getOption('bookableSelectable');
    if (!empty($bookableSelectable) && true === $bookableSelectable) {
      $this->bookableSelectable = true;
    }
    else {
      $this->bookableSelectable = false;
    }

    $this->setWidgets($this->getWidgets());
    $this->setValidators($this->getValidators());
    $this->setDefault('bookingType', Booking::BOOKING_TYPE_HOURS);

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
    $booking->setFullDay($allDay);
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
   * @return type
   */
  protected function getWidgets() {

    $widgets = array(
      'bookingId' => new sfWidgetFormInputHidden(array(), array()),
      'duration' => new sfWidgetFormInputHidden(array(), array()),
      'bookingType' => new sfWidgetFormInputHidden(array(), array()),
      'bookableId' =>
      $this->bookableSelectable ?
      new sfWidgetFormDoctrineChoice($this->getBookableOptions(), array()) :
      new sfWidgetFormInputHidden(array(), array()),
      'bookableName' => $this->bookableSelectable ?
      new sfWidgetFormInputHidden(array(), array()) :
      new sfWidgetFormInputText(array(), array('readonly' => true)),
      'startDate' => new sfWidgetFormInputText(array(), array('class' => 'input-date')),
      'endDate' => new sfWidgetFormInputText(array(), array('class' => 'input-date')),
      'customerId' => new sfWidgetFormDoctrineChoice($this->getCustomerOptions(), array()),
      'projectId' => new sfWidgetFormChoice(array('choices' => array()), array()),
      'hours' => new sfWidgetFormInputText(array(), array('class' => 'input-hours')),
      'minutes' => new sfWidgetFormInputText(array(), array('class' => 'input-minutes')),
      'startTime' => new sfWidgetFormInputText(array(), array('class' => 'input-time')),
      'endTime' => new sfWidgetFormInputText(array(), array('class' => 'input-time')),
    );
    return $widgets;
  }

  protected function getValidators() {
    $validators = array(
      'bookingId' => new sfValidatorString(array('required' => false)),
      'duration' => new sfValidatorNumber(array('required' => false), array()),
      'bookingType' => new sfValidatorInteger(array('required' => true), array()),
      'bookableId' =>
      $this->bookableSelectable ?
      new sfValidatorDoctrineChoice(array('model' => 'BookableResource')) :
      new sfValidatorString(array('required' => false)),
      'bookableName' => new sfValidatorString(array('required' => false)),
      'startDate' => new sfValidatorDate(array('required' => true), array()),
      'endDate' => new sfValidatorDate(array('required' => true), array()),
      'customerId' => new sfValidatorDoctrineChoice(array('model' => 'Customer', 'required' => true)),
      'projectId' => new sfValidatorDoctrineChoice(array('model' => 'Project', 'required' => true)),
      'hours' => new sfValidatorInteger(array('required' => false), array()),
      'minutes' => new sfValidatorInteger(array('required' => false), array()),
      'startTime' => new sfValidatorTime(array('required' => false), array()),
      'endTime' => new sfValidatorTime(array('required' => false), array()),
    );
    return $validators;
  }

  /**
   *
   * @return \sfValidatorAnd
   */
  protected function getPostValidator() {
    $validator = new sfValidatorAnd(array(
      new sfValidatorSchemaCompare('startDate', sfValidatorSchemaCompare::LESS_THAN_EQUAL, 'endDate'),
    ));
    return $validator;
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
      'bookingType' => __('Booking Type'),
    );
    return $labels;
  }

}
