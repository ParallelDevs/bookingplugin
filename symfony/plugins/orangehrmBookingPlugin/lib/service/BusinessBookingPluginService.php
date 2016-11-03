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
   * @return array
   */
  public function getCompanyBusinessLimitHoursForCalendar() {
    $hours = array();
    $workShifts = self::getWorkShiftService()->getWorkShiftList();
    $iterWorkShift = $workShifts->getIterator();
    foreach ($iterWorkShift as $workShift) {

      if (!array_key_exists('minHour', $hours)) {
        $hours['minHour'] = self::getLowestHour('', $workShift->start_time);
      }
      else {
        $hours['minHour'] = self::getLowestHour($hours['minHour'], $workShift->start_time);
      }

      if (!array_key_exists('maxHour', $hours)) {
        $hours['maxHour'] = self::getGreatestHour('', $workShift->end_time);
      }
      else {
        $hours['maxHour'] = self::getGreatestHour($hours['maxHour'], $workShift->end_time);
      }
    }

    if (!empty($hours)) {
      $minHour = strtotime(date('Y-m-d') . ' ' . $hours['minHour'] . ' -30 minutes');
      $maxHour = strtotime(date('Y-m-d') . ' ' . $hours['maxHour'] . ' +30 minutes');
      $hours = array(
        'minHour' => date('H:i:s', $minHour),
        'maxHour' => date('H:i:s', $maxHour),
      );
    }

    return $hours;
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

  /**
   *
   * @param type $hour1
   * @param type $hour2
   * @return type
   */
  public static function getLowestHour($hour1, $hour2) {
    if (empty($hour1) && !empty($hour2)) {
      return $hour2;
    }

    if (!empty($hour1) && empty($hour2)) {
      return $hour1;
    }

    $today = date('Y-m-d');
    $current = strtotime($today . ' ' . $hour1);
    $new = strtotime($today . ' ' . $hour2);
    if ($new < $current) {
      return $hour2;
    }
    return $hour1;
  }

  /**
   *
   * @param type $hour1
   * @param type $hour2
   * @return type
   */
  public static function getGreatestHour($hour1, $hour2) {
    if (empty($hour1) && !empty($hour2)) {
      return $hour2;
    }

    if (!empty($hour1) && empty($hour2)) {
      return $hour1;
    }

    $today = date('Y-m-d');
    $current = strtotime($today . ' ' . $hour1);
    $new = strtotime($today . ' ' . $hour2);
    if ($new > $current) {
      return $hour2;
    }
    return $hour1;
  }

}
