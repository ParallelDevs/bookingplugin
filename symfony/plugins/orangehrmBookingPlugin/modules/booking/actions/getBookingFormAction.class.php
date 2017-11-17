<?php

/**
 * Description of getBookingFormAction
 *
 * @author amora
 */
class getBookingFormAction extends baseBookingUIAction {

  /**
   *
   * @param sfForm $form
   */
  public function setForm(sfForm $form) {
    if (is_null($this->form)) {
      $this->form = $form;
    }
  }

  /**
   *
   * @param type $request
   * @return type
   */
  public function execute($request) {
    $this->bookingPermissions = $this->getDataGroupPermissions('booking_bookings');
    if ($this->bookingPermissions->canCreate() || $this->bookingPermissions->canUpdate()) {
      $params = $this->getBookingFormParams($request);

      $this->setForm(new BookingForm(array(), $params, true));

      $partialParams = array(
        'form' => $this->form,
        'actionForm' => '',
        'buttons' => array(
        )
      );
      return $this->renderPartial('modalBookingForm', $partialParams);
    }
  }

  /**
   * 
   * @param type $request
   * @return type
   */
  private function getBookingFormParams(&$request) {
    $bookableId = $request->getParameter('bookableId');
    $params = [
      'bookingId' => $request->getParameter('bookingId'),
      'bookableId' => $bookableId,
      'bookableName' => $request->getParameter('bookableName'),
      'startDate' => $request->getParameter('startDate'),
      'endDate' => $request->getParameter('endDate'),
      'bookableSelectable' => empty($bookableId) ? true : false,
      'minStartTime' => $request->getParameter('minStartTime'),
      'maxEndTime' => $request->getParameter('maxEndTime'),
      'workingDays' => $request->getParameter('workingDays'),
    ];
    return $params;
  }

}
