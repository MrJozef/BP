<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/Manager.php";

const FOR_ELEMENT_MIN_LENGTH = 1;
const FOR_ELEMENT_MAX_LENGTH = 60;


const EXAMPLE_NAME_MIN_LENGTH = 3;
const EXAMPLE_NAME_MAX_LENGTH = 60;

const EXAMPLE_DESC_MIN_LENGTH = 20;
const EXAMPLE_DESC_MAX_LENGTH = 1000;

const EXAMPLE_CODE_MIN_LENGTH = 10;
const EXAMPLE_CODE_MAX_LENGTH = 3000;

//Táto trieda obsluhuje tabuľky css_use a súčasne example - nemá zmysel ich oddeľovať, kedže css_use existuje len kvôli tab. example
class ManagerExample extends Manager
{
    public function loadAllNames() {
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
}