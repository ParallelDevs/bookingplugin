<?php

/**
 * Description of BookableSearchParameterHolder
 *
 * @author amora
 */
class BookableSearchParameterHolder extends SearchParameterHolder {

  const RETURN_TYPE_OBJECT = 1;
  const RETURN_TYPE_ARRAY = 2;
  const RETURN_TYPE_CALENDAR_RESOURCE = 3;

  protected $filters;
  protected $returnType = self::RETURN_TYPE_OBJECT;

  public function __construct() {
    $this->orderField = 'empNumber';
  }

  public function setFilters($filters) {
    $this->filters = $filters;
  }

  public function getFilters() {
    return $this->filters;
  }

  public function getReturnType() {
    return $this->returnType;
  }

  public function setReturnType($returnType) {
    $this->returnType = $returnType;
  }

}
