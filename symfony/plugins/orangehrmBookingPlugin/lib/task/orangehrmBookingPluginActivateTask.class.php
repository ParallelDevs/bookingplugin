<?php

class orangehrmBookingPluginActivateTask extends sfBaseTask {

  const LICENSE_EMAIL = 'booking.license_email';
  const LICENSE_KEY = 'booking.license_key';
  const LICENSE_SECRET = 'booking.license_secret';
  const MESSAGE_SIZE = 2048;
  const MESSAGE_TYPE_INFO = 'INFO';
  const MESSAGE_TYPE_ERROR = 'ERROR';

  private $licenseKey;
  private $licenseEmail;
  private $licenseSecretHash;
  private $licenseDomain;

  /**
   *
   * @param \sfEventDispatcher $dispatcher
   * @param \sfFormatter $formatter
   */
  public function __construct(\sfEventDispatcher $dispatcher, \sfFormatter $formatter) {
    parent::__construct($dispatcher, $formatter);
    $this->licenseKey = null;
    $this->licenseEmail = null;
    $this->licenseSecretHash = null;
    $this->licenseDomain = null;
    $this->pluginName = 'orangehrmBookingPlugin';
  }

  /**
   *
   */
  protected function configure() {
    $this->addArguments(array(
      new sfCommandArgument('licenseKey', sfCommandArgument::OPTIONAL, 'The license key'),
      new sfCommandArgument('licenseEmail', sfCommandArgument::OPTIONAL, 'The license email'),
      new sfCommandArgument('licenseDomain', sfCommandArgument::OPTIONAL, 'The site domain to register'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      new sfCommandOption('key', null, sfCommandOption::PARAMETER_REQUIRED, 'License key', ''),
      new sfCommandOption('email', null, sfCommandOption::PARAMETER_REQUIRED, 'License email', ''),
      new sfCommandOption('domain', null, sfCommandOption::PARAMETER_REQUIRED, 'Site domain', ''),
    ));

    $this->aliases = array('orangehrmBooking:activate'); // for backwards compatibility
    $this->namespace = 'orangehrmBookingPlugin';
    $this->name = 'activate';
    $this->briefDescription = 'Activates the license for the OrangeHRM Booking plugin';
    $this->detailedDescription = <<<EOF
The [orangehrmBooking:activate-plugin|INFO] task activates the booking plugin:

  [./symfony orangehrm:activate-plugin licenseKey licenseEmail|INFO]
EOF;
  }

  /**
   *
   * @param type $arguments
   * @param type $options
   */
  protected function execute($arguments = array(), $options = array()) {
    $this->checkArguments($arguments, $options);

    define('SF_ROOT_DIR', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..'));
    define('SF_APP', 'orangehrm');
    define('SF_ENVIRONMENT', 'prod');
    define('SF_DEBUG', true);
    require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'ProjectConfiguration.class.php');

    $configuration = ProjectConfiguration::getApplicationConfiguration('orangehrm', 'prod', true);
    sfContext::createInstance($configuration);

    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $activationConfig = $this->readPluginActivationData();
    $licenseData = $this->sendActivation($activationConfig);

    if ($licenseData->result == 'success') {
      $this->installPlugin($licenseData);
    }
    else {
      $this->reportFailedActivation($licenseData);
    }
  }

  /**
   *
   * @param type $arguments
   * @param type $options
   * @throws sfCommandException
   */
  protected function checkArguments(&$arguments, &$options) {
    if (!empty($arguments['licenseKey'])) {
      $this->licenseKey = $arguments['licenseKey'];
    }
    else if (!empty($options['key'])) {
      $this->licenseKey = $options['key'];
    }

    if (!empty($arguments['licenseEmail'])) {
      $this->licenseEmail = $arguments['licenseEmail'];
    }
    else if (!empty($options['email'])) {
      $this->licenseEmail = $options['email'];
    }

    if (!empty($arguments['licenseDomain'])) {
      $this->licenseDomain = $arguments['licenseDomain'];
    }
    else if (!empty($options['domain'])) {
      $this->licenseDomain = $options['domain'];
    }

    if (empty($this->licenseKey)) {
      throw new sfCommandException('License key must be specified as an argument');
    }

    if (empty($this->licenseEmail)) {
      throw new sfCommandException('License email must be specified as an argument');
    }

    if (empty($this->licenseDomain)) {
      throw new sfCommandException('Site domain must be specified as an argument');
    }
  }

  /**
   *
   * @return type
   * @throws sfCommandException
   */
  protected function readPluginActivationData() {
    $activationData = array();
    $pluginName = $this->namespace;
    $pluginDir = sfConfig::get('sf_plugins_dir') . DIRECTORY_SEPARATOR . $pluginName;

    $appYmlFile = $pluginDir . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "app.yml";

    if (is_file($appYmlFile) && is_readable($appYmlFile)) {
      try {
        $appYml = sfYaml::load($appYmlFile);
        if (isset($appYml['all'][$pluginName]['activation'])) {
          $activationData = $appYml['all'][$pluginName]['activation'];
        }
      }
      catch (Exception $e) {
        throw new sfCommandException('Error loading plugin app.yml file. ' . $e->getMessage());
        $message = "Error loading plugin $appYmlFile file. " . $e->getMessage();
        $this->log($message, null, self::MESSAGE_TYPE_ERROR);
      }
    }
    else {
      $this->log($appYmlFile . ' not found. Plugin information cannot be determined', null, self::MESSAGE_TYPE_ERROR);
    }

    return $activationData;
  }

  /**
   *
   * @param type $config
   */
  protected function sendActivation(&$config) {
    $url = $config['url'];
    $data = array(
      'slm_action' => 'slm_activate',
      'secret_key' => $config['request_key'],
      'email' => trim($this->licenseEmail),
      'license_key' => trim($this->licenseKey),
      'registered_domain' => trim($this->licenseDomain),
      'item_reference' => trim($config['name']),
    );

    $response = $this->sendRequest($data, $url);
    $result = json_decode($response);
    return $result;
  }

  /**
   *
   * @param type $data
   * @param type $url
   * @return type
   */
  protected function sendRequest($data, $url) {
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

  /**
   *
   * @param type $key
   * @param type $value
   */
  protected function saveLicenseSettings($key, $value) {

    $sql = "INSERT INTO hs_hr_config(`key`, `value`) VALUES(:key, :value) "
        . " ON DUPLICATE KEY UPDATE `value` = :value";
    $pdo = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();

    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute(array(':key' => $key, ':value' => $value));
    if (!$result) {
      $this->log("Failed to update value for $key", self::MESSAGE_SIZE, self::MESSAGE_TYPE_ERROR);
    }
  }

  /**
   *
   * @param type $licenseData
   */
  protected function installPlugin(&$licenseData) {
    $this->licenseSecretHash = $licenseData->secret;
    $installer = new PluginInstaller($this);
    $installer->installPlugin($this->pluginName);
    $this->saveLicenseSettings(self::LICENSE_EMAIL, $this->licenseEmail);
    $this->saveLicenseSettings(self::LICENSE_KEY, $this->licenseKey);
    $this->saveLicenseSettings(self::LICENSE_SECRET, $this->licenseSecretHash);
    $this->logSection('booking', 'License was activated', self::MESSAGE_SIZE, self::MESSAGE_TYPE_INFO);
    $this->logSection('booking', 'Clearing cache for forcing to load plugin configuration', self::MESSAGE_SIZE, self::MESSAGE_TYPE_INFO);
    $this->runTask('cache:clear');
  }

  /**
   *
   * @param type $licenseData
   */
  protected function reportFailedActivation(&$licenseData) {
    $this->logSection('orangehrm', $this->pluginName . ' was not installed', self::MESSAGE_SIZE, self::MESSAGE_TYPE_ERROR);
    $this->logSection('booking', 'License was not activated', self::MESSAGE_SIZE, self::MESSAGE_TYPE_ERROR);
    $this->logSection('booking', $licenseData->message, self::MESSAGE_SIZE, self::MESSAGE_TYPE_ERROR);
  }

}
