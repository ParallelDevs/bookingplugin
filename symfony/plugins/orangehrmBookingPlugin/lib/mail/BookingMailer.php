<?php

/**
 * Description of BookingMailer
 *
 * @author pdev
 */
class BookingMailer implements ohrmObserver {

  protected $emailService;
  protected $bookablePermissions;

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
  
  public function listen(\sfEvent $event) {
    
  }

}
