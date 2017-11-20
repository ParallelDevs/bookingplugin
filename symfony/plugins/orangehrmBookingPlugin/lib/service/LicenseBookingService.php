<?php

/**
 * Description of ConfigureBookingService
 *
 * @author alonso
 */
class LicenseBookingService extends BaseService {

  /**
   *
   * @return string
   */
  public function getApiLicenseConfig() {
    $pluginConfig = sfConfig::get('app_orangehrmBookingPlugin_activation', array());

    if (!array_key_exists('name', $pluginConfig)) {
      $pluginConfig['name'] = '';
    }
    if (!array_key_exists('url', $pluginConfig)) {
      $pluginConfig['url'] = '';
    }
    if (!array_key_exists('request_key', $pluginConfig)) {
      $pluginConfig['request_key'] = '';
    }
    return $pluginConfig;
  }

  /**
   *
   * @param type $licenseKey
   * @param type $email
   * @return type
   */
  public function activateLicense($email = '', $licenseKey = '') {
    $config = $this->getApiLicenseConfig();
    $url = $config['url'];
    $data = array(
      'slm_action' => 'slm_activate',
      'secret_key' => $config['request_key'],
      'email' => trim($email),
      'license_key' => trim($licenseKey),
      'registered_domain' => $_SERVER['SERVER_NAME'],
      'item_reference' => $config['name'],
    );

    $result = $this->sendApiRequest($data, $url);
    return $result;
  }

  /**
   *
   * @param type $licenseKey
   * @param type $licenseSecret
   * @return type
   */
  public function checkLicense($email = '', $licenseKey = '', $licenseSecret = '') {
    $config = $this->getApiLicenseConfig();
    $url = $config['url'];
    $data = array(
      'slm_action' => 'slm_check',
      'secret_key' => $config['request_key'],
      'email' => $email,
      'license_key' => $licenseKey,
      'domain' => $_SERVER['SERVER_NAME'],
      'license_secret' => $licenseSecret,
      'item_reference' => $config['name'],
    );

    $result = $this->sendApiRequest($data, $url);
    return $result;
  }

  protected function sendApiRequest($data, $url) {
    $postData = http_build_query($data);
    try {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

      $response = curl_exec($ch);
      curl_close($ch);

      $responseJson = json_decode($response);
      if ($responseJson === false) {
        $response = json_encode(new stdClass());
      }
    }
    catch (Exception $e) {
      $response = json_encode(new stdClass());
    }
    return $response;
  }

}
