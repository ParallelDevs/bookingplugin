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
    $this->bookablePermissions = $this->getDataGroupPermissions('booking_resources');
    if ($this->bookablePermissions->canRead()) {
      $start = $request->hasParameter('start') ? $request->getParameter('start') : date('Y-m-d');
      $end = $request->hasParameter('end') ? $request->getParameter('end') : date('Y-m-d');
      if ($request->hasParameter('bookableId')) {
        $this->result = [];
        $this->processWorkShift($request);
      }
      else {
        $this->result = array('maxTime' => '23:59', 'minTime' => '00:00');
      }

      $holidays = BusinessBookingPluginService::getHolidaysAsArray($start, $end);

      $time = strtotime(date('Y-m-d ' . $this->result['maxTime']));
      $this->result['maxTime'] = date('H:i', $time);
      $time = strtotime(date('Y-m-d ' . $this->result['minTime']));
      $this->result['minTime'] = date('H:i', $time);
      $this->result['holidays'] = $holidays;
    }
  }

  /**
   * 
   * @param type $request
   */
  private function processWorkShift($request) {
    $bookableId = $request->getParameter('bookableId');

    try {
      $bookable = $this->getBookableService()->getBookableResourceById($bookableId);
      $result = $bookable->getWorkShifts();
      foreach ($result as $workshift) {
        $this->verifyValue('minTime', 'start', $workshift);
        $this->verifyValue('maxTime', 'end', $workshift);
        $this->result['dow'] = $workshift['dow'];
      }
    }
    catch (Exception $e) {
      $this->result = array('maxTime' => '23:59', 'minTime' => '00:00');
      sfContext::getInstance()->getLogger()->err($e->getMessage());
    }
  }

  /**
   * 
   * @param type $key1
   * @param type $key2
   * @param type $data
   */
  private function verifyValue($key1, $key2, &$data) {
    if (array_key_exists($key1, $this->result)) {
      if (strcasecmp($this->result[$key1], $data[$key2]) > 0) {
        $this->result[$key1] = $data[$key2];
      }
    }
    else {
      $this->result[$key1] = $data[$key2];
    }
  }

}
