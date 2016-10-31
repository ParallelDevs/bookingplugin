<?php

/**
 * Description of BaseBookinPluginService
 *
 * @author amora
 */
class BusinessBookingPluginService extends BaseService {

  /**
   *
   * @return \WorkShiftService
   */
  public static function getWorkShiftService() {
    $workshiftService = new WorkShiftService();
    $workshiftService->setWorkShiftDao(new WorkShiftDao());

    return $workshiftService;
  }

  /**
   *
   * @return \WorkWeekService
   */
  public static function getWorkWeekService() {
    $workWeekService = new WorkWeekService();
    $workWeekService->setWorkWeekDao(new WorkWeekDao());

    return $workWeekService;
  }

  /**
   *
   * @return \HolidayService
   */
  public static function getHolidayService() {
    $holidayService = new HolidayService();
    $holidayService->setHolidayDao(new HolidayDao());

    return $holidayService;
  }

  /**
   *
   * @return array
   */
  public static function getCompanyBusinessHoursForCalendar() {
    $businessHours = array();
    $workWeeks = self::getWorkWeekService()->getWorkWeekList();
    $workShifts = self::getWorkShiftService()->getWorkShiftList();
    $iterWorkWeek = $workWeeks->getIterator();
    $iterWorkShift = $workShifts->getIterator();
    foreach ($iterWorkWeek as $workWeek) {
      foreach ($iterWorkShift as $workShift) {
        $item = self::getBusinessHoursForCalendar($workWeek, $workShift);
        array_push($businessHours, $item);
      }
    }
    return $businessHours;
  }

  /**
   *
   * @param Employee $employee
   * @return array
   */
  public static function getEmployeeBusinessHoursForCalendar(Employee $employee) {
    $businessHours = array();
    $workWeeks = self::getWorkWeekService()->getWorkWeekList();
    $iterWorkWeek = $workWeeks->getIterator();
    $iterEmployeeWorkShift = $employee->EmployeeWorkShift->getIterator();

    foreach ($iterWorkWeek as $workWeek) {
      foreach ($iterEmployeeWorkShift as $employeeWorkShift) {
        $workShift = $employeeWorkShift->getWorkShift();
        $item = self::getBusinessHoursForCalendar($workWeek, $workShift);
        array_push($businessHours, $item);
      }
    }

    return $businessHours;
  }

  /**
   *
   * @param type $iterWorkWeek
   * @param type $iterWorkShift
   * @return array
   */
  public static function getBusinessHoursForCalendar(&$workWeek, &$workShift) {
    return array(
      'dow' => self::getWorkDays($workWeek),
      'start' => self::getWorkStartHour($workShift),
      'end' => self::getWorkEndHour($workShift),
    );
  }

  /**
   *
   * @param WorkWeek $workWeek
   * @return array
   */
  public static function getWorkDays(WorkWeek $workWeek) {
    $days = array();
    if ($workWeek->mon != WorkWeek::WORKWEEK_LENGTH_WEEKEND) {
      array_push($days, 1);
    }
    if ($workWeek->tue != WorkWeek::WORKWEEK_LENGTH_WEEKEND) {
      array_push($days, 2);
    }
    if ($workWeek->wed != WorkWeek::WORKWEEK_LENGTH_WEEKEND) {
      array_push($days, 3);
    }
    if ($workWeek->thu != WorkWeek::WORKWEEK_LENGTH_WEEKEND) {
      array_push($days, 4);
    }
    if ($workWeek->fri != WorkWeek::WORKWEEK_LENGTH_WEEKEND) {
      array_push($days, 5);
    }
    if ($workWeek->sat != WorkWeek::WORKWEEK_LENGTH_WEEKEND) {
      array_push($days, 6);
    }
    if ($workWeek->sun != WorkWeek::WORKWEEK_LENGTH_WEEKEND) {
      array_push($days, 0);
    }

    return $days;
  }

  /**
   *
   * @param WorkShift $workShift
   * @return type
   */
  public static function getWorkStartHour(WorkShift $workShift) {
    $time = strtotime(date('Y-m-d ' . $workShift->start_time));
    $hour = date('H:i', $time);
    return $hour;
  }

  /**
   *
   * @param WorkShift $workShift
   * @return type
   */
  public static function getWorkEndHour(WorkShift $workShift) {
    $time = strtotime(date('Y-m-d ' . $workShift->end_time));
    $hour = date('H:i', $time);
    return $hour;
  }

  /**
   *
   * @param type $startDate
   * @param type $endDate
   * @return array
   */
  public static function getHolidaysAsCalendarEvents($startDate = null, $endDate = null) {
    $holidays = array();
    $holidayService = self::getHolidayService();
    $holidaysResult = $holidayService->searchHolidays($startDate, $endDate);
    foreach ($holidaysResult as $holiday) {
      $tmp = array(
        'id' => "holiday" . $holiday->getId(),
        'title' => $holiday->getDescription(),
        'start' => $holiday->getDate(),
        'end' => date('Y-m-d', strtotime($holiday->getDate() . ' + 1 day')),
        'allday' => ($holiday->getLength() == WorkWeek::WORKWEEK_LENGTH_FULL_DAY) ? true : false,
        'isHoliday' => ($holiday->getLength() == WorkWeek::WORKWEEK_LENGTH_FULL_DAY || $holiday->getLength() == WorkWeek::WORKWEEK_LENGTH_HALF_DAY) ? true : false,
        'rendering' => 'background',
      );
      array_push($holidays, $tmp);
    }

    return $holidays;
  }

}
