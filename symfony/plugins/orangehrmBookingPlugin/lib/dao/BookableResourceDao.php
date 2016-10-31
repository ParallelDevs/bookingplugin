<?php

/**
 * Description of BookableResourceDao
 *
 * @author amora
 */
class BookableResourceDao extends BaseDao {

  /**
   * Mapping of search field names to database fields
   * @var array
   */
  protected static $searchMapping = array(
    'bookableId' => 'b.bookable_id',
    'empId' => 'b.emp_number',
    'active' => 'b.is_active',
    'employeeId' => 'e.employee_id',
  );

  /**
   * Mapping of sort field names to database fields
   * @var array
   */
  protected static $sortMapping = array(
    'bookableId' => 'b.bookable_id',
    'empId' => 'b.emp_number',
    'active' => 'b.is_active',
    'employeeId' => 'e.employee_id',
  );

  /**
   *
   * @param BookableResource $resource
   * @return \BookableResource
   * @throws DaoException
   */
  public function saveBookableResource(BookableResource $resource) {
    try {
      if ($resource->Employee->getEmpNumber() == '') {
        $employee = $resource->Employee->save();
        $resource->Employee = $employee;
      }

      $resource->save();
      return $resource;
    }
    catch (Exception $e) {
      throw new DaoException($e->getMessage(), $e->getCode(), $e);
    }
  }

  /**
   *
   * @param type $id
   * @return type
   * @throws DaoException
   */
  public function getBookableResourceById($id) {
    try {
      return Doctrine :: getTable('BookableResource')->find($id);
      // @codeCoverageIgnoreStart
    }
    catch (Exception $e) {
      throw new DaoException($e->getMessage(), $e->getCode(), $e);
    }
  }

  /**
   *
   * @param type $empNumber
   * @return type
   * @throws DaoException
   */
  public function getBookableResource($empNumber) {
    try {

      return Doctrine::getTable('BookableResource')->findOneBy('empNumber', $empNumber);
    }
    catch (Exception $e) {
      throw new DaoException($e->getMessage(), $e->getCode(), $e);
    }
  }

  /**
   *
   * @return type
   * @throws DaoException
   */
  public function getBookableResourceList() {
    try {
      $q = Doctrine_Query::create()
          ->from('BookableResource')
          ->orderBy(' empNumber ASC ');
      return $q->execute();
    }
    catch (Exception $e) {
      throw new DaoException($e->getMessage(), $e->getCode(), $e);
    }
  }

  /**
   *
   * @param type $field
   * @param type $value
   * @return type
   * @throws DaoException
   */
  public function searchBookableResource($field, $value) {
    try {
      $q = Doctrine_Query::create()
          ->from('BookableResource')
          ->where($field . ' = ?', $value);
      return $q->execute();
    }
    catch (Exception $e) {
      throw new DaoException($e->getMessage(), $e->getCode(), $e);
    }
  }

  /**
   *
   * @param BookableSearchParameterHolder $parameterHolder
   * @return type
   */
  public function searchBookableResources(BookableSearchParameterHolder $parameterHolder) {
    $sortField = $parameterHolder->getOrderField();
    $sortOrder = $parameterHolder->getOrderBy();
    $offset = $parameterHolder->getOffset();
    $limit = $parameterHolder->getLimit();
    $filters = $parameterHolder->getFilters();
    $returnType = $parameterHolder->getReturnType();

    $select = '';
    $query = '';
    $bindParams = array();
    $orderBy = '';

    $this->_getBookableListQuery($select, $query, $bindParams, $orderBy, $sortField, $sortOrder, $filters);
    $completeQuery = $select . ' ' . $query . ' ' . $orderBy;

    if (!is_null($offset) && !is_null($limit)) {
      $completeQuery .= ' LIMIT ' . $offset . ', ' . $limit;
    }

    return $this->_runSearch($completeQuery, $bindParams, $returnType);
  }

  /**
   *
   * @param type $select
   * @param type $query
   * @param array $bindParams
   * @param type $orderBy
   * @param type $sortField
   * @param type $sortOrder
   * @param array $filters
   */
  private function _getBookableListQuery(&$select, &$query, array &$bindParams, &$orderBy, $sortField = null, $sortOrder = null, array $filters = null) {
    $select = 'SELECT b.bookable_id as bookableId, b.is_active as isActive, b.emp_number,  b.bookable_color as bookableColor, ';
    $select .= 'e.emp_number AS empNumber, e.employee_id AS employeeId, ';
    $select .= 'e.emp_firstname AS firstName, e.emp_lastname AS lastName ';
    $query = ' FROM hs_hr_bookable_resource b ';
    $query .= ' LEFT JOIN hs_hr_employee e ON e.emp_number = b.emp_number ';

    $conditions = array();

    if (!empty($filters)) {
      $filterCount = 0;
      $this->_filterSearchFields($filters, $conditions, $bindParams, $filterCount);
    }

    /* Build the query */
    $numConditions = 0;
    $this->_buildWhere($conditions, $query, $numConditions);

    /* sorting */
    $order = array();
    $this->_filterSortFields($sortField, $sortOrder, $order);


    /* Build the order by part */
    $numOrderBy = 0;
    $this->_buildOrderBy($order, $orderBy, $numOrderBy);
  }

