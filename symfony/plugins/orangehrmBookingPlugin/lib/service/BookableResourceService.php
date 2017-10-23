<?php

/**
 * Description of BookableResourceService
 *
 * @author amora
 */
class BookableResourceService extends BaseService {

  private $bookableDao;

  /**
   *
   */
  public function __construct() {

  }

  /**
   *
   * @param BookableResourceDao $bookableDao
   */
  public function setBookableDao(BookableResourceDao $bookableDao) {
    $this->bookableDao = $bookableDao;
  }

  /**
   *
   * @return type
   */
  public function getBookableDao() {
    if (!$this->bookableDao instanceof BookableResourceDao) {
      $this->bookableDao = new BookableResourceDao();
    }
    return $this->bookableDao;
  }

  /**
   *
   * @param BookableResource $bookableResource
   * @return type
   */
  public function saveBookableResource(BookableResource $bookableResource) {
    return $this->getBookableDao()->saveBookableResource($bookableResource);
  }

  /**
   *
   * @param String $empNumber
   * @return type
   */
  public function getBookableResource($empNumber) {
    return $this->getBookableDao()->getBookableResource($empNumber);
  }

  /**
   *
   * @param Integer $id
   * @return type
   */
  public function getBookableResourceById($id) {
    return $this->getBookableDao()->getBookableResourceById($id);
  }

  /**
   *
   * @return type
   */
  public function getBookableResourceList() {
    return $this->getBookableDao()->getBookableResourceList();
  }

  /**
   *
   * @param BookableSearchParameterHolder $parameterHolder
   * @return type
   */
  public function searchBookableResourceList(BookableSearchParameterHolder $parameterHolder) {
    return $this->getBookableDao()->searchBookableResources($parameterHolder);
  }

}
