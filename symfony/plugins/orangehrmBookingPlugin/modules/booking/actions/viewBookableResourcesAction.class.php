<?php

/**
 * Description of indexAction
 *
 * @author amora
 */
class viewBookableResourcesAction extends baseBookingAction {

  protected $configurationList;

  /**
   *
   * @param type $request
   */
  public function execute($request) {
    if ($this->getUser()->hasFlash('templateMessage')) {
      list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
    }

    $empNumber = $request->getParameter('empNumber');
    $isPaging = $request->getParameter('hdnAction') == 'search' ? 1 : $request->getParameter('pageNo', 1);


    if ($request->hasParameter('reset')) {
      $this->setFilters(array());
      $this->setSortParameter(array("field" => NULL, "order" => NULL));
      $this->setPage(1);
    }

    $pageNumber = $isPaging;
    if (!empty($empNumber) && $this->getUser()->hasAttribute('pageNumber')) {
      $pageNumber = $this->getUser()->getAttribute('pageNumber');
    }

    $noOfRecords = sfConfig::get('app_items_per_page');
    $offset = ($pageNumber >= 1) ? (($pageNumber - 1) * $noOfRecords) : ($request->getParameter('pageNo', 1) - 1) * $noOfRecords;

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

    $sort = $this->getSortParameter();
    $sortField = $sort["field"];
    $sortOrder = $sort["order"];
    $filters = $this->getFilters();

    if (isset($filters['employee_list'])) {
      $filters['empId'] = $filters['employee_list']['empId'];
    }

    $accessibleEmployees = UserRoleManagerFactory::getUserRoleManager()->getAccessibleEntityIds('Employee');
    if (count($accessibleEmployees) > 0) {
      $parameterHolder = new BookableSearchParameterHolder();
      $parameterHolder->setOrderField($sortField);
      $parameterHolder->setOrderBy($sortOrder);
      $parameterHolder->setLimit($noOfRecords);
      $parameterHolder->setOffset($offset);
      $parameterHolder->setFilters($filters);

      $list = $this->getBookableService()->searchBookableResourceList($parameterHolder);
    }
    else {
      $count = 0;
      $list = array();
    }


    $this->setListComponent($list, $count, $noOfRecords, $pageNumber);
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
