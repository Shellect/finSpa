<?php

namespace app\controllers;

use app\models\Article;
use engine\Controller;
use engine\Response;
use PDO;

class ArticlesController extends Controller
{
    public function actionIndex(): Response
    {
        sleep(2);
        $articles = Article::find()->take(10)->all(PDO::FETCH_ASSOC);
        return $this->json($articles);
    }

    public function actionCreate(): Response
    {
        $title = $this->request->postParams['title'] ?? '';
        $content = $this->request->postParams['content'] ?? '';
        $authToken = $this->request->postParams['authToken'] ?? '';
        if ($title && $content && $authToken) {
            $article = new Article();
            $article['title'] = $title;
            $article['content'] = $content;
            $articleId = $article->save();
            if (isset($articleId)) {
                return $this->json(['status' => 'Success', 'articleID' => $articleId]);
            }
            return new Response("");
        }
        return $this->json(['status' => 'Fail', 'message' => 'Internal server error']);
    }
}