<?php

/**
 * Description of BookingMailer
 *
 * @author pdev
 */
class BookingMailer implements ohrmObserver {

  protected $bookableService;
  protected $configBookingService;
  protected $emailConfigurationService;
  protected $emailService;
  protected $projectService;
  protected $search = [
    '%employeeFirstName%',
    '%projectName%',
  ];

  /**
   * Get email service instance
   * @return EmailService
   */
  public function getEmailService() {
    if (empty($this->emailService)) {
      $this->emailService = new EmailService();
    }
    return $this->emailService;
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
  public function getProjectService() {
    if (!$this->projectService instanceof ProjectService) {
      $this->projectService = new ProjectService();
      $this->projectService->setProjectDao(new ProjectDao());
    }
    return $this->projectService;
  }

  /**
   * 
   * @return type
   */
  public function getEmailConfigurationService() {
    if (!$this->emailConfigurationService instanceof EmailConfigurationService) {
      $this->emailConfigurationService = new EmailConfigurationService();
    }
    return $this->emailConfigurationService;
  }

  /**
   * 
   * @param \sfEvent $event
   */
  public function listen(\sfEvent $event) {

    $emailService = $this->getEmailService();
    $emailTo = $this->getEventEmailTo($event);
    $emailFrom = $this->getEventEmailFrom();
    $subject = $this->getEventEmailSubject($event);
    $message = $this->getEventEmailBody($event);

    $emailService->setMessageTo($emailTo);
    $emailService->setMessageFrom($emailFrom);
    $emailService->setMessageSubject($subject);
    $emailService->setMessageBody($message);
    $emailService->sendEmail();
  }

  /**
   * 
   * @param type $event
   * @return type
   */
  private function getEventEmailSubject(&$event) {
    $replace = $this->getEventReplaceValues($event);
    $subjectTemplate = $this->getConfigBookingService()->getNotificationSubject();
    $subject = str_replace($this->search, $replace, $subjectTemplate);
    return $subject;
  }

  /**
   * 
   * @param type $event
   */
  private function getEventEmailBody(&$event) {
    $replace = $this->getEventReplaceValues($event);
    $emailTemplate = $this->getConfigBookingService()->getNotificationEmail();
    $message = str_replace($this->search, $replace, $emailTemplate);
    return $message;
  }

  /**
   * 
   * @param type $event
   * @return type
   */
  private function getEventEmailTo(&$event) {
    $eventData = $event->getParameters();
    $bookableId = $eventData['bookableId'];
    $bookable = $this->getBookableService()->getBookableResourceById($bookableId);
    $emailTo = $bookable->getEmployeeWorkEmail();
    return $emailTo;
  }

  /**
   * 
   * @return type
   */
  private function getEventEmailFrom() {
    $emailconfiguration = $this->getEmailConfigurationService()->getEmailConfiguration();
    $emailFrom = $emailconfiguration->getSentAs();
    return $emailFrom;
  }

  /**
   * 
   * @param type $event
   * @return type
   */
  private function getEventReplaceValues(&$event) {
    $eventData = $event->getParameters();
    $bookableId = $eventData['bookableId'];
    $projectId = $eventData['projectId'];
    $bookable = $this->getBookableService()->getBookableResourceById($bookableId);
    $project = $this->getProjectService()->getProjectById($projectId);

    $replace = [
      $bookable->getEmployeeName(),
      $project->getName()
    ];

    return $replace;
  }

}
