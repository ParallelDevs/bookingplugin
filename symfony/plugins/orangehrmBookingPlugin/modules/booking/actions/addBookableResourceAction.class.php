<?php

/**
 * Description of addBookableResourceAction
 *
 * @author amora
 */
class addBookableResourceAction extends baseBookingAction {

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
    $postArray = array();
    if ($request->isMethod('post')) {
      $postArray = $request->getPostParameters();
      unset($postArray['_csrf_token']);
      $_SESSION['addBookablePost'] = $postArray;
    }

    if (isset($_SESSION['addBookablePost'])) {
      $postArray = $_SESSION['addBookablePost'];
    }

    $this->setForm(new BookableResourceForm(array(), array(), true));

    if ($this->getUser()->hasFlash('templateMessage')) {
      unset($_SESSION['addBookablePost']);
      list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
    }


    if ($request->isMethod('post')) {
      $this->form->bind($request->getPostParameters(), $request->getFiles());
      $posts = $this->form->getValues();

      if ($this->form->isValid()) {
        $this->_bookableResourceExists($this->form->getValue('empNum'));

        try {
          $empNumber = $this->form->save();
          $this->getUser()->setFlash('success', __(TopLevelMessages::SAVE_SUCCESS));
          $this->redirect('booking/viewBookableResources');
        }
        catch (Exception $e) {
          print($e->getMessage());
        }
      }
    }
  }

  /**
   *
   * @param type $empNum
   */
  private function _bookableResourceExists($empNum) {
    if (!empty($empNum)) {
      $bookable = $this->getBookableService()->getBookableResource($empNum);

      if ($bookable instanceof BookableResource) {
        $this->getUser()->setFlash('warning', __('Failed To Save: Resource Already Exists'));
        $this->redirect('booking/addBookableResource');
      }
    }
  }

}
