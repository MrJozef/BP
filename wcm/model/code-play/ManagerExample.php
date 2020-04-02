<?php

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

const ERROR_NEW_USE = "Pri vkladaní novej vlastnosti do príkladu nastala chyba a vlastnosť sa neuložila!";
const ERROR_UPDATE_USE = "Vlastnosť nebola pre chybu upravená!";
const ERROR_PROP_USE = "Pri mazaní CSS vlastnosti nastala chyba a tá sa pravdepodobne nezmazala!";

const ERROR_NEW_EXAM = "Pri nového príkladu nastala chyba a príklad sa správne neuložil!";
const ERROR_UPDATE_EXAM = "Príklad pre chybu zostal neupravený!";
const ERROR_PROP_EXAM = "Pri mazaní príkladu nastala chyba!";

const ERROR_ART_EXAMPLE = "Pokus odstrániť prepojenia s článkami zlyhal, príklad sa nevymazal!";

//Táto trieda obsluhuje tabuľky css_use a súčasne example - nemá zmysel ich oddeľovať, kedže css_use existuje len kvôli tab. example
class ManagerExample extends Manager
{
    public function loadExampleNames() {
        $task = 'SELECT id_example, exam_name FROM example ORDER BY exam_name';
        return DBWrap::selectAll($task, []);
    }

    public function loadExample($idExample) {
        $task = 'SELECT exam_name, exam_description, exam_code FROM example WHERE id_example = ?';
        $outputArray['example'] = DBWrap::selectOne($task, [$idExample]);

        $task = 'SELECT id_use, id_prop, for_element FROM css_use JOIN css_property USING(id_prop) WHERE id_example = ? ORDER BY for_element, prop_name';
        $outputArray['cssUse'] = DBWrap::selectAll($task, [$idExample]);
        return $outputArray;
    }

    public function saveUse($propId, $exampleId, $forElement) {
        $this->checkLengthWException($forElement, FOR_ELEMENT_MAX_LENGTH, FOR_ELEMENT_MIN_LENGTH, FOR_ELEMENT_LENGTH);

        $task = 'INSERT INTO css_use (id_prop, id_example, for_element) VALUES (?, ?, ?)';
        $this->tryQueryDb($task, [$propId, $exampleId, $forElement],ERROR_NEW_USE);
    }

    //je možné meniť iba for_element a id_prop
    public function saveEditedUse($useId, $propId, $forElement) {
        $this->checkLengthWException($forElement, FOR_ELEMENT_MAX_LENGTH, FOR_ELEMENT_MIN_LENGTH, FOR_ELEMENT_LENGTH);

        $task = 'UPDATE css_use SET id_prop = ?, for_element = ? WHERE id_use = ?';
        $this->tryQueryDb($task, [$propId, $forElement, $useId], ERROR_UPDATE_USE);
    }

    public function deleteUse($useId) {
        $task = 'DELETE FROM css_use WHERE id_use = ?';
        $this->tryQueryDb($task, [$useId], ERROR_PROP_USE);
    }

    //táto metóda vracia id novovloženého príkladu - aby sme mohli v session nastaviť
    public function saveNewExample($name, $desc = "", $code) {
        $this->checkValues($name, $desc, $code);

        $task = 'INSERT INTO example (exam_name, exam_description, exam_code) VALUES (?, ?, ?)';
        $this->tryQueryDb($task, [$name, $desc, $code], ERROR_NEW_EXAM);

        $task = 'SELECT id_example FROM example WHERE exam_name = ? LIMIT 1';
        $outputArray = DBWrap::selectOne($task, [$name]);
        return $outputArray['id_example'];
    }

    public function saveEditedExample($name, $desc = "", $code, $exampleId) {
        $this->checkValues($name, $desc, $code);

        $task = 'UPDATE example SET exam_name = ?, exam_description = ?, exam_code = ? WHERE id_example = ?';
        $this->tryQueryDb($task, [$name, $desc, $code, $exampleId], ERROR_UPDATE_EXAM);
    }

    public function deleteExample($exampleId) {
        //musíme odstrániť aj prípadné referencie v článkoch
        $task = 'UPDATE article SET id_example = 0 WHERE id_example = ?';
        $this->tryQueryDb($task, [$exampleId], ERROR_ART_EXAMPLE);

        $task = 'DELETE FROM css_use WHERE id_example = ?';
        $this->tryQueryDb($task, [$exampleId], ERROR_PROP_EXAM);

        $task = 'DELETE FROM example WHERE id_example = ?';
        $this->tryQueryDb($task, [$exampleId], ERROR_PROP_EXAM);
    }

    public function aLoadExample($exampleId) {
        $task = 'SELECT exam_code, exam_description FROM example WHERE id_example = ? LIMIT 1';
        return DBWrap::selectOne($task, [$exampleId]);
    }

    public function aLoadArrayProperties($exampleId) {
        $task = 'SELECT DISTINCT prop.prop_name, us.for_element 
                 FROM css_use us
                 JOIN css_property prop USING(id_prop)
                 WHERE us.id_example = ?
                 ORDER BY  us.for_element, prop.prop_name';

        $arrayFromDb = DBWrap::selectAll($task, [$exampleId]);
        $outputArray = [];

        foreach ($arrayFromDb as $prop) {
            $outputArray[$prop['for_element']][$prop['prop_name']] = "";
        }
        return $outputArray;
    }

    public function aLoadExampleProperties($exampleId) {
        $task = 'SELECT us.id_prop, us.for_element, prop.prop_name, prop.prop_value, cat.categ_name
                 FROM css_use us
                 JOIN css_property prop USING(id_prop)
                 JOIN css_category cat USING(id_css_categ)
                 WHERE us.id_example = ?
                 ORDER BY us.for_element, cat.categ_name, prop.prop_name';


        return DBWrap::selectAll($task, [$exampleId]);
    }

    private function checkValues($name, $desc, $code) {
        $this->checkLengthWException($name, EXAMPLE_NAME_MAX_LENGTH, EXAMPLE_NAME_MIN_LENGTH, EXAMPLE_NAME_LENGTH);
        $this->checkLengthWException($code, EXAMPLE_CODE_MAX_LENGTH, EXAMPLE_CODE_MIN_LENGTH, EXAMPLE_CODE_LENGTH);
        if($desc != "") {
            $this->checkLengthWException($desc, EXAMPLE_DESC_MAX_LENGTH, EXAMPLE_DESC_MIN_LENGTH, EXAMPLE_DESC_LENGTH);
        }
    }
}