<?php
include "Controller.php";
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/ManagerCategory.php";


class ControllerCategory extends Controller
{
    public function __construct()
    {
        $this->myManager = new ManagerCategory();
    }

    public function loadNamesOfCat() {
        $result = $this->myManager->loadOnlyNames();
        return $this->clearHTML($result);
    }
}