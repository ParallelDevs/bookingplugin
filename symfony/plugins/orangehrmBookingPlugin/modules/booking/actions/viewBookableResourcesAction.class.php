<?php

/**
 * Description of viewBookableResourcesAction
 *
 * @author amora
 */
class viewBookableResourcesAction extends baseBookingAction {

  protected $configurationList;
  protected $noOfRecords;
  protected $offset;
  protected $pageNumber;

  /**
   *
   * @param type $request
   */
  public function execute($request) {
    $bookablePermissions = $this->getDataGroupPermissions('booking_resources');
    if ($bookablePermissions->canRead()) {
      if ($this->getUser()->hasFlash('templateMessage')) {
        list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
      }
      $this->processPaging($request);
      $this->processFilters($request);

      $accessibleEmployees = UserRoleManagerFactory::getUserRoleManager()->getAccessibleEntityIds('Employee');
      if (count($accessibleEmployees) > 0) {
        $parameterHolder = $this->getBookableSearchParameter();
        $list = $this->getBookableService()->searchBookableResourceList($parameterHolder);
      }
      else {
        $count = 0;
        $list = array();
      }

      $this->setListComponent($list, $count, $this->noOfRecords, $this->pageNumber);
    }
  }

  /**
   * 
   * @param type $request
   */
  protected function processPaging(&$request) {
    $empNumber = $request->getParameter('empNumber');
    $isPaging = $request->getParameter('hdnAction') == 'search' ? 1 : $request->getParameter('pageNo', 1);

    if ($request->hasParameter('reset')) {
      $this->setFilters(array());
      $this->setSortParameter(array("field" => NULL, "order" => NULL));
      $this->setPage(1);
    }

    $this->pageNumber = $isPaging;
    if (!empty($empNumber) && $this->getUser()->hasAttribute('pageNumber')) {
      $this->pageNumber = $this->getUser()->getAttribute('pageNumber');
    }

    $this->noOfRecords = sfConfig::get('app_items_per_page');
    $this->offset = ($this->pageNumber >= 1) ? (($this->pageNumber - 1) * $this->noOfRecords) : ($request->getParameter('pageNo', 1) - 1) * $this->noOfRecords;
  }

  /**
   * 
   * @param type $request
   */
  protected function processFilters(&$request) {
    $this->form = new SearchBookableResourceForm($this->getFilters());

    if ($request->isMethod('post')) {
      $this->form->bind($request->getParameter($this->form->getName()));

      if ($this->form->isValid()) {

        if ($this->form->getValue('isSubmitted') == 'yes') {
          $this->setSortParameter(array("field" => NULL, "order" => NULL));
        }

        $this->setFilters($this->form->getValues());
      }
      else {
        $this->setFilters(array());
      }

      $this->setPage(1);
    }

    if ($request->isMethod('get')) {
      $sortParam = array(
        "field" => $request->getParameter('sortField'),
        "order" => $request->getParameter('sortOrder'),
      );
      $this->setSortParameter($sortParam);
      $this->setPage(1);
    }
  }

  /**
   * 
   * @return \BookableSearchParameterHolder
   */
  protected function getBookableSearchParameter() {
    $sort = $this->getSortParameter();
    $filters = $this->getFilters();
    if (isset($filters['employee_list'])) {
      $filters['empId'] = $filters['employee_list']['empId'];
    }
    $parameterHolder = new BookableSearchParameterHolder();
    $parameterHolder->setOrderField($sort["field"]);
    $parameterHolder->setOrderBy($sort["order"]);
    $parameterHolder->setLimit($this->noOfRecords);
    $parameterHolder->setOffset($this->offset);
    $parameterHolder->setFilters($filters);
    return $parameterHolder;
  }

  /**
   *
   * @return type
   */
  protected function getConfigurationListFactory() {
    if (!$this->configurationList instanceof BookableListConfigurationFactory) {
      $this->configurationList = new BookableListConfigurationFactory();
    }
    return $this->configurationList;
  }

  /**
   *
   * @return string
   */
  protected function getRunTimeDefinitions() {
    $runtimeDefinitions = array();
    $buttons = array();
    $buttons['Add'] = array('label' => 'Add');

    $runtimeDefinitions['buttons'] = $buttons;

    return $runtimeDefinitions;
  }

}
