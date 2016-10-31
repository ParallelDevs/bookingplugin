<?php

/**
 * Description of AddBookingFormModal
 *
 * @author amora
 */
class AddBookingFormModal extends sfForm {

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
    $formExtension->mergeForms($this, 'addBooking', 'AddBookingFormModal');
    $this->getWidgetSchema()->setLabels($this->getFormLabels());
  }

  /**
   *
   * @return \Booking
   */
  public function getBooking() {
    $posts = $this->getValues();
    $booking = new Booking();
    $booking->bookableId = $posts['bookableId'];
    $booking->projectId = $posts['projectId'];
    $booking->customerId = $posts['customerId'];
    $booking->startDate = $posts['startDate'];
    $booking->endDate = $posts['endDate'];
    $booking->allDay = isset($posts['allDay']) && !empty($posts['allDay']) ? Booking::ALL_DAY_ON : Booking::ALL_DAY_OFF;
    $booking->availableOn = Booking::calculateAvailibity($posts['startDate'], $posts['endDate']);
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
      'bookableName' => __('Resource'),
      'customerId' => __('Customer'),
      'projectId' => __('Project'),
      'startDate' => __('From Date'),
      'endDate' => __('To'),
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
    $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();

    return array(
      'bookableId' => new sfValidatorDoctrineChoice(array('model' => 'BookableResource')),
      'bookableName' => new sfValidatorString(array('required' => false)),
      'customerId' => new sfValidatorDoctrineChoice(array('model' => 'Customer')),
      'projectId' => new sfValidatorDoctrineChoice(array('model' => 'Project')),
      'startAt' => new sfValidatorDateTime(array('date_format' => $inputDatePattern, 'required' => true), array('invalid' => "Date format should be $inputDatePattern")),
      'endAt' => new sfValidatorDateTime(array('date_format' => $inputDatePattern, 'required' => true), array('invalid' => "Date format should be $inputDatePattern")),
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
