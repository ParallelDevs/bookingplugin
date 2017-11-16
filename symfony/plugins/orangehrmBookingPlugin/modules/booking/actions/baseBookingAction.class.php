<?php

/**
 * Description of baseBookingAction
 *
 * @author amora
 */
abstract class baseBookingAction extends sfAction {

  protected $bookablePermissions;
  protected $bookingPermissions;
  protected $bookingConfigurationPermissions;
  protected $bookableService;
  protected $bookingService;
  protected $employeeService;
  protected $customerService;
  protected $projectService;
  protected $configBookingService;
  protected $licenseBookingService;

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
   * @return type
   */
  public function getBookingService() {
    if (!$this->bookingService instanceof BookingService) {
      $this->bookingService = new BookingService();
    }
    return $this->bookingService;
  }

  /**
   *
   * @param BookingService $bookingService
   */
  public function setBookingService(BookingService $bookingService) {
    $this->bookingService = $bookingService;
  }

  /**
   *
   * @return type
   */
  public function getEmployeeService() {
    if (!$this->employeeService instanceof EmployeeService) {
      $this->employeeService = new EmployeeService();
      $this->employeeService->setEmployeeDao(new EmployeeDao());
    }
    return $this->employeeService;
  }

  /**
   *
   * @param EmployeeService $employeeService
   */
  public function setEmployeeService(EmployeeService $employeeService) {
    $this->employeeService = $employeeService;
  }

  /**
   *
   * @return type
   */
  public function getCustomerService() {
    if (!$this->customerService instanceof CustomerService) {
      $this->customerService = new CustomerService();
      $this->customerService->setCustomerDao(new CustomerDao());
    }
    return $this->customerService;
  }

  /**
   *
   * @param CustomerService $customerService
   */
  public function setCustomerService(CustomerService $customerService) {
    $this->customerService = $customerService;
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
   * @param ProjectService $projectService
   */
  public function setProjectService(ProjectService $projectService) {
    $this->projectService = $projectService;
  }

  /**
   * 
   * @return type
   */
  public function getLicenseBookingService() {
    if (!$this->licenseBookingService instanceof LicenseBookingService) {
      $this->licenseBookingService = new LicenseBookingService();
    }
    return $this->licenseBookingService;
  }

  /**
   * 
   * @param LicenseBookingService $licenseService
   */
  public function setLicenseBookingService(LicenseBookingService $licenseService) {
    $this->licenseBookingService = $licenseService;
  }

  /**
   * 
   * @param type $dataGroups
   * @return type
   */
  public function getDataGroupPermissions($dataGroups) {
    return $this->getContext()->getUserRoleManager()->getDataGroupPermissions($dataGroups, array(), array(), false, array());
  }

  /**
   * 
   * @return type
   */
  public function getDispatcher() {
    return $this->getContext()->getEventDispatcher();
  }

  /**
   *
   * @return type
   */
  protected function getConfigurationListFactory() {
    return array();
  }

  /**
   *
   * @return type
   */
  protected function getRunTimeDefinitions() {
    return array();
  }

  /**
   *
   * @param type $resourceList
   * @param type $count
   * @param type $noOfRecords
   * @param type $page
   */
  protected function setListComponent($resourceList, $count, $noOfRecords, $page) {
    $configurationFactory = $this->getConfigurationListFactory();
    $runtimeDefinitions = $this->getRunTimeDefinitions();

    $configurationFactory->setRuntimeDefinitions($runtimeDefinitions);
    ohrmListComponent::setConfigurationFactory($configurationFactory);

    ohrmListComponent::setActivePlugin('orangehrmBookingPlugin');
    ohrmListComponent::setListData($resourceList);
    ohrmListComponent::setItemsPerPage($noOfRecords);
    ohrmListComponent::setNumberOfRecords($count);
    ohrmListComponent::setPageNumber($page);
  }

  /**
   * Set's the current page number in the user session.
   * @param $page int Page Number
   * @return None
   */
  protected function setPage($page) {
    $this->getUser()->setAttribute('bookablelist.page', $page, 'booking_module');
  }

  /**
   * Get the current page number from the user session.
   * @return int Page number
   */
  protected function getPage() {
    return $this->getUser()->getAttribute('bookablelist.page', 1, 'booking_module');
  }

  /**
   * Sets the current sort field and order in the user session.
   * @param type Array $sort
   */
  protected function setSortParameter($sort) {
    $this->getUser()->setAttribute('bookablelist.sort', $sort, 'booking_module');
  }

  /**
   * Get the current sort field&order from the user session.
   * @return array ('field' , 'order')
   */
  protected function getSortParameter() {
    return $this->getUser()->getAttribute('bookablelist.sort', null, 'booking_module');
  }

  /**
   *
   * @param array $filters
   * @return unknown_type
   */
  protected function setFilters(array $filters) {
    return $this->getUser()->setAttribute('bookablelist.filters', $filters, 'booking_module');
  }

  /**
   *
   * @return unknown_type
   */
  protected function getFilters() {
    return $this->getUser()->getAttribute('bookablelist.filters', null, 'booking_module');
  }

  /**
   *
   * @param type $filters
   * @param type $parameter
   * @param type $default
   * @return type
   */
  protected function _getFilterValue($filters, $parameter, $default = null) {
    $value = $default;
    if (isset($filters[$parameter])) {
      $value = $filters[$parameter];
    }

    return $value;
  }

  /**
   *
   * @return type
   */
  protected function getSearchParameterHolder() {
    return null;
  }

}
