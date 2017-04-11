<?php

/**
 * Description of getBookableWorkShiftAction
 *
 * @author amora
 */
class getBookableWorkShiftAction extends baseBookingAction {

  /**
   *
   * @param type $request
   */
  public function execute($request) {
    if ($request->hasParameter('bookableId')) {
      $bookableId = $request->getParameter('bookableId');
      $start = $request->hasParameter('start') ? $request->getParameter('start') : date('Y-m-d');
      $end = $request->hasParameter('end') ? $request->getParameter('end') : date('Y-m-d');
      try {
        $bookable = $this->getBookableService()->getBookableResourceById($bookableId);
        $result = $bookable->getWorkShifts();
        $this->result = array();
        foreach ($result as $workshift) {
          if (array_key_exists('minTime', $this->result)) {
            if (strcasecmp($this->result['minTime'], $workshift['start']) > 0) {
              $this->result['minTime'] = $workshift['start'];
            }
          }
          else {
            $this->result['minTime'] = $workshift['start'];
          }

          if (array_key_exists('maxTime', $this->result)) {
            if (strcasecmp($this->result['maxTime'], $workshift['end']) > 0) {
              $this->result['maxTime'] = $workshift['end'];
            }
          }
          else {
            $this->result['maxTime'] = $workshift['end'];
          }
          $this->result['dow'] = $workshift['dow'];
        }
      }
      catch (Exception $e) {
        $this->result = array('maxTime' => '23:59', 'minTime' => '00:00');
        sfContext::getInstance()->getLogger()->err($e->getMessage());
      }
    }
    else {
      $this->result = array('maxTime' => '23:59', 'minTime' => '00:00');
    }

    $holidays = BusinessBookingPluginService::getHolidaysAsJson($start, $end);

    $time = strtotime(date('Y-m-d ' . $this->result['maxTime']));
    $this->result['maxTime'] = date('H:i', $time);
    $time = strtotime(date('Y-m-d ' . $this->result['minTime']));
    $this->result['minTime'] = date('H:i', $time);
    $this->result['holidays'] = $holidays;
  }

}
