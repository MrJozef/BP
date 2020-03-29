<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/Manager.php";

const ARTICLE_MIN_LENGTH = 50;
const ARTICLE_MAX_LENGTH = 65535;
const ERROR_ARTICLE_LENGTH = "Dĺžka článku musí byť medzi " . ARTICLE_MIN_LENGTH . " a " . ARTICLE_MAX_LENGTH . " znakmi!";

const ART_TITLE_MIN_LENGTH = 6;
const ART_TITLE_MAX_LENGTH = 200;
const ERROR_TITLE_LENGTH = "Dĺžka nadpisu článku musí byť medzi " . ART_TITLE_MIN_LENGTH . " a " . ART_TITLE_MAX_LENGTH . " znakmi!";

const ERROR_ART_NEW = "Pri ukladaní článku nastala chyba a článok sa neuložil!";
const ERROR_ART_UPDATE = "Pri ukladaní zmien nastala chyba a zmeny v článku sa neuložili!";
const ERROR_ART_DELETE = "Pokus vymazať článok zlyhal a článok v databáze zostáva!";

class ManagerArticle extends Manager
{
    public function saveNewArtic($idAuthor, $idCateg, $title, $text, $importance) {
        $this->checkLengthWException($title, ART_TITLE_MAX_LENGTH, ART_TITLE_MIN_LENGTH, ERROR_TITLE_LENGTH);
        $this->checkLengthWException($text, ARTICLE_MAX_LENGTH, ARTICLE_MIN_LENGTH, ERROR_ARTICLE_LENGTH);

        $actualDate = date(RAW_DATE_FORMAT);

        $task = 'INSERT INTO article (id_category, id_author, title, text, importance, date_creation) VALUES (?, ?, ?, ?, ?, ?)';
        $this->tryQueryDb($task, [$idCateg, $idAuthor, $title, $text, $importance, $actualDate], ERROR_ART_NEW);
    }

    //používame aj v administrátorskom prostredí, nielen pre Ajax
    public function aLoadAllArticNamesOfCat($categoryName) {
        $task = 'SELECT id_article, title, importance FROM article WHERE id_category = ?';
        return DBWrap::selectAll($task, [$categoryName]);
    }

    public function aLoadArticle($articleId) {
        $task = 'SELECT id_author, title, text, importance, date_creation, date_edit FROM article WHERE id_article = ?';
        return DBWrap::selectOne($task, [$articleId]);
    }

    //tato funkcia sa pouziva pri editacii clankov v admin. rozhrani
    public function loadOneArticle($articleId) {
        $task = 'SELECT id_category, title, text, importance FROM article WHERE id_article = ?';
        return DBWrap::selectOne($task, [$articleId]);
    }

    public function saveEditedArticle($articleId, $categoryId, $title, $text, $importance) {
        $this->checkLengthWException($title, ART_TITLE_MAX_LENGTH, ART_TITLE_MIN_LENGTH, ERROR_TITLE_LENGTH);
        $this->checkLengthWException($text, ARTICLE_MAX_LENGTH, ARTICLE_MIN_LENGTH, ERROR_ARTICLE_LENGTH);

        $actualDate = date(RAW_DATE_FORMAT);

        $task = 'UPDATE article SET id_category = ?, title = ?, text = ?, importance = ?, date_edit = ? WHERE id_article = ?';

        $this->tryQueryDb($task, [$categoryId, $title, $text, $importance, $actualDate, $articleId], ERROR_ART_UPDATE);
    }

    public function deleteArticle($articleId) {
        $task = 'DELETE FROM article WHERE id_article = ?';
        $this->tryQueryDb($task, [$articleId], ERROR_ART_DELETE);
    }
}