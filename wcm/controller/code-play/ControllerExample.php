<?php


class ControllerExample extends Controller
{
    public function __construct()
    {
        $this->myManager = new ManagerExample();
    }

    //if $forAdmin == false -> zobrazi sa iba ul sablona (pouzivane v code-play.php)
    public function showExamples($forAdmin = true) {
        try {
            $this->dataForView['examples'] = $this->clearHTML($this->myManager->loadExampleNames());

            extract($this->dataForView);
            if ($forAdmin) {
                require($_SERVER['DOCUMENT_ROOT'] . "/wcm/view/code-play/example-menu.phtml");
            }
            else {
                require($_SERVER['DOCUMENT_ROOT'] . "/wcm/view/code-play/example-ul.phtml");
            }
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function loadExamplesNames() {
        try {
            return $this->clearHTML($this->myManager->loadExampleNames());
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
        return null;
    }

    public function manageExample($propArrayForSelect) {
        try {
            if (isset($_SESSION['example'])) {
                $dataFromManager = $this->myManager->loadExample($_SESSION['example']);
                $this->dataForView['example'] = $dataFromManager['example'];
                $this->dataForView['cssUse'] = $dataFromManager['cssUse'];
            } else {
                if (isset($_POST['example-name'])) {
                    $this->dataForView['example'] = ['exam_name' => $_POST['example-name'], 'exam_description' => $_POST['example-description'], 'exam_code' => $_POST['example-code'], 'exam_js' => $_POST['example-js'], 'exam_js_description' => $_POST['example-js-desc']];
                }
                else {
                    $this->dataForView['example'] = ['exam_name' => "", 'exam_description' => "", 'exam_code' => "", 'exam_js' => "", 'exam_js_description' => ""];
                }
                $this->dataForView['cssUse'] = [];
            }

            $this->dataForView['cssProp'] = $propArrayForSelect;

            extract($this->dataForView);
            require($_SERVER['DOCUMENT_ROOT'] . "/wcm/view/code-play/example-form.phtml");
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function saveNewUse() {
        try {
            $this->myManager->saveUse($_POST['id_prop'], $_SESSION['example'], $_POST['for-element']);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function saveEditedUse() {
        try {
            $this->myManager->saveEditedUse($_POST['use-edit'], $_POST['id_prop'], $_POST['for-element']);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function deleteUse() {
        try {
            $this->myManager->deleteUse($_POST['use-delete']);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function saveNewExample() {
        try {
            return $this->myManager->saveNewExample($_POST['example-name'], $_POST['example-description'], $_POST['example-code'], $_POST['example-js'], $_POST['example-js-desc']);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
        return false;
    }

    public function saveEditedExample() {
        try {
            $this->myManager->saveEditedExample($_POST['example-name'], $_POST['example-description'], $_POST['example-code'], $_POST['example-js'], $_POST['example-js-desc'], $_SESSION['example']);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function deleteExample() {
        try {
            $this->myManager->deleteExample($_SESSION['example']);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function aLoadExample($exampleId) {
        try {
            return $this->myManager->aLoadExample($exampleId);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
        return null;
    }

    public function aLoadArrayProperties($exampleId) {
        try {
            return $this->myManager->aLoadArrayProperties($exampleId);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
        return null;
    }

    public function aLoadExampleProperties($exampleId) {
        try {
            $this->dataForView['propList'] = $this->myManager->aLoadExampleProperties($exampleId);

            extract($this->dataForView);
            require($_SERVER['DOCUMENT_ROOT']."/wcm/view/code-play/example-prop-list.phtml");
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
        return null;
    }

    public function aLoadExampleJs($exampleId) {
        try {
            $this->dataForView['examJs'] = $this->clearHTML($this->myManager->aLoadExampleJs($exampleId));
            $this->dataForView['examId'] = $exampleId;

            extract($this->dataForView);
            require($_SERVER['DOCUMENT_ROOT'] . "/wcm/view/code-play/example-js-area.phtml");
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
        return null;
    }

    public function aLoadJsDesc($exampleId) {
        try {
            $this->dataForView['jsDesc'] = $this->myManager->aLoadJsDesc($exampleId);

            extract($this->dataForView);
            require($_SERVER['DOCUMENT_ROOT']."/wcm/view/code-play/example-js-desc.phtml");
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
        return null;
    }
}