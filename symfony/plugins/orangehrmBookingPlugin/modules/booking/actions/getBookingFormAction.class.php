<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of getBookingForm
 *
 * @author pdev
 */
class getBookingFormAction extends baseBookingAction {

    /**
     *
     * @param sfForm $form
     */
    public function setForm(sfForm $form) {
        if (is_null($this->form)) {
            $this->form = $form;
        }
    }

    public function execute($request) {
        $bookableId = $request->getParameter('bookableId');
        $params = array(
          'bookingId' => $request->getParameter('bookingId'),
          'bookableId' => $bookableId,
          'bookableName' => $request->getParameter('bookableName'),
          'startDate' => $request->getParameter('startDate'),
          'endDate' => $request->getParameter('endDate'),
          'bookableSelectable' => empty($bookableId) ? true : false,
          'minStartTime' => $request->getParameter('minStartTime'),
          'maxEndTime' => $request->getParameter('maxEndTime'),
        );

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
