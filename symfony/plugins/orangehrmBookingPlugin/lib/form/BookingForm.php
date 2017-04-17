<?php

/**
 * Description of BookingForm
 *
 * @author amora
 */
class BookingForm extends BaseBookingForm {

  protected $minStartTime = '';
  protected $maxEndTime = '';
  protected $workingDays = '';

  /**
   *
   */
  public function configure() {
    $this->loadFromOptions();

    $this->setWidgets($this->getWidgets());
    $this->setValidators($this->getValidators());
    $this->setDefaults($this->getDefaultValues());
    $this->mergePreValidator(new sfValidatorSchemaCompare('endDate', sfValidatorSchemaCompare::GREATER_THAN_EQUAL, 'startDate', array(), array()));
    $this->mergePostValidator(new sfValidatorCallback(array(
      'callback' => array($this, 'validateBooking'),
    )));
    $this->getWidgetSchema()->setLabels($this->getFormLabels());
  }

  /**
   *
   * @param sfValidatorBase $validator
   * @param array $values
   * @param array $arguments
   * @return type
   */
  public function validateBooking(sfValidatorBase $validator, array $values, array $arguments) {
    $this->isNew = empty($values['bookingId']) ? true : false;

    $this->vallidateBookingDuration($values);
    $this->validateBookingProject($values);

    if (!$this->isNew) {
      $start = $values['starDate'] . ' ' . $values['startTime'];
      $end = $values['endDate'] . ' ' . $values['endTime'];
      $values['availableOn'] = Booking::calculateAvailibity($start, $end);
    }

    return $values;
  }

  /**
   *
   * @return \Booking
   */
  public function getBooking() {
    $posts = $this->getValues();

    try {
      $booking = !$this->isNew ? $this->getBookingService()->getBooking($posts['bookingId']) : new Booking();
    }
    catch (Exception $e) {
      $this->isNew = true;
      $booking = new Booking();
      sfContext::getInstance()->getLogger()->err($e->getMessage());
    }

    if (!$this->isNew) {
      $this->fillBooking($booking, $posts);
      return $booking;
    }
    else {
      $result = $this->loadBookingCollection($posts);
      return $result;
    }
  }

  /**
   *
   * @return type
   */
  public function save() {
    $booking = $this->getBooking();
    if ($booking instanceof Booking) {
      $this->saveSingle($booking);
    }
    else if (is_array($booking)) {
      $this->saveMultiple($booking);
    }
    return true;
  }

  /**
   *
   */
  protected function loadFromOptions() {
    $bookableSelectable = $this->getOption('bookableSelectable');
    $this->bookableSelectable = (!empty($bookableSelectable) &&
        true === $bookableSelectable) ? true : false;

    $bookingId = $this->getOption('bookingId');
    $this->isNew = empty($bookingId) ? true : false;
    try {
      $booking = !$this->isNew ? $this->getBookingService()->getBooking($bookingId) : null;
      if (null === $booking) {
        $booking = new Booking();
        $this->bookableName = $this->getOption('bookableName');
        $booking->setBookableId($this->getOption('bookableId'));
      }
      else {
        $this->bookableName = $booking->getBookableResource()->getEmployeeName();
      }

      $this->minStartTime = $this->getOption('minStartTime');
      $this->maxEndTime = $this->getOption('maxEndTime');
      $this->workingDays = $this->getOption('workingDays');
    }
    catch (Exception $e) {
      $booking = new Booking();
      $this->isNew = true;
      sfContext::getInstance()->getLogger()->err($e->getMessage());
    }

    $booking->setStartDate($this->getOption('startDate'));
    $booking->setEndDate($this->getOption('endDate'));

    $this->booking = $booking;
  }

