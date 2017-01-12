<?php

/**
 * Description of getBookableWorkShiftAction
 *
 * @author amora
 */
class getBookableWorkShiftAction extends baseBookingAction {

  public function execute($request) {
    if ($request->hasParameter('bookableId')) {
      $bookableId = $request->getParameter('bookableId');
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

    foreach ($this->result as $key => &$value) {
      $time = strtotime(date('Y-m-d ' . $value));
      $this->result[$key] = date('H:i', $time);
    }
  }

}
