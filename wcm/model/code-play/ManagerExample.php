<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/Manager.php";

const FOR_ELEMENT_MIN_LENGTH = 1;
const FOR_ELEMENT_MAX_LENGTH = 60;
const FOR_ELEMENT_LENGTH = "Selektor musí obsahovať " . FOR_ELEMENT_MIN_LENGTH . " až " . FOR_ELEMENT_MAX_LENGTH . " znakov!";

const EXAMPLE_NAME_MIN_LENGTH = 3;
const EXAMPLE_NAME_MAX_LENGTH = 60;
const EXAMPLE_NAME_LENGTH = "Meno príkladu musí obsahovať " . EXAMPLE_NAME_MIN_LENGTH . " až " . EXAMPLE_NAME_MAX_LENGTH . " znakov!";

const EXAMPLE_DESC_MIN_LENGTH = 20;
const EXAMPLE_DESC_MAX_LENGTH = 1000;
const EXAMPLE_DESC_LENGTH = "Popis príkladu je nepovinný, ak sa ho však rozhodnete napísať, musí mať rozsah medzi " . EXAMPLE_DESC_MIN_LENGTH . " a " . EXAMPLE_DESC_MAX_LENGTH . " znakmi!";

const EXAMPLE_CODE_MIN_LENGTH = 10;
const EXAMPLE_CODE_MAX_LENGTH = 3000;
const EXAMPLE_CODE_LENGTH = "HTML kód príkladu musí obsahovať " . EXAMPLE_CODE_MIN_LENGTH . " až " . EXAMPLE_CODE_MAX_LENGTH . " znakov!";

//Táto trieda obsluhuje tabuľky css_use a súčasne example - nemá zmysel ich oddeľovať, kedže css_use existuje len kvôli tab. example
class ManagerExample extends Manager
{
    public function loadExampleNames() {
        $task = 'SELECT id_example, exam_name FROM example ORDER BY exam_name';
        return DBWrap::selectAll($task, []);
    }

    public function loadExample($idExample) {       //todo jednym selectom toto vyriesit?
        $task = 'SELECT exam_name, exam_description, exam_code FROM example WHERE id_example = ?';
        $outputArray['example'] = DBWrap::selectOne($task, [$idExample]);

        $task = 'SELECT id_use, id_prop, for_element FROM css_use WHERE id_example = ?';
        $outputArray['cssUse'] = DBWrap::selectAll($task, [$idExample]);
        return $outputArray;
    }

    public function saveUse($propId, $exampleId, $forElement) {
        $this->checkLengthWException($forElement, FOR_ELEMENT_MAX_LENGTH, FOR_ELEMENT_MIN_LENGTH, FOR_ELEMENT_LENGTH);

        $task = 'INSERT INTO css_use (id_prop, id_example, for_element) VALUES (?, ?, ?)';
        DBWrap::queryUniversal($task, [$propId, $exampleId, $forElement]);
    }

    //je možné meniť iba for_element a id_prop
    public function saveEditedUse($useId, $propId, $forElement) {
        $this->checkLengthWException($forElement, FOR_ELEMENT_MAX_LENGTH, FOR_ELEMENT_MIN_LENGTH, FOR_ELEMENT_LENGTH);

        $task = 'UPDATE css_use SET id_prop = ?, for_element = ? WHERE id_use = ?';
        DBWrap::queryUniversal($task, [$propId, $forElement, $useId]);
    }

    public function deleteUse($useId) {
        $task = 'DELETE FROM css_use WHERE id_use = ?';
        DBWrap::queryUniversal($task, [$useId]);
    }

    //táto metóda vracia id novovloženého príkladu - aby sme mohli v session nastaviť
    public function saveNewExample($name, $desc = "", $code) {
        $this->checkValues($name, $desc, $code);

        $task = 'INSERT INTO example (exam_name, exam_description, exam_code) VALUES (?, ?, ?)';
        DBWrap::queryUniversal($task, [$name, $desc, $code]);

        $task = 'SELECT id_example FROM example WHERE exam_name = ? LIMIT 1';
        $outputArray = DBWrap::selectOne($task, [$name]);
        return $outputArray['id_example'];
    }

    public function saveEditedExample($name, $desc = "", $code, $exampleId) {
        $this->checkValues($name, $desc, $code);

        $task = 'UPDATE example SET exam_name = ?, exam_description = ?, exam_code = ? WHERE id_example = ?';
        DBWrap::queryUniversal($task, [$name, $desc, $code, $exampleId]);
    }

    public function deleteExample($exampleId) {
        $task = 'DELETE FROM css_use WHERE id_example = ?';
        DBWrap::queryUniversal($task, [$exampleId]);

        $task = 'DELETE FROM example WHERE id_example = ?';
        DBWrap::queryUniversal($task, [$exampleId]);
    }

    public function aLoadExample($exampleId){
        $task = 'SELECT exam_code, exam_description FROM example WHERE id_example = ? LIMIT 1';
        return DBWrap::selectOne($task, [$exampleId]);
    }

    public function aLoadExampleProperties($exampleId) {

    }

    private function checkValues($name, $desc, $code) {
        $this->checkLengthWException($name, EXAMPLE_NAME_MAX_LENGTH, EXAMPLE_NAME_MIN_LENGTH, EXAMPLE_NAME_LENGTH);
        $this->checkLengthWException($code, EXAMPLE_CODE_MAX_LENGTH, EXAMPLE_CODE_MIN_LENGTH, EXAMPLE_CODE_LENGTH);
        if($desc != "") {
            $this->checkLengthWException($desc, EXAMPLE_DESC_MAX_LENGTH, EXAMPLE_DESC_MIN_LENGTH, EXAMPLE_DESC_LENGTH);
        }
    }
}