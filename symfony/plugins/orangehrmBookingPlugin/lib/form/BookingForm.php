<?php

/**
 * Description of AddBookingFormModal
 *
 * @author amora
 */
class BookingForm extends sfForm {

    private $bookingService;
    private $booking;
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
        $this->loadFromOptions();

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

        try {
            $booking = !empty($posts['bookingId']) ? $this->getBookingService()->getBooking($posts['bookingId']) : new Booking();
        }
        catch (Exception $e) {
            $booking = new Booking();
            sfContext::getInstance()->getLogger()->err($e->getMessage());
        }

        $booking->setBookableId($posts['bookableId']);
        $booking->setCustomerId($posts['customerId']);
        $booking->setProjectId($posts['projectId']);
        $booking->setDuration($posts['duration']);
        $booking->setBookingType($posts['bookingType']);
        $booking->setStartDate($posts['startDate']);
        $booking->setEndDate($posts['endDate']);
        $booking->setStartTime($posts['startTime']);
        $booking->setEndTime($posts['endTime']);
        $booking->setAvailableOn($posts['availableOn']);
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
     * {@inheritDoc}
     */
    public function getValues() {
        $values = parent::getValues();

        switch ($values['bookingType']) {
            case Booking::BOOKING_TYPE_HOURS:
                $values['duration'] = Booking::calculateDurationHours($values['hours'], $values['minutes']);
                $startTime = $this->getBookingService()
                    ->getBookableNextAvailableStartTime($values['bookableId'], $values['startDate']);
                $values['startTime'] = (null !== $startTime ) ? $startTime : $values['minStartTime'];
                $values['endTime'] = Booking::calculateEndTimeOfHours($values['startTime'], $values['hours'], $values['minutes']);
                break;
            case Booking::BOOKING_TYPE_SPECIFIC_TIME:
                $values['duration'] = Booking::calculateDurationSpecificTime($values['startTime'], $values['endTime']);
                break;
            default :
                $values['duration'] = 0;
                $values['startTime'] = $values['endTime'] = date('H:i:s');
                break;
        }

        $start = $values['starDate'] . ' ' . $values['startTime'];
        $end = $values['endDate'] . ' ' . $values['endTime'];
        $values['availableOn'] = Booking::calculateAvailibity($start, $end);
        return $values;
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
     */
    protected function loadFromOptions() {
        $bookableSelectable = $this->getOption('bookableSelectable');
        $this->bookableSelectable = (!empty($bookableSelectable) &&
            true === $bookableSelectable) ? true : false;

        $bookingId = $this->getOption('bookingId');

        try {
            $booking = !empty($bookingId) ? $this->getBookingService()->getBooking($bookingId) : null;
            if (null === $booking) {
                $booking = new Booking();
                $booking->setBookingType(Booking::BOOKING_TYPE_HOURS);
            }
        }
        catch (Exception $e) {
            $booking = new Booking();
            $booking->setBookingType(Booking::BOOKING_TYPE_HOURS);
            sfContext::getInstance()->getLogger()->err($e->getMessage());
        }

        $this->booking = $booking;
    }

    /**
     *
     * @return type
     */
    protected function getWidgets() {

        $widgets = array(
          'bookingId' => new sfWidgetFormInputHidden(array(), array('value' => $this->booking->getBookingId())),
          'duration' => new sfWidgetFormInputHidden(array(), array()),
          'bookingType' => new sfWidgetFormInputHidden(array(), array('value' => $this->booking->getBookingType())),
          'minStartTime' => new sfWidgetFormInputHidden(),
          'bookableId' =>
          $this->bookableSelectable ?
          new sfWidgetFormDoctrineChoice($this->getBookableOptions(), array()) :
          new sfWidgetFormInputHidden(array(), array('value' => $this->booking->getBookableId())),
          'bookableName' => $this->bookableSelectable ?
          new sfWidgetFormInputHidden(array(), array()) :
          new sfWidgetFormInputText(array(), array('class' => 'text-read-only', 'readonly' => true, $this->booking->getBookableResource()->getEmployeeName())),
          'startDate' => new sfWidgetFormInputText(array(), array('class' => 'input-date', 'value' => $this->booking->getStartDate())),
          'endDate' => new sfWidgetFormInputText(array(), array('class' => 'input-date', 'value' => $this->booking->getEndDate())),
          'customerId' => new sfWidgetFormDoctrineChoice($this->getCustomerOptions(), array('value' => $this->booking->getCustomerId())),
          'projectId' => new sfWidgetFormChoice(array('choices' => array()), array()),
          'hours' => new sfWidgetFormInputText(array(), array('class' => 'input-hours', 'value' => $this->booking->getHours())),
          'minutes' => new sfWidgetFormInputText(array(), array('class' => 'input-minutes', 'value' => $this->booking->getMinutes())),
          'startTime' => new sfWidgetFormInputText(array(), array('class' => 'input-time', 'value' => $this->booking->getStartTime())),
          'endTime' => new sfWidgetFormInputText(array(), array('class' => 'input-time', 'value' => $this->booking->getEndTime())),
        );
        return $widgets;
    }

    protected function getValidators() {
        $validators = array(
          'bookingId' => new sfValidatorString(array('required' => false)),
          'duration' => new sfValidatorNumber(array('required' => false), array()),
          'bookingType' => new sfValidatorInteger(array('required' => true), array()),
          'minStartTime' => new sfValidatorTime(),
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
          'minStartTime' => __('Minimum Start Time'),
        );
        return $labels;
    }

}
