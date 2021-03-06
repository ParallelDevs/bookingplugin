<?php

/**
 * Description of BookingService
 *
 * @author amora
 */
class BookingService extends BaseService {

  private $bookingDao;

  /**
   *
   */
  public function __construct() {
    
  }

  /**
   *
   * @param BookingDao $bookingDao
   */
  public function setBookingDao(BookingDao $bookingDao) {
    $this->bookingDao = $bookingDao;
  }

  /**
   *
   * @return type
   */
  public function getBookingDao() {
    if (!$this->bookingDao instanceof BookingDao) {
      $this->bookingDao = new BookingDao();
    }
    return $this->bookingDao;
  }

  /**
   *
   * @param Booking $booking
   * @return type
   */
  public function saveBooking(Booking $booking) {
    return $this->getBookingDao()->saveBooking($booking);
  }

  /**
   *
   * @param type $id
   * @return type
   */
  public function getBooking($id) {
    return $this->getBookingDao()->getBookingById($id);
  }

  /**
   *
   * @param BookingSearchParameterHolder $parameterHolder
   * @return type
   */
  public function searchBookingsList(BookingSearchParameterHolder $parameterHolder) {
    return $this->getBookingDao()->searchBooking($parameterHolder);
  }

  /**
   *
   * @param type $bookableId
   * @param type $date
   * @return type
   */
  public function getBookableNextAvailableStartTime($bookableId, $date) {
    return $this->getBookingDao()->getBookableNextAvailableStartTime($bookableId, $date);
  }

  /**
   * 
   * @param type $projectId
   * @return type
   */
  public function getBookingColorByProjectId($projectId) {
    return $this->getBookingDao()->getBookingColorByProjectId($projectId);
  }

  /**
   * 
   * @param type $color
   * @return type
   */
  public function getProjectIdByBookingColor($color) {
    return $this->getBookingDao()->getProjectIdByBookingColor($color);
  }

  /**
   * 
   * @param type $projectId
   * @return string
   */
  public function chooseBookingColor($projectId) {
    $bookingColor = '';
    $result = $this->getBookingColorByProjectId($projectId);
    $color = reset($result);
    if ($color !== false && !empty($color)) {
      $bookingColor = reset($color);
    }
    else {
      $counter = 0;
      $colorIsUsed = true;
      while ($colorIsUsed && $counter < 11) {
        $bookingColor = '#' . dechex(rand(0x000000, 0xFFFFFF));
        $project = $this->getProjectIdByBookingColor($bookingColor);
        $colorIsUsed = empty($project) ? false : true;
        $counter++;
      }
    }

    return $bookingColor;
  }

  /**
   * 
   * @param type $id
   * @return type
   */
  public function deleteBooking($id) {
    return $this->getBookingDao()->deleteBookingById($id);
  }

}
