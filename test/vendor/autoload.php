<?php



spl_autoload_register(function ($class) {
    // Reemplaza el namespace en la clase con el separador de directorios
    $class = str_replace('\\', '/', $class);

    // Define la ruta base para las clases
    $file = __DIR__ . '/' . $class . '.php';
    $file = str_replace("vendor/", "", $file);
    // Incluye el archivo si existe
    if (file_exists($file)) {
        require $file;
    } else {
        echo "un error al cargar $file";
    }
});