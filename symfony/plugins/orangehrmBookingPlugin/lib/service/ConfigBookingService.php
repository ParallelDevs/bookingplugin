<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ConfigureBookingService
 *
 * @author alonso
 */
class ConfigBookingService extends BaseService {

  const COMPANY_BREAKS_TIME = 'booking.company_breaks_time';
  const LICENSE_EMAIL = 'booking.license_email';
  const LICENSE_KEY = 'booking.license_key';
  const LICENSE_SECRET = 'booking.license_secret';

  private $configDao;

  /**
   *
   * @param ConfigDao $configDao
   */
  public function setConfigDao(ConfigDao $configDao) {
    $this->configDao = $configDao;
  }

  /**
   *
   * @return type
   */
  public function getConfigDao() {
    if (!$this->configDao instanceof ConfigDao) {
      $this->configDao = new ConfigDao();
    }
    return $this->configDao;
  }

  /**
   *
   * @return type
   */
  public function getCompanyBreaksTime() {
    return $this->getConfigDao()->getValue(self::COMPANY_BREAKS_TIME);
  }

  /**
   *
   * @param type $breaksTime
   * @return type
   */
  public function setCompanyBreaksTime($breaksTime) {
    return $this->getConfigDao()->setValue(self::COMPANY_BREAKS_TIME, $breaksTime);
  }

  /**
   *
   * @return type
   */
  public function getLicenseEmail() {
    return $this->getConfigDao()->getValue(self::LICENSE_EMAIL);
  }

  /**
   *
   * @param type $email
   * @return type
   */
  public function setLicenseEmail($email = '') {
    return $this->getConfigDao()->setValue(self::LICENSE_EMAIL, $email);
  }

  /**
   *
   * @return type
   */
  public function getLicenseKey() {
    return $this->getConfigDao()->getValue(self::LICENSE_KEY);
  }

  /**
   *
   * @param type $license_key
   * @return type
   */
  public function setLicenseKey($license_key = '') {
    return $this->getConfigDao()->setValue(self::LICENSE_KEY, $license_key);
  }

  /**
   *
   * @return type
   */
  public function getLicenseSecret() {
    return $this->getConfigDao()->getValue(self::LICENSE_SECRET);
  }

  /**
   *
   * @param type $secret
   * @return type
   */
  public function setLicenseSecret($secret = '') {
    return $this->getConfigDao()->setValue(self::LICENSE_SECRET, $secret);
  }

  /**
   *
   * @return type
   */
  public function isLicenseSet() {
    $licenseEmail = $this->getLicenseEmail();
    $licenseKey = $this->getLicenseKey();
    $licenseSecret = $this->getLicenseSecret();
    return !empty($licenseEmail) && !empty($licenseKey) && !empty($licenseSecret) ? true : false;
  }

}
