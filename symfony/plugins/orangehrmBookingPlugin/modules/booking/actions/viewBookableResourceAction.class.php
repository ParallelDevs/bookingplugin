<?php

/**
 * Description of viewBookableResourceAction
 *
 * @author amora
 */
class viewBookableResourceAction extends baseBookingAction {

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
   */
  public function execute($request) {

    $bookableId = $request->getParameter('bookableId');

    $params = array(
      'bookableId' => $bookableId,
    );

    $this->setForm(new BookableResourceForm(array(), $params, true));

    if ($request->isMethod('post')) {
      $this->form->bind($request->getPostParameters(), $request->getFiles());

      if ($this->form->isValid()) {
        try {
          $bookableId = $this->form->save();
          $this->getUser()->setFlash('success', __(TopLevelMessages::SAVE_SUCCESS));
          $this->redirect("booking/viewBookableResources");
        }
        catch (Exception $e) {
          print($e->getMessage());
        }
      }
    }
  }

}
