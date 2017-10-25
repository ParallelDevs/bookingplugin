<?php

/**
 * Description of orangehrmBookingPluginConfiguration
 *
 * @author amora
 */
class orangehrmBookingPluginConfiguration extends sfPluginConfiguration {

  protected static $eventsBound = false;

  public function initialize() {
    $enabledModules = sfConfig::get('sf_enabled_modules');
    if (is_array($enabledModules)) {
      sfConfig::set('sf_enabled_modules', array_merge(sfConfig::get('sf_enabled_modules'), array('booking')));
    }

    if (!self::$eventsBound) {
      $this->dispatcher->connect(BookingEvents::BOOKING_ADD, array(new BookingMailer(), 'listen'));
      $this->dispatcher->connect(BookingEvents::BOOKING_UPDATE, array(new BookingMailer(), 'listen'));
      $this->dispatcher->connect(BookingEvents::BOOKING_DELETE, array(new BookingMailer(), 'listen'));
      self::$eventsBound = true;
    }
  }

}
