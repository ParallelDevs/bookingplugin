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
    $this->bookablePermissions = $this->getDataGroupPermissions('booking_resources');
    if ($this->bookablePermissions->canRead()) {
      $request->setParameter('initialActionName', 'viewBookableResources');
      $bookableId = $request->getParameter('bookableId');

      $params = array(
        'bookableId' => $bookableId,
      );

      $this->setForm(new BookableResourceForm(array(), $params, true));

      if ($request->isMethod('post')) {
        $this->processPost($request);
      }
    }
  }

  /**
   * 
   * @param type $request
   */
  protected function processPost(&$request) {
    if ($this->bookablePermissions->canCreate() || $this->bookablePermissions->canUpdate()) {
      $this->form->bind($request->getPostParameters(), $request->getFiles());

      if ($this->form->isValid()) {
        try {
          $this->form->save();
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
