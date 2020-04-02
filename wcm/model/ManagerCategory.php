<?php

const CAT_NAME_MIN_LENGTH = 3;
const CAT_NAME_MAX_LENGTH = 100;
const ERROR_CAT_NAME = "Dĺžka názvu kategórie musí byť medzi " . CAT_NAME_MIN_LENGTH . " a " . CAT_NAME_MAX_LENGTH . " znakmi!";

const CAT_DESCRIPT_MIN_LENGTH = 20;
const CAT_DESCRIPT_MAX_LENGTH = 2000;
const ERROR_CAT_DESCRIPT = "Dĺžka popisu kategórie musí byť medzi " . CAT_DESCRIPT_MIN_LENGTH . " a " . CAT_DESCRIPT_MAX_LENGTH . " znakmi!";

const ERROR_CAT_NEW = "Pri ukladaní kategórie nastala chyba a kategória sa neuložila!";
const ERROR_CAT_UPDATE = "Pri ukladaní zmien nastala chyba a zmeny kategórie sa neuložili!";
const ERROR_CAT_DELETE = "Pri mazaní kategórie nastala chyba!";


class ManagerCategory extends Manager
{
    public function save($name, $desc, $visibility) {
        $this->checkLengthWException($name, CAT_NAME_MAX_LENGTH, CAT_NAME_MIN_LENGTH, ERROR_CAT_NAME);
        $this->checkLengthWException($desc, CAT_DESCRIPT_MAX_LENGTH, CAT_DESCRIPT_MIN_LENGTH, ERROR_CAT_DESCRIPT);

        $task = 'INSERT INTO category (name, description, visibility) VALUES(?, ?, ?)';

        $this->tryQueryDb($task, [$name, $desc, $visibility], ERROR_CAT_NEW);
    }

    public function getNamesAndId() {
        $task = 'SELECT id_category, name FROM category WHERE visibility = 1 ORDER BY id_category DESC';

        return DBWrap::selectAll($task, []);
    }

    public function getAllNamesAndId() {
        $task = 'SELECT id_category, name FROM category ORDER BY id_category DESC';

        return DBWrap::selectAll($task, []);
    }

    public function loadOneCat($categId) {
        $task = 'SELECT name, description, visibility FROM category WHERE id_category = ? LIMIT 1';

        return DBWrap::selectOne($task, [$categId]);
    }

    public function aLoadDescCat($categId) {
        $task = 'SELECT description FROM category WHERE id_category = ? LIMIT 1';
        $dbOutput = DBWrap::selectOne($task, [$categId]);

        if($dbOutput) {
            return $dbOutput;
        }
        return [];
    }

    public function saveEditedCat($categId, $name, $desc, $visibility) {
        $this->checkLengthWException($name, CAT_NAME_MAX_LENGTH, CAT_NAME_MIN_LENGTH, ERROR_CAT_NAME);
        $this->checkLengthWException($desc, CAT_DESCRIPT_MAX_LENGTH, CAT_DESCRIPT_MIN_LENGTH, ERROR_CAT_DESCRIPT);

        $task = 'UPDATE category SET name = ?, description = ?, visibility = ? WHERE id_category = ?';

        $this->tryQueryDb($task, [$name, $desc, $visibility, $categId], ERROR_CAT_UPDATE);
    }

    public function deleteCatWithArticles($categId) {
        $task = 'DELETE FROM article WHERE id_category = ?';
        $this->tryQueryDb($task, [$categId], ERROR_CAT_DELETE);

        $task = 'DELETE FROM category WHERE id_category = ?';
        $this->tryQueryDb($task, [$categId], ERROR_CAT_DELETE);
    }
}