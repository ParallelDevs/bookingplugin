<?php

/**
 * Booking
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    orangehrm
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Booking extends PluginBooking {

  const ALL_DAY_ON = 1;
  const ALL_DAY_OFF = 0;

  private $configBookingService;

  /**
   *
   * @return type
   */
  public function getEventStart() {
    $time = strtotime($this->startAt);
    $date = date('c', $time);
    return $date;
  }

  /**
   *
   * @return type
   */
  public function getEventEnd() {
    $time = strtotime($this->endAt);
    $date = date('c', $time);
    return $date;
  }

  /**
   *
   * @return type
   */
  public function getIsFullDay() {
    return $this->fullDay == self::ALL_DAY_ON ? true : false;
  }

  /**
   *
   * @return type
   */
  public function getTitle() {
    return $this->getProject()->getName();
  }

  /**
   *
   * @return type
   */
  public function getCustomerName() {
    return $this->getCustomer()->getName();
  }

  /**
   *
   * @param type $startDate
   * @param type $endDate
   * @return string
   */
  public static function calculateAvailibity($startDate, $endDate) {
    $availablePeriod = array();
    $start = strtotime($startDate);
    $end = strtotime($endDate);
    $diff = floor(($end - $start) / 86400);
    $weeks = floor($diff / 7);
    $days = $diff % 7;

    $date = $start;
    for ($i = 0; $i < $weeks; $i++) {
      $period = date('Y-m', $date);
      if (!in_array($period, $availablePeriod)) {
        array_push($availablePeriod, $period);
      }
      $date = strtotime("+ 1 week", $date);
    }
    for ($i = 0; $i < $days; $i++) {
      $period = date('Y-m', $date);
      if (!in_array($period, $availablePeriod)) {
        array_push($availablePeriod, $period);
      }
      $date = strtotime("+ 1 day", $date);
    }
    $date = $end;
    $period = date('Y-m', $date);
    if (!in_array($period, $availablePeriod)) {
      array_push($availablePeriod, $period);
    }

    return implode(',', $availablePeriod);
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
      $this->configBookingService->setConfigDao(new ConfigDao());
    }
    return $this->configBookingService;
  }

  /**
   *
   * @return type
   */
  public function getBookingAsCalendarEvent() {
    return array(
      'id' => $this->getBookingId(),
      'resourceId' => $this->getBookableId(),
      'title' => $this->getTitle(),
      'start' => $this->getEventStart(),
      'end' => $this->getEventEnd(),
      'customerId' => $this->getCustomerId(),
      'customerName' => $this->getCustomerName(),
      'projectId' => $this->getProjectId(),
      'isHoliday' => false,
      'fullDay' => $this->getIsFullDay(),
    );
  }

}
