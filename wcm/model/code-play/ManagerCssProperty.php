<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/Manager.php";

const PROP_NAME_MIN_LENGTH = 3;
const PROP_NAME_MAX_LENGTH = 50;
const PROP_NAME_LENGTH = "Dĺžka názvu vlastnosti musí byť medzi " . PROP_NAME_MIN_LENGTH . " a " . PROP_NAME_MAX_LENGTH . " znakmi!";

const PROP_DESC_MIN_LENGTH = 20;
const PROP_DESC_MAX_LENGTH = 65535;
const PROP_DESC_LENGTH = "Popis vlastnosti je nepovinný, ak sa ho však rozhodnete napísať, musí mať rozsah medzi " . PROP_DESC_MIN_LENGTH . " a " . PROP_DESC_MAX_LENGTH . " znakmi!";

const PROP_VALUE_MIN_LENGTH = 10;
const PROP_VALUE_MAX_LENGTH = 2000;
const PROP_VALUE_LENGTH = "Popis hodnôt musí byť dlhý minimálne " . PROP_VALUE_MIN_LENGTH . " a maximálne " . PROP_VALUE_MAX_LENGTH . " znakov!";

const ERROR_NEW_PROP = "Pri ukladaní novej vlastnosti chyba a vlastnosť sa neuložila!";
const ERROR_UPDATE_PROP = "Vlastnosť nebola pre chybu upravená!";
const ERROR_PROP_IN_USE = "Túto CSS vlastnosť nie je možné zmazať, pretože sa používa v príkladoch! Najskôr ich vymažte, alebo z nich odstránte túto vlastnosť.";
const ERROR_PROP_DELETE = "Pri mazaní CSS vlastnosti nastala chyba a tá sa pravdepodobne nezmazala!";

class ManagerCssProperty extends Manager
{
    public function loadAllNames() {
        $task = 'SELECT id_prop, prop_name FROM css_property ORDER BY prop_name';

        return DBWrap::selectAll($task, []);
    }

    public function loadProperty($propId) {
        $task = 'SELECT id_css_categ, prop_name, prop_description, prop_value FROM css_property WHERE id_prop = ? LIMIT 1';

        return DBWrap::selectOne($task, [$propId]);
    }

    public function save($name, $description = "", $value, $cssCateg) {

        if($this->checkValues($name, $description, $value)) {
            $task = 'INSERT INTO css_property (id_css_categ, prop_name, prop_description, prop_value) VALUES (?, ?, ?, ?)';
            $this->tryQueryDb($task, [$cssCateg, $name, $description, $value], ERROR_NEW_PROP);
        }
        else {
            $task = 'INSERT INTO css_property (id_css_categ, prop_name, prop_value) VALUES (?, ?, ?)';
            $this->tryQueryDb($task, [$cssCateg, $name, $value], ERROR_NEW_PROP);
        }
    }

    public function saveEdited($propId, $name, $description = "", $value, $cssCateg) {

        $this->checkValues($name, $description, $value);
        $task = 'UPDATE css_property SET id_css_categ = ?, prop_name = ?, prop_description = ?, prop_value = ? WHERE id_prop = ?';
        $this->tryQueryDb($task, [$cssCateg, $name, $description, $value, $propId], ERROR_UPDATE_PROP);
    }

    //navratova hodnota - TRUE, ak je description zadana, FALSE, ak zadana nie je
    private function checkValues($name, $description, $value) {
        $this->checkLengthWException($name, PROP_NAME_MAX_LENGTH, PROP_NAME_MIN_LENGTH, PROP_NAME_LENGTH);
        $this->checkLengthWException($value, PROP_VALUE_MAX_LENGTH, PROP_VALUE_MIN_LENGTH, PROP_VALUE_LENGTH);

        if($description != "") {
            $this->checkLengthWException($description, PROP_DESC_MAX_LENGTH, PROP_DESC_MIN_LENGTH, PROP_DESC_LENGTH);
            return true;
        }
        return false;
    }

    //vymazat vlastnost budeme moct len vtedy, ak nebude pouzita v ziadnom priklade -> avsak vymazavat css vlastnosti nedava zmysel tak ci tak
    public function delete($propId) {
        $task = 'SELECT id_use FROM css_use WHERE id_prop = ? LIMIT 1';

        //ak sa tato CSS vlastnost naozaj nikde nepouziva
        if(!DBWrap::queryUniversal($task, [$propId])) {
            $task = 'DELETE FROM css_property WHERE id_prop = ?';
            $this->tryQueryDb($task, [$propId], ERROR_PROP_DELETE);
        }
        else {
            throw new MyException(ERROR_PROP_IN_USE);
        }
    }

    public function aLoadPropDesc($propId) {
        $task = 'SELECT prop_name, prop_description FROM css_property WHERE id_prop = ? LIMIT 1';

        return DBWrap::selectOne($task, [$propId]);
    }
}