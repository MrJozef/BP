<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/Manager.php";



//Táto trieda obsluhuje tabuľky css_use a súčasne example - nemá zmysel ich oddeľovať, kedže css_use existuje len kvôli tab. example
class ManagerExample extends Manager
{
    public function loadAllNames() {
        $task = 'SELECT id_example, exam_name FROM example ORDER BY exam_name';
        return DBWrap::selectAll($task, []);
    }
}