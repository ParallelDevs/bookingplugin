<?php

/**
 * Description of BookingForm
 *
 * @author amora
 */
class BookingForm extends sfForm {

    private $bookingService;
    private $configBookingService;
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
     * @param ConfigBookingService $configService
     */
    public function setConfigBookingService(ConfigBookingService $configService) {
        $this->configBookingService = $configService;
    }

    /**
     *
     * @return type
     */
    public function getConfigBookingService() {
        if (!$this->configBookingService instanceof ConfigBookingService) {
            $this->configBookingService = new ConfigBookingService();
        }
        return $this->configBookingService;
    }

    /**
     *
     */
    public function configure() {
        $this->loadFromOptions();

        $this->setWidgets($this->getWidgets());
        $this->setValidators($this->getValidators());
        $this->setDefaults($this->getDefaultValues());
        $this->mergePreValidator(new sfValidatorSchemaCompare('startDate', sfValidatorSchemaCompare::LESS_THAN_EQUAL, 'endDate', array(
            ), array(
          'invalid' => 'The start ("%left_field%") must be set before the end ("%right_field%")',
        )));
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
        $values['duration'] = Booking::calculateDurationHours($values['hours'], $values['minutes']);
        $startTime = $this->getBookingService()
            ->getBookableNextAvailableStartTime($values['bookableId'], $values['startDate']);
        $values['startTime'] = (null !== $startTime ) ? $startTime : $values['minStartTime'];
        $values['endTime'] = Booking::calculateEndTimeOfHours($values['startTime'], $values['hours'], $values['minutes']);
        $start = $values['starDate'] . ' ' . $values['startTime'];
        $end = $values['endDate'] . ' ' . $values['endTime'];
        $values['availableOn'] = Booking::calculateAvailibity($start, $end);
        $projectId = $values['projectId'];
        $values['bookingColor'] = $this->getBookingService()->chooseBookingColor($projectId);
        return $values;
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
        $booking->setStartDate($posts['startDate']);
        $booking->setEndDate($posts['endDate']);
        $booking->setStartTime($posts['startTime']);
        $booking->setEndTime($posts['endTime']);
        $booking->setAvailableOn($posts['availableOn']);
        $booking->setBookingColor($posts['bookingColor']);
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
            }
        }
        catch (Exception $e) {
            $booking = new Booking();
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
          'bookingId' => new sfWidgetFormInputHidden(array(), array()),
          'duration' => new sfWidgetFormInputHidden(array(), array()),
          'minStartTime' => new sfWidgetFormInputHidden(),
          'maxEndTime' => new sfWidgetFormInputHidden(),
          'bookingColor' => new sfWidgetFormInputHidden(),
          'bookableId' =>
          $this->bookableSelectable ?
          new sfWidgetFormDoctrineChoice($this->getBookableOptions(), array()) :
          new sfWidgetFormInputHidden(array(), array('value' => $this->booking->getBookableId())),
          'bookableName' => $this->bookableSelectable ?
          new sfWidgetFormInputHidden(array(), array()) :
          new sfWidgetFormInputText(array(), array('class' => 'text-read-only', 'readonly' => true, 'value' => $this->booking->getBookableResource()->getEmployeeName())),
          'startDate' => new sfWidgetFormInputText(array(), array('class' => 'input-date')),
          'endDate' => new sfWidgetFormInputText(array(), array('class' => 'input-date')),
          'customerId' => new sfWidgetFormDoctrineChoice($this->getCustomerOptions(), array()),
          'projectId' => new sfWidgetFormChoice(array('choices' => array()), array()),
          'hours' => new sfWidgetFormInputText(array(), array('class' => 'input-hours', 'placeholder' => __('Hours'))),
          'minutes' => new sfWidgetFormInputText(array(), array('class' => 'input-minutes', 'placeholder' => __('Minutes'))),
          'startTime' => new sfWidgetFormInputText(array(), array('class' => 'input-time')),
          'endTime' => new sfWidgetFormInputText(array(), array('class' => 'input-time')),
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
          'bookableId' =>
          $this->bookableSelectable ?
          new sfValidatorDoctrineChoice(array('model' => 'BookableResource')) :
          new sfValidatorString(array('required' => false)),
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
        );
        return $defaults;
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
          'minStartTime' => __('Minimum Start Time'),
          'maxEndTime' => __('Maximum End Time'),
          'bookingColor' => __('Color'),
        );
        return $labels;
    }

}
