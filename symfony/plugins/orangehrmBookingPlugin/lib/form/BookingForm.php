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
    $availableOn = Booking::calculateAvailibity($posts['startDate'], $posts['endDate']);

    $booking = $this->getBookingService()->getBooking($posts['bookingId']);
    $booking->setProjectId($posts['projectId']);
    $booking->setCustomerId($posts['customerId']);
    $booking->setStartDate($posts['startDate']);
    $booking->setEndDate($posts['endDate']);
    $booking->setStartTime($posts['startTime']);
    $booking->setEndTime($posts['endTime']);
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
      'customerId' => __('Customer'),
      'projectId' => __('Project'),
      'startDate' => __('From Date'),
      'endDate' => __('To'),
      'startTime' => __('Starts At'),
      'endTime' => __('To'),
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
      'startDate' => new ohrmWidgetDatePicker(array(), array()),
      'endDate' => new ohrmWidgetDatePicker(array(), array()),
      'allDay' => new sfWidgetFormInputCheckbox(array(), array('value' => 1)),
      'startTime' => new sfWidgetFormInputText(array(), array()),
      'endTime' => new sfWidgetFormInputText(array(), array()),
    );
  }

  protected function getValidators() {
    $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();


    return array(
      'bookingId' => new sfValidatorDoctrineChoice(array('model' => 'Booking')),
      'bookableId' => new sfValidatorDoctrineChoice(array('model' => 'BookableResource')),
      'customerId' => new sfValidatorDoctrineChoice(array('model' => 'Customer')),
      'projectId' => new sfValidatorDoctrineChoice(array('model' => 'Project')),
      //'startDate' => new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => true), array('invalid' => "Date format should be $inputDatePattern")),
      'endDate' => new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => true), array('invalid' => "Date format should be $inputDatePattern")),
      'startTime' => new sfValidatorTime(array('required' => true)),
      'endTime' => new sfValidatorTime(array('required' => true)),
      'allDay' => new sfValidatorBoolean(array(), array()),
      'startDate' => new sfValidatorDateTime(array())
    );
  }

  /**
   *
   * @return \sfValidatorAnd
   */
  protected function getPostValidator() {
    return new sfValidatorAnd(array(
      new sfValidatorSchemaCompare('startDate', sfValidatorSchemaCompare::LESS_THAN_EQUAL, 'endDate', array(
          ), array(
        'invalid' => 'The start date ("%left_field%") must be before the end date ("%right_field%")',
          )),
      new sfValidatorSchemaCompare('startTime', sfValidatorSchemaCompare::LESS_THAN_EQUAL, 'endTime', array(
          ), array(
        'invalid' => 'The start time ("%left_field%") must be before the end time ("%right_field%")',
          )),
    ));
  }

}
