<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/code-play/ManagerCssProperty.php";


class ControllerCssProperty extends Controller
{
    public function __construct()
    {
        $this->myManager = new ManagerCssProperty();
    }

    public function showCssProp($categForSelect) {
        $this->dataForView['cssCateg'] = $categForSelect;
        $this->loadCssPropMenu();

        if(isset($_SESSION['css-prop'])) {
            $this->editCssProp($_SESSION['css-prop']);
        }
        else {
            $this->showCssPropForm();
        }
    }

    public function saveNewProp() {
        try {
            $this->myManager->save(trim($_POST['prop-name']), $_POST['prop-description'], $_POST['prop-value'], $_POST['prop-categ']);
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function saveEditedProp() {
        try {
            $this->myManager->saveEdited($_SESSION['css-prop'], trim($_POST['prop-name']), $_POST['prop-description'], $_POST['prop-value'], $_POST['prop-categ']);
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function deleteProp() {
        try {
            $this->myManager->delete($_SESSION['css-prop']);
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function loadCssPropNames() {
        try {
            $allNames = $this->myManager->loadAllNames();
            return $this->clearHTML($allNames);
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
        return null;
    }

    public function aLoadPropDesc($propId) {
        try {
            $this->dataForView['property'] = $this->myManager->aLoadPropDesc($propId);
            extract($this->dataForView);
            require($_SERVER['DOCUMENT_ROOT']."/wcm/view/code-play/css-property-desc.phtml");
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    private function loadCssPropMenu() {
        $this->dataForView['cssProp'] = $this->loadCssPropNames();
        extract($this->dataForView);
        require($_SERVER['DOCUMENT_ROOT']."/wcm/view/code-play/css-property-ul.phtml");
    }

    private function showCssPropForm() {
        if (isset($_POST['prop-name'])) {
            $this->dataForView['cssProp'] = ['prop-name' => $_POST['prop-name'], 'prop-value' => $_POST['prop-value'], 'id_css_categ' => $_POST['prop-categ'], 'prop-description' => $_POST['prop-description'] ];
        }
        else {
            $this->dataForView['cssProp'] = ['prop-name' => "", 'prop-value' => "", 'id_css_categ' => "", 'prop-description' => ""];
        }
        extract($this->dataForView);
        require($_SERVER['DOCUMENT_ROOT']."/wcm/view/code-play/create-css-property.phtml");
    }

    private function editCssProp($cssPropId) {
        try {
            $this->dataForView['cssProp'] = $this->clearHTML($this->myManager->loadProperty($cssPropId));
            extract($this->dataForView);
            require($_SERVER['DOCUMENT_ROOT']."/wcm/view/code-play/edit-css-property.phtml");
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }
}