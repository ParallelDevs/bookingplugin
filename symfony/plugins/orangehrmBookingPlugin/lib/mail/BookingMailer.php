<?php

/**
 * Description of BookingMailer
 *
 * @author pdev
 */
class BookingMailer implements ohrmObserver {

  protected $bookableService;
  protected $configBookingService;
  protected $emailService;
  protected $projectService;

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
   * @param EmailService $emailService
   */
  public function setEmailService(EmailService $emailService) {
    $this->emailService = $emailService;
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
   * @param BookableResourceService $bookableService
   */
  public function setBookableService(BookableResourceService $bookableService) {
    $this->bookableService = $bookableService;
  }

  /**
   *
   * @param ConfigBookingService $configService
   */
  public function setConfigBookingService(ConfigBookingService $configService) {
    $this->configBookingService = $configService;
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
   * @param ProjectService $projectService
   */
  public function setProjectService(ProjectService $projectService) {
    $this->projectService = $projectService;
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
   * @param \sfEvent $event
   */
  public function listen(\sfEvent $event) {
    
  }

}
