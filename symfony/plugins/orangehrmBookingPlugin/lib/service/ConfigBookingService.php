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

}
