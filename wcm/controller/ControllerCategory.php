<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/ManagerCategory.php";


class ControllerCategory extends Controller
{
    public function __construct()
    {
        $this->myManager = new ManagerCategory();
    }

    public function saveNewCat() {
        try {
            $this->myManager->save(trim($_POST['category-name']), $_POST['category-desc'], $_POST['category-visibility']);
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function loadCatMenu() {
        try {
            if(strpos($_SERVER['REQUEST_URI'], 'admin') !== false) {
                $this->loadAllNamesOfCat();
            }
            else {
                $this->loadNamesOfCat();
            }

            extract($this->dataForView);
            require($_SERVER['DOCUMENT_ROOT']."/wcm/view/category-ul.phtml");
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function editCat($catId) {
        try {
            $category = $this->myManager->loadOneCat($catId);
            $this->dataForView['categEdit'] = $this->clearHTML($category);

            extract($this->dataForView);
            require($_SERVER['DOCUMENT_ROOT']."/wcm/view/edit-category.phtml");
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function saveEditCat($catId) {
        try {
            $this->myManager->saveEditedCat($catId, trim($_POST['edit-category-name']), $_POST['edit-category-desc'], $_POST['edit-category-visibility']);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function loadCatSelect() {
        $this->loadAllNamesOfCat();
        extract($this->dataForView);
        require($_SERVER['DOCUMENT_ROOT']."/wcm/view/category-select.phtml");
    }

    private function loadNamesOfCat() {
        $result = $this->myManager->getNamesAndId();
        $this->dataForView['categNames'] = $this->clearHTML($result);
    }

    //teda aj tie, ktoré sú skryté
    private function loadAllNamesOfCat() {
        $result = $this->myManager->getAllNamesAndId();
        $this->dataForView['categNames'] = $this->clearHTML($result);
    }
}