<?php

const CSS_CAT_NAME_MIN_LENGTH = 3;
const CSS_CAT_NAME_MAX_LENGTH = 100;
const ERROR_CSS_CAT_NAME_LENGTH = "Dĺžka názvu kategórie musí byť medzi " . CSS_CAT_NAME_MIN_LENGTH . " a " . CSS_CAT_NAME_MAX_LENGTH . " znakmi!";

const ERROR_CSS_CAT_NEW = "Pri ukladaní novej kategórie pre CSS vlastnosti nastala chyba a kategória sa neuložila!";
const ERROR_CSS_CAT_UPDATE = "Pri ukladaní zmien nastala chyba a zmeny CSS kategórie sa neuložili!";
const ERROR_CSS_CAT_CONTAINS = "Túto CSS kategóriu nie je možné zmazať, pretože obsahuje CSS vlastnosti! Najskôr ich presuňte do inej kategórie alebo vymažte.";
const ERROR_CSS_CAT_DELETE = "Pri mazaní kategórie CSS vlastností nastala chyba a pravdepodobne sa nezmazala!";


class ManagerCssCategory extends Manager
{
    public function loadAllNames() {
        $task = 'SELECT id_css_categ, categ_name FROM css_category ORDER BY categ_name';
        return DBWrap::selectAll($task, []);
    }

    public function save($name) {
        $this->checkLengthWException($name, CSS_CAT_NAME_MAX_LENGTH, CSS_CAT_NAME_MIN_LENGTH, ERROR_CSS_CAT_NAME_LENGTH);

        $task = 'INSERT INTO css_category (categ_name) VALUES(?)';
        $this->tryQueryDb($task, [$name], ERROR_CAT_NEW);
    }

    public function loadCategory($categId) {
        $task = 'SELECT categ_name FROM css_category WHERE id_css_categ = ? LIMIT 1';

        return DBWrap::selectOne($task, [$categId]);
    }

    public function saveEdited($categId, $name) {
        $this->checkLengthWException($name, CSS_CAT_NAME_MAX_LENGTH, CSS_CAT_NAME_MIN_LENGTH, ERROR_CSS_CAT_NAME_LENGTH);

        $task = 'UPDATE css_category SET categ_name = ? WHERE id_css_categ = ?';
        $this->tryQueryDb($task, [$name, $categId], ERROR_CSS_CAT_UPDATE);
    }

    //nepredpokladam, ze by sa niekedy mali mazat css vlastnosti (color, background...), preto ich nikdy nebudeme chciet zmazat spolu s kategoriou,
    //musia byt presunute niekam inam najskor
    public function delete($categId) {
        $task = 'SELECT id_prop FROM css_property WHERE id_css_categ = ? LIMIT 1';

        //ak neexistuje k tejto kategorii priradena CSS vlastnost
        if(!DBWrap::queryUniversal($task, [$categId])) {
            $task = 'DELETE FROM css_category WHERE id_css_categ = ?';
            $this->tryQueryDb($task, [$categId], ERROR_CSS_CAT_DELETE);
        }
        else {
            throw new MyException(ERROR_CSS_CAT_CONTAINS);
        }
    }
}