<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/Manager.php";

const CAT_NAME_MIN_LENGTH = 3;
const CAT_NAME_MAX_LENGTH = 100;
const ERROR_CAT_NAME = "Dĺžka názvu kategórie musí byť medzi " . CAT_NAME_MIN_LENGTH . " a " . CAT_NAME_MAX_LENGTH . " znakmi!";

const CAT_DESCRIPT_MIN_LENGTH = 20;
const CAT_DESCRIPT_MAX_LENGTH = 2000;
const ERROR_CAT_DESCRIPT = "Dĺžka popisu kategórie musí byť medzi " . CAT_DESCRIPT_MIN_LENGTH . " a " . CAT_DESCRIPT_MAX_LENGTH . " znakmi!";

const ERROR_CAT_NEW = "Pri ukladaní kategórie nastala chyba a kategória sa neuložila!";


class ManagerCategory extends Manager
{
    public function save($name, $descript, $visibility) {
        $this->checkLengthWException($name, CAT_NAME_MAX_LENGTH, CAT_NAME_MIN_LENGTH, ERROR_CAT_NAME);
        $this->checkLengthWException($descript, CAT_DESCRIPT_MAX_LENGTH, CAT_DESCRIPT_MIN_LENGTH, ERROR_CAT_DESCRIPT);

        $task = 'INSERT INTO category (name, description, visibility) VALUES(?, ?, ?)';

        $this->tryQueryDb($task, [$name, $descript, $visibility], ERROR_CAT_NEW);
    }

    public function getNamesAndId() {
        $task = 'SELECT id_category, name FROM category WHERE visibility = 1 ORDER BY id_category DESC';

        return DBWrap::selectAll($task, []);
    }
}