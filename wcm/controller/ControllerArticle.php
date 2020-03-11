<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/ManagerArticle.php";


class ControllerArticle extends Controller
{
    public function __construct()
    {
        $this->myManager = new ManagerArticle();
    }

    public function saveNewArtic() {
        try {
            $this->myManager->saveNewArtic($_SESSION['user-id'], $_POST['article-category'], $_POST['article-title'], $_POST['article-text']);
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function aLoadAllArticNamesOfCat($categoryName) {
        $articles = $this->myManager->aLoadAllArticNamesOfCat($categoryName);
        $this->dataForView['articleNames'] = $this->clearHTML($articles);

        extract($this->dataForView);
        require($_SERVER['DOCUMENT_ROOT']."/wcm/view/article-ul.phtml");
    }

    public function aLoadArticle($articleId) {
        $article = $this->myManager->aLoadArticle($articleId);
        $article = $this->clearHTML($article);
        print_r($article);
        return $article;
    }
}