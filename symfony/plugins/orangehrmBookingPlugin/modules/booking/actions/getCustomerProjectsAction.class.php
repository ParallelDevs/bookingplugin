<?php

/**
 * Description of getCustomerProjectsAction
 *
 * @author amora
 */
class getCustomerProjectsAction extends baseBookingAction {

  /**
   *
   * @param type $request
   */
  public function execute($request) {
    if ($request->hasParameter('customerId')) {
      $customerId = $request->getParameter('customerId');
      $result = $this->getProjectService()->getProjectsByCustomerId($customerId);
      $this->result = $result->toArray();
      foreach ($this->result as &$project) {
        unset($project['customerId']);
        unset($project['is_deleted']);
        unset($project['description']);
      }
    }
    else {
      $this->result = array();
    }
  }

}
