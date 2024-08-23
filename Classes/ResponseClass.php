<?php

namespace Classes;

class ResponseClass
{

    public static function throw($twig, $message = "Plantilla no encontrada.")
    {
        echo $twig->render('/layouts/error.html.twig', ['error_message' => $message]);
    }

    public static function responseLayout($twig, $data = null, $view = "")
    {
        echo $twig->render($view, ['data' => $data]);
    }
}
