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

    public function manageCat($catId = null) {
        try {
            if($catId != null) {
                $category = $this->myManager->loadOneCat($catId);
                $this->dataForView['categ'] = $this->clearHTML($category);
            }
            elseif(isset($_POST['category-name'])) {
                $this->dataForView['categ'] = ['name' => trim($_POST['category-name']), 'description' => $_POST['category-desc'], 'visibility' => $_POST['category-visibility']];
            }
            else {
                $this->dataForView['categ'] = ['name' => "", 'description' => "Sem napíšte popis ku kategórii - aké články obsahuje... Môžete používať HTML značky.", 'visibility' => "0"];
            }


            extract($this->dataForView);
            require($_SERVER['DOCUMENT_ROOT'] . "/wcm/view/category-form.phtml");
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function saveEditCat($catId) {
        try {
            $this->myManager->saveEditedCat($catId, trim($_POST['category-name']), $_POST['category-desc'], $_POST['category-visibility']);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function deleteCat() {
        try {
            $this->myManager->deleteCatWithArticles($_SESSION['category']);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function onlyLoadCatSelect() {
        $this->loadAllNamesOfCat();
        return $this->dataForView['categNames'];
    }

    public function loadCatSelect() {
        $this->loadAllNamesOfCat();
        extract($this->dataForView);
        require($_SERVER['DOCUMENT_ROOT']."/wcm/view/category-select.phtml");
    }

    public function aLoadDescCat($categId) {
        $this->dataForView = $this->myManager->aLoadDescCat($categId);
        extract($this->dataForView);
        require($_SERVER['DOCUMENT_ROOT']."/wcm/view/category-section.phtml");
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