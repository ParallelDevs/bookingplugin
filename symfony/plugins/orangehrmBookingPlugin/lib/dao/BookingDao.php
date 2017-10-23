<?php

/**
 * Description of BookingDao
 *
 * @author amora
 */
class BookingDao extends BaseDao {

  /**
   * Mapping of search field names to database fields
   * @var array
   */
  protected static $searchMapping = array(
    'bookingId' => 'b.booking_id',
    'bookableId' => 'b.bookable_id',
    'start' => 'b.start_date',
    'end' => 'b.end_date',
    'months' => 'b.available_on',
    'range' => array('b.start_date', 'b.end_date'),
  );

  /**
   * Mapping of sort field names to database fields
   * @var array
   */
  protected static $sortMapping = array(
    'bookingId' => 'b.booking_id',
    'bookableId' => 'b.bookable_id',
  );

  /**
   *
   * @param Booking $booking
   * @return \Booking
   * @throws DaoException
   */
  public function saveBooking(Booking $booking) {
    try {
      $booking->save();
      return $booking;
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
  public function getBookingById($id) {
    try {
      return Doctrine::getTable('Booking')->find($id);
    }
    catch (Exception $e) {
      throw new DaoException($e->getMessage(), $e->getCode(), $e);
    }
  }

  /**
   *
   * @param BookingSearchParameterHolder $parameterHolder
   * @return type
   */
  public function searchBooking(BookingSearchParameterHolder $parameterHolder) {
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

    $this->_getBookingListQuery($select, $query, $bindParams, $orderBy, $sortField, $sortOrder, $filters);
    $completeQuery = $select . ' ' . $query . ' ' . $orderBy;

    if (!is_null($offset) && !is_null($limit)) {
      $completeQuery .= ' LIMIT ' . $offset . ', ' . $limit;
    }

    return $this->_runSearch($completeQuery, $bindParams, $returnType);
  }

  /**
   *
   * @param type $bookableId
   * @param type $date
   * @return type
   * @throws DaoException
   */
  public function getBookableNextAvailableStartTime($bookableId, $date) {
    try {
      $q = Doctrine::getTable('Booking')
          ->createQuery('b')
          ->select('MAX(b.end_time) as next_start_time')
          ->where('b.bookable_id = ?', $bookableId)
          ->andWhere('? BETWEEN b.start_date AND b.end_date', $date)
          ->limit(1);
      $startTime = $q->execute(array(), Doctrine_Core::HYDRATE_SCALAR);
      return isset($startTime[0]) && isset($startTime[0]['b_next_start_time']) ? $startTime[0]['b_next_start_time'] : null;
    }
    catch (Exception $e) {
      throw new DaoException($e->getMessage(), $e->getCode(), $e);
    }
  }

  /**
   *
   * @param type $projectId
   * @return type
   * @throws DaoException
   */
  public function getBookingColorByProjectId($projectId) {
    try {
      $q = Doctrine::getTable('Booking')
          ->createQuery('b')
          ->select('b.booking_color as project_color')
          ->where('b.project_id = ?', $projectId)
          ->limit(1);
      $color = $q->execute(array(), Doctrine::HYDRATE_SCALAR);
      return $color;
    }
    catch (Exception $e) {
      throw new DaoException($e->getMessage(), $e->getCode(), $e);
    }
  }

  /**
   *
   * @param type $color
   * @return type
   * @throws DaoException
   */
  public function getProjectIdByBookingColor($color) {
    try {
      $q = Doctrine::getTable('Booking')
          ->createQuery('b')
          ->select('b.project_id as project_id')
          ->where('b.booking_color = ?', $color)
          ->limit(1);
      $id = $q->execute(array(), Doctrine::HYDRATE_SCALAR);
      return $id;
    }
    catch (Exception $e) {
      throw new DaoException($e->getMessage(), $e->getCode(), $e);
    }
  }

  /**
   *
   * @param type $id
   * @return boolean
   * @throws DaoException
   */
  public function deleteBookingById($id) {
    try {
      $q = Doctrine::getTable('Booking')
          ->createQuery('b')
          ->delete()
          ->where('b.booking_id = ?', $id);
      $result = $q->execute();
      return true;
    }
    catch (Exception $e) {
      throw new DaoException($e->getMessage(), $e->getCode(), $e);
    }
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
  private function _getBookingListQuery(&$select, &$query, array &$bindParams, &$orderBy, $sortField = null, $sortOrder = null, array $filters = null) {
    $select = ' SELECT DISTINCT b.booking_id AS bookingId, b.bookable_id AS bookableId, b.customer_id AS customerId, b.project_id AS projectId, ';
    $select .= ' b.duration as duration, b.booking_color as bookingColor, ';
    $select .= ' b.start_date AS startDate, b.end_date AS endDate, b.start_time AS startTime, b.end_time AS endTime, b.available_on AS availableOn ';
    $query = ' FROM ohrm_booking b ';
    $query .= ' LEFT JOIN ohrm_bookable_resource br ON br.bookable_id = b.booking_id ';
    $query .= ' LEFT JOIN ohrm_project p ON p.project_id = b.project_id ';
    $query .= ' LEFT JOIN ohrm_customer c ON c.customer_id = b.customer_id ';

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
        switch ($searchField) {
          case 'bookingId':
            $conditions[] = array('operator' => 'AND', 'condition' => " $field LIKE ? ");
            $bindParams[] = $searchBy;
            break;
          case 'bookableId':
            $conditions[] = array('operator' => 'AND', 'condition' => " $field = ? ");
            $bindParams[] = $searchBy;
            break;
          case 'start' :
            $conditions[] = array('operator' => 'AND', 'condition' => " $field >= ? ");
            $bindParams[] = $searchBy;
            break;
          case 'end':
            $conditions[] = array('operator' => 'AND', 'condition' => " $field <= ? ");
            $bindParams[] = $searchBy;
            break;
          case 'months':
            $months = explode(',', $searchBy);
            $conds = [];
            foreach ($months as $search) {
              $conds[] = " $field LIKE ? ";
              $bindParams[] = "%$search%";
            }
            $conditions[] = array('operator' => 'AND', 'condition' => " ( " . implode(" OR ", $conds) . " ) ");
            break;
          case 'range'://array('b.start_date','b.end_date')
            $conds = [];
            foreach ($field as $subfield) {
              $conds[] = " $subfield BETWEEN ? AND ? ";
              $bindParams[] = $searchBy['start'];
              $bindParams[] = $searchBy['end'];
            }
            $conditions[] = array('operator' => 'AND', 'condition' => " ( " . implode(" OR ", $conds) . " ) ");
          default :
            break;
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

    $order['b.bookable_id'] = 'asc';
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
        $query .= ' WHERE ' . $condition['condition'];
      }
      else {
        $query .= ' ' . $condition['operator'] . ' ' . $condition['condition'];
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
      case BookingSearchParameterHolder::RETURN_TYPE_OBJECT:
        $data = $this->_prepareResultAsObjectCollection($statement, $result);
        break;
      case BookingSearchParameterHolder::RETURN_TYPE_CALENDAR_EVENT:
        $data = $this->_prepareResultAsCalendarResource($statement, $result);
        break;
      case BookingSearchParameterHolder::RETURN_TYPE_ARRAY:
      default:
        $data = $statement->fetchAll();
        break;
    }
    return $data;
  }

  /**
   *
   * @param type $statement
   * @param type $result
   * @return Booking
   */
  private function _prepareResultAsObjectCollection(&$statement, &$result) {
    $bookings = new Doctrine_Collection(Doctrine::getTable('Booking'));

    if ($result) {
      while ($row = $statement->fetch()) {
        $booking = $this->getBookingById($row['bookingId']);
        $bookings[] = $booking;
      }
    }
    return $bookings;
  }

  /**
   *
   * @param type $statement
   * @param type $result
   * @return array
   */
  private function _prepareResultAsCalendarResource($statement, $result) {
    $events = array();
    if ($result) {
      $events = $this->_getBookingAsCalendarEvent($statement);
    }
    return $events;
  }

  /**
   *
   * @param type $statement
   */
  private function _getBookingAsCalendarEvent(&$statement) {
    $events = array();
    try {
      while ($row = $statement->fetch()) {
        $booking = $this->getBookingById($row['bookingId']);
        $resource = $booking->getBookingAsCalendarEvent();
        array_push($events, $resource);
      }
    }
    catch (Exception $e) {
      sfContext::getInstance()->getLogger()->err($e->getMessage());
      unset($events);
      $events = array();
    }
    return $events;
  }

}
