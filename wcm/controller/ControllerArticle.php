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
            $this->myManager->saveNewArtic($_SESSION['user-id'], $_POST['article-category'], $_POST['article-example'], trim($_POST['article-title']), $_POST['article-text'], $_POST['article-importance']);
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function aLoadAllArticNamesOfCat($categoryName) {
        try {
            $articleNames = $this->myManager->aLoadAllArticNamesOfCat($categoryName);
            if (!empty($articleNames)) {
                $this->dataForView['articleNames'] = $this->clearHTML($articleNames);

                extract($this->dataForView);
                require($_SERVER['DOCUMENT_ROOT']."/wcm/view/article-ul.phtml");
            }
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());       //todo toto fixnut, ked nemam nic v db ziadny clanok -> chyba dopytu
        }
    }

    public function aLoadArticle($articleId) {
        $article = $this->myManager->aLoadArticle($articleId);

        //potrebujeme, aby v texte ostali zachovane html tagy
        $clearText = $article['text'];
        $article['text'] = "";
        $article = $this->clearHTML($article);
        $article['text'] = $clearText;

        return $article;
    }

    public function editArtic($articId, $arraySelectCat, $arraySelectExam) {
        try {
            $article = $this->myManager->loadOneArticle($articId);
            $this->dataForView['articEdit'] = $this->clearHTML($article);
            $this->dataForView['categNames'] = $arraySelectCat;
            $this->dataForView['articExample'] = $arraySelectExam;

            extract($this->dataForView);
            require($_SERVER['DOCUMENT_ROOT']."/wcm/view/edit-article.phtml");
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function deleteArtic($articId) {
        try {
            $this->myManager->deleteArticle($articId);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function saveEditArtic($articId) {
        try {
            $this->myManager->saveEditedArticle($articId, $_POST['article-category'], $_POST['article-example'], trim($_POST['edit-article-title']), $_POST['edit-article-text'], $_POST['article-importance']);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }
}