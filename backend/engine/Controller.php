<?php

namespace engine;

abstract class Controller
{
    public function __construct(protected readonly Request $request)
    {
    }

    abstract public function actionIndex(): Response;

    /**
     * @param string $template_name
     * @param array $data
     * @return Response
     */
    public function render(string $template_name, array $data = []): Response
    {
        $controller_path = explode('\\', static::class);
        $controller_name = str_replace('Controller', '', array_pop($controller_path));
        $template = lcfirst($controller_name)  . DIRECTORY_SEPARATOR . $template_name;
        $view = new View();
        return new Response($view->render($template, $data));
    }

    public function json(array $data): Response
    {
        header('Content-Type: application/json; charset=utf-8');
        return new Response(json_encode($data));
    }
}