  /**
   *
   * @param type $filters
   * @param type $conditions
   * @param type $bindParams
   * @param type $filterCount
   */
  private function _filterSearchFields(&$filters, &$conditions, &$bindParams, &$filterCount) {
    foreach ($filters as $searchField => $searchBy) {
      if (!empty($searchField) && !empty($searchBy) && array_key_exists($searchField, self::$searchMapping)) {
        $field = self::$searchMapping[$searchField];
        if ($searchField == 'employeeId') {
          $conditions[] = ' e.employee_id LIKE ? ';
          $bindParams[] = $searchBy;
        }
        elseif ($searchField == 'bookableId') {
          $conditions[] = ' b.bookable_id LIKE ? ';
          $bindParams[] = $searchBy;
        }
        elseif ($searchField == 'empId') {
          $conditions[] = ' b.emp_number LIKE ? ';
          $bindParams[] = $searchBy;
        }
        elseif ($searchField == 'active') {
          $conditions[] = ' b.is_active LIKE ? ';
          $bindParams[] = $searchBy;
        }
      }
      $filterCount++;
    }
  }

  /**
   *
   * @param type $sortField
   * @param type $sortOrder
   * @param type $order
   */
  private function _filterSortFields(&$sortField, &$sortOrder, &$order) {
    if (!empty($sortField) && !empty($sortOrder)) {
      if (array_key_exists($sortField, self::$sortMapping)) {
        $field = self::$sortMapping[$sortField];
        if (is_array($field)) {
          foreach ($field as $name) {
            $order[$name] = $sortOrder;
          }
        }
        else {
          $order[$field] = $sortOrder;
        }
      }
    }

    $order['b.emp_number'] = 'asc';
  }

  /**
   *
   * @param type $conditions
   * @param type $query
   * @param type $numConditions
   */
  private function _buildWhere(&$conditions, &$query, &$numConditions) {
    foreach ($conditions as $condition) {
      $numConditions++;

      if ($numConditions == 1) {
        $query .= ' WHERE ' . $condition;
      }
      else {
        $query .= ' AND ' . $condition;
      }
    }
  }

  /**
   *
   * @param type $order
   * @param type $orderBy
   * @param type $numOrderBy
   */
  private function _buildOrderBy(&$order, &$orderBy, &$numOrderBy) {
    foreach ($order as $field => $dir) {
      $numOrderBy++;
      if ($numOrderBy == 1) {
        $orderBy = ' ORDER BY ' . $field . ' ' . $dir;
      }
      else {
        $orderBy .= ', ' . $field . ' ' . $dir;
      }
    }
  }

  /**
   *
   * @param type $completeQuery
   * @param type $bindParams
   * @param type $returnType
   * @return type
   */
  private function _runSearch(&$completeQuery, &$bindParams, &$returnType) {
    if (sfConfig::get('sf_logging_enabled')) {
      $msg = $completeQuery;
      if (count($bindParams) > 0) {
        $msg .= ' (' . implode(',', $bindParams) . ')';
      }
      sfContext::getInstance()->getLogger()->info($msg);
    }

    $conn = Doctrine_Manager::connection();
    $statement = $conn->prepare($completeQuery);
    $result = $statement->execute($bindParams);

    switch ($returnType) {
      case BookableSearchParameterHolder::RETURN_TYPE_OBJECT:
        $data = $this->_prepareResultAsObjectCollection($statement, $result);
        break;
      case BookableSearchParameterHolder::RETURN_TYPE_CALENDAR_RESOURCE:
        $data = $this->_prepareResultAsCalendarResource($statement, $result);
        break;
      case BookableSearchParameterHolder::RETURN_TYPE_ARRAY:
      default :
        $data = $statement->fetchAll();
        break;
    }

    return $data;
  }

  /**
   *
   * @param type $statement
   * @param type $result
   * @return \Doctrine_Collection
   */
  private function _prepareResultAsObjectCollection(&$statement, &$result) {
    $resources = new Doctrine_Collection(Doctrine::getTable('BookableResource'));

    if ($result) {
      while ($row = $statement->fetch()) {
        $bookable = $this->getBookableResourceById($row['bookableId']);
        $resources[] = $bookable;
      }
    }
    return $resources;
  }

  private function _prepareResultAsCalendarResource($statement, $result) {
    $resources = array();
    if ($result) {
      try {
        while ($row = $statement->fetch()) {
          $bookable = $this->getBookableResourceById($row['bookableId']);
          $resource = $bookable->getBookableAsCalendarResource();
          array_push($resources, $resource);
        }
      }
      catch (Exception $e) {
        sfContext::getInstance()->getLogger()->err($e->getMessage());
        unset($resources);
        $resources = array();
      }
      return $resources;
    }
  }

}
