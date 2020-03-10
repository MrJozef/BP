<?php

include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/Manager.php";

const ARTICLE_MIN_LENGTH = 50;
const ARTICLE_MAX_LENGTH = 65535;
const ERROR_ARTICLE_LENGTH = "Dĺžka článku musí byť medzi " . ARTICLE_MIN_LENGTH . " a " . ARTICLE_MAX_LENGTH . " znakmi!";

const ART_TITLE_MIN_LENGTH = 6;
const ART_TITLE_MAX_LENGTH = 200;
const ERROR_TITLE_LENGTH = "Dĺžka nadpisu článku musí byť medzi " . ART_TITLE_MIN_LENGTH . " a " . ART_TITLE_MAX_LENGTH . " znakmi!";

const ERROR_ART_NEW = "Pri ukladaní článku nastala chyba a článok sa neuložil!";

class ManagerArticle extends Manager
{
    public function saveNewArtic($idAuthor, $idCateg, $title, $text) {
        $this->checkLengthWException($title, ART_TITLE_MAX_LENGTH, ART_TITLE_MIN_LENGTH, ERROR_TITLE_LENGTH);
        $this->checkLengthWException($text, ARTICLE_MAX_LENGTH, ARTICLE_MIN_LENGTH, ERROR_ARTICLE_LENGTH);

        $task = 'INSERT INTO article (id_category, id_author, title, text, date_creation) VALUES (?, ?, ?, ?, CURRENT_DATE())';

        $this->tryQueryDb($task, [$idCateg, $idAuthor, $title, $text], ERROR_ART_NEW);
    }
}