<?php

/**
 * Description of BookingMailer
 *
 * @author pdev
 */
class BookingMailer implements ohrmObserver {

  protected $bookableService;
  protected $emailService;

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
   * @param \sfEvent $event
   */
  public function listen(\sfEvent $event) {
    $eventData = $event->getParameters();
    $recipientRoles = ['ess'];
    $emailType = $eventData['actionType'];
    $performerRole = null;

    if (count($recipientRoles) > 0) {
      $this->getEmailService()->sendEmailNotifications($emailType, $recipientRoles, $eventData, strtolower($performerRole));
    }
  }

}
