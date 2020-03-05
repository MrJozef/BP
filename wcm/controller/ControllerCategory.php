<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/ManagerCategory.php";


class ControllerCategory extends Controller
{
    public function __construct()
    {
        $this->myManager = new ManagerCategory();
    }

    public function loadNamesOfCat() {
        $result = $this->myManager->getOnlyNames();

        $this->dataForView['categNames'] = $this->clearHTML($result);
        extract($this->dataForView);
        require($_SERVER['DOCUMENT_ROOT']."/wcm/view/category-ul.phtml");
    }
}