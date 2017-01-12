<?php

/**
 * Description of getBookablesAction
 *
 * @author amora
 */
class getBookablesAction extends baseBookingAction {

  /**
   *
   * @param type $request
   */
  public function execute($request) {

    $bookableId = $request->hasParameter('bookableId') ? $request->getParameter('bookableId') : '';

    $this->setFilters(array('bookableId' => $bookableId));

    $parameterHolder = new BookableSearchParameterHolder();
    $parameterHolder->setOrderField('empNumber');
    $parameterHolder->setOrderBy('ASC');
    $parameterHolder->setLimit(NULL);
    $parameterHolder->setOffset(0);
    $parameterHolder->setFilters($this->getFilters());
    $parameterHolder->setReturnType(BookableSearchParameterHolder::RETURN_TYPE_CALENDAR_RESOURCE);

    $this->result = $this->getBookableService()->searchBookableResourceList($parameterHolder);
  }

}
