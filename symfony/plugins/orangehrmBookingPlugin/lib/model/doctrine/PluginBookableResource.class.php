<?php

/**
 * PluginBookableResource
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginBookableResource extends BaseBookableResource {

  const STATUS_ACTIVE = 1;
  const STATUS_INACTIVE = 0;

  /**
   *
   * @return type
   */
  public function getEmployeeId() {
    return $this->getEmployee()->getEmployeeId();
  }

  /**
   *
   * @return type
   */
  public function getEmployeeName() {
    return $this->getEmployee()->getFullName();
  }

  /**
   *
   * @return type
   */
  public function getStatus() {
    $status = $this->isActive == self::STATUS_ACTIVE ? __('Active') : __('Inactive');
    return $status;
  }

  /**
   *
   * @return type
   */
  public function getWorkShifts() {
    $employee = $this->getEmployee();
    $businessHours = BusinessBookingPluginService::getEmployeeBusinessHoursForCalendar($employee);
    if (empty($businessHours)) {
      $businessHours = BusinessBookingPluginService::getDefaultEmployeeBusinessHoursForCalendar();
    }
    return $businessHours;
  }

  /**
   *
   * @return type
   */
  public function getResourceIsActive() {
    return $this->isActive == self::STATUS_ACTIVE ? true : false;
  }

  /**
   *
   * @return type
   */
  public function getBookableAsCalendarResource() {
    return array(
      'id' => $this->bookableId,
      'title' => $this->getEmployeeName(),
      'businessHours' => $this->getWorkShifts(),
      'empNum' => $this->getEmployeeId(),
      'isActive' => $this->getResourceIsActive(),
    );
  }

}
