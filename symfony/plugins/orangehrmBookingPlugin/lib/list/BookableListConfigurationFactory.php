<?php

/**
 * Description of BookableListConfigurationFactory
 *
 * @author amora
 */
class BookableListConfigurationFactory extends ohrmListConfigurationFactory {

  protected function init() {
    sfContext::getInstance()->getConfiguration()->loadHelpers('OrangeDate');
    $headerArray = array();

    $headers = $this->getHeaderList();
    foreach ($headers as $header_config) {
      $header = new ListHeader();
      $header->populateFromArray($header_config);
      array_push($headerArray, $header);
    }

    $this->headers = $headerArray;
  }

  public function getClassName() {
    return 'BookableResource';
  }

  /**
   *
   * @return array
   */
  private function getHeaderList() {
    $columns = array(
      array(
        'name' => __('Id'),
        'width' => '10%',
        'isSortable' => true,
        'sortField' => 'bookableId',
        'elementType' => 'link',
        'elementProperty' => array(
          'labelGetter' => 'getBookableId',
          'placeholderGetters' => array('id' => 'getBookableId'),
          'linkable' => true,
          'urlPattern' => public_path('index.php/booking/viewBookableResource/bookableId/{id}'),
        ),
      ),
      array(
        'name' => __('Employee Id'),
        'width' => '10%',
        'isSortable' => true,
        'sortField' => 'empNumber',
        'elementType' => 'link',
        'elementProperty' => array(
          'labelGetter' => 'getEmployeeId',
          'placeholderGetters' => array('id' => 'getEmpNumber'),
          'linkable' => true,
          'urlPattern' => public_path('index.php/pim/viewEmployee/empNumber/{id}'),
        ),
      ),
      array(
        'name' => __('Employee Name'),
        'width' => '40%',
        'isSortable' => false,
        'sortField' => 'employeeName',
        'elementType' => 'label',
        'textAlignmentStyle' => 'left',
        'elementProperty' => array(
          'getter' => array('getEmployeeName'),
        ),
      ),
      array(
        'name' => __('Status'),
        'width' => '10%',
        'isSortable' => true,
        'sortField' => 'isActive',
        'elementType' => 'label',
        'textAlignmentStyle' => 'left',
        'elementProperty' => array('getter' => array('getStatus'),
        ),
      ),
      array(
        'name' => __('Color for Bookings'),
        'width' => '10%',
        'isSortable' => false,
        'elementType' => 'label',
        'textAlignmentStyle' => 'left',
        'elementProperty' => array('getter' => array('getBookableColor'),
        ),
      ),
    );

    return $columns;
  }

}
