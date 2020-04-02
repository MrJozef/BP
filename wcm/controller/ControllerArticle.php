<?php


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
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function aLoadArticle($articleId) {
        $article = $this->myManager->aLoadArticle($articleId);

        if ($article != null) {
            //potrebujeme, aby v texte ostali zachovane html tagy
            $clearText = $article['text'];
            $article['text'] = "";
            $article = $this->clearHTML($article);
            $article['text'] = $clearText;

            return $article;
        }
        return null;
    }

    public function manageArtic($articId = null, $arraySelectCat, $arraySelectExam) {
        try {
            if ($articId != null) {
                $article = $this->myManager->loadOneArticle($articId);
                $this->dataForView['article'] = $this->clearHTML($article);
            }
            elseif (isset($_POST['title'])) {
                $this->dataForView['article'] = ['title' => trim($_POST['article-title']), 'text' => $_POST['article-text'], 'importance' => $_POST['article-importance']];
                echo($_POST['article-text']);
            }
            else {
                $this->dataForView['article'] = ['title' => "", 'text' => "Sem napíšte celý Váš článok... Môžete používať HTML značky.", 'importance' => ""];
            }

            $this->dataForView['categNames'] = $arraySelectCat;
            $this->dataForView['articExample'] = $arraySelectExam;

            extract($this->dataForView);
            require($_SERVER['DOCUMENT_ROOT'] . "/wcm/view/article-form.phtml");
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
            $this->myManager->saveEditedArticle($articId, $_POST['article-category'], $_POST['article-example'], trim($_POST['article-title']), $_POST['article-text'], $_POST['article-importance']);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }
}