  /**
   *
   * @return array
   */
  protected function getDefaultValues() {
    $defaults = array(
      'bookingId' => $this->booking->getBookingId(),
      'startDate' => $this->booking->getStartDate(),
      'endDate' => $this->booking->getEndDate(),
      'customerId' => $this->booking->getCustomerId(),
      'projectId' => $this->booking->getProjectId(),
      'hours' => $this->booking->getHours(),
      'minutes' => $this->booking->getMinutes(),
      'startTime' => $this->booking->getStartTime(),
      'endTime' => $this->booking->getEndTime(),
      'bookingColor' => $this->booking->getBookingColor(),
      'bookableId' => $this->booking->getBookableId(),
      'bookableName' => $this->bookableName,
      'minStartTime' => $this->minStartTime,
      'maxEndTime' => $this->maxEndTime,
      'workingDays' => $this->workingDays,
    );
    return $defaults;
  }

  /**
   *
   * @param type $booking
   * @param type $values
   */
  protected function fillBooking(&$booking, &$values) {
    $booking->setBookableId($values['bookableId']);
    $booking->setCustomerId($values['customerId']);
    $booking->setProjectId($values['projectId']);
    $booking->setDuration($values['duration']);
    $booking->setStartDate($values['startDate']);
    $booking->setEndDate($values['endDate']);
    $booking->setStartTime($values['startTime']);
    $booking->setEndTime($values['endTime']);
    $booking->setAvailableOn($values['availableOn']);
    $booking->setBookingColor($values['bookingColor']);
  }

  /**
   *
   * @param type $values
   * @return array
   */
  protected function loadBookingCollection(&$values) {
    $collection = array();
    $workingDays = explode(',', $values['workingDays']);
    $holidays = BusinessBookingPluginService::getHolidaysAsArray($values['startDate'], $values['endDate']);
    $dates = Booking::calculateBookingPeriods($values['startDate'], $values['endDate'], $workingDays, $holidays);

    foreach ($dates as $week) {
      $start = $week['startDate'] . ' ' . $values['startTime'];
      $end = $week['endDate'] . ' ' . $values['endTime'];
      $availableOn = Booking::calculateAvailibity($start, $end);
      $values['startDate'] = $week['startDate'];
      $values['endDate'] = $week['endDate'];
      $values['availableOn'] = $availableOn;

      $booking = new Booking();
      $this->fillBooking($booking, $values);
      array_push($collection, $booking);
    }
    return $collection;
  }

  /**
   *
   * @param Booking $booking
   * @return type
   */
  protected function saveSingle(Booking $booking) {
    $service = $this->getBookingService();
    $savedBooking = $service->saveBooking($booking);
    $bookingId = $savedBooking->getBookingId();
    return $bookingId;
  }

  /**
   *
   * @param type $collection
   */
  protected function saveMultiple($collection) {
    $service = $this->getBookingService();
    foreach ($collection as $booking) {
      $service->saveBooking($booking);
    }
  }

  /**
   *
   * @param array $values
   */
  protected function vallidateBookingDuration(array &$values) {
    if (empty($values['hours']) && empty($values['minutes'])) {
      if (empty($values['startTime'])) {
        $startTime = $this->getBookingService()
            ->getBookableNextAvailableStartTime($values['bookableId'], $values['startDate']);
        $values['startTime'] = (null !== $startTime ) ? $startTime : $values['minStartTime'];
      }
      $values['endTime'] = $values['maxEndTime'];
      $values['duration'] = Booking::calculateDurationTimes($values['startTime'], $values['endTime']);
    }
    else {
      $values['duration'] = Booking::calculateDurationHours($values['hours'], $values['minutes']);

      if (empty($values['startTime'])) {
        $startTime = $this->getBookingService()
            ->getBookableNextAvailableStartTime($values['bookableId'], $values['startDate']);
        $values['startTime'] = (null !== $startTime ) ? $startTime : $values['minStartTime'];
      }
      $values['endTime'] = Booking::calculateEndTimeOfHours($values['startTime'], $values['hours'], $values['minutes']);
    }
  }

  /**
   *
   * @param array $values
   */
  protected function validateBookingProject(array &$values) {
    $projectId = $values['projectId'];
    $currentProjectId = $this->booking->getProjectId();
    if (empty($values['bookingColor']) || ($currentProjectId != $projectId)) {
      $values['bookingColor'] = $this->getBookingService()->chooseBookingColor($projectId);
    }
  }

}
