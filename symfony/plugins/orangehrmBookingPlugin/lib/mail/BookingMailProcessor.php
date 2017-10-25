<?php

/**
 * Description of BookingMailProcessor
 *
 * @author pdev
 */
class BookingMailProcessor implements orangehrmMailProcessor {

  /**
   *
   * @param type $data
   * @return array
   */
  public function getReplacements($data) {
    $replacements = [];
    $bookableId = $data['bookableId'];
    $projectId = $data['projectId'];
    $bookable = $this->getBookableService()->getBookableResourceById($bookableId);
    $project = $this->getProjectService()->getProjectById($projectId);

    $replacements['employeeFirstName'] = $bookable->getEmployeeFirstName();
    $replacements['projectName'] = $project->getName();
    return $replacements;
  }

  /**
   *
   * @param type $emailName
   * @param type $role
   * @param type $data
   * @return array
   */
  public function getRecipients($emailName, $role, $data) {
    $recipients = [];
    switch ($role) {
      case 'ess':
        $recipients = $this->getSelf($data);
        break;
      default :
        break;
    }
    return $recipients;
  }

  /**
   *
   * @return type
   */
  public function getBookableService() {
    if (!$this->bookableService instanceof BookableResourceService) {
      $this->bookableService = new BookableResourceService();
      $this->bookableService->setBookableDao(new BookableResourceDao());
    }
    return $this->bookableService;
  }

  /**
   *
   * @return type
   */
  public function getProjectService() {
    if (!$this->projectService instanceof ProjectService) {
      $this->projectService = new ProjectService();
      $this->projectService->setProjectDao(new ProjectDao());
    }
    return $this->projectService;
  }

  /**
   *
   * @param type $data
   * @return type
   */
  private function getSelf($data) {
    $recipients = [];
    $bookableId = $data['bookableId'];
    $bookable = $this->getBookableService()->getBookableResourceById($bookableId);
    $emailTo = $bookable->getEmployeeWorkEmail();

    if (!empty($emailTo)) {
      $recipients[] = $bookable->getEmployee();
    }
    return $recipients;
  }

}
