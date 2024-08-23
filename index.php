<?php

use Classes\ResponseClass;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use db;
use Twig\Error\LoaderError;

require_once './vendor/autoload.php';
spl_autoload_register(function ($class) {
    // Reemplaza el namespace en la clase con el separador de directorios
    $class = str_replace('\\', '/', $class);

    // Define la ruta base para las clases
    $file = __DIR__ . '/' . $class . '.php';

    // Incluye el archivo si existe

    if (file_exists($file)) {
        require $file;
    }
});


$conexion = new db();
$pdo = $conexion->getConnection();


$loader = new FilesystemLoader("templates");
$twig = new Environment($loader);




$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;


if ($pdo === null) {
    echo "No se pudo establecer la conexión a la base de datos.";
    exit;
}

if ($action == 'create') {

    try {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (!empty($_POST['name'])) {

                $stmt = $pdo->prepare("INSERT INTO users (name) VALUES (:name)");
                $stmt->execute(['name' => $_POST['name']]);
                header('Location: index.php');
                exit;
            } else {
                ResponseClass::throw($twig, "Esta intentando crear un usuario sin nombre");
                exit;
            }
        }
        ResponseClass::responseLayout($twig, "", "create.html.twig");
    } catch (LoaderError $e) {
        ResponseClass::throw($twig, "");
    } catch (Exception $e) {
        ResponseClass::throw($twig, $e->getMessage());
    }
} elseif ($action == 'edit' && $id) {


    try {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!empty($_POST['name'])) {
                $stmt = $pdo->prepare("UPDATE users SET name = :name WHERE id = :id");
                $stmt->execute(['name' => $_POST['name'], 'id' => $id]);
                header('Location: index.php');
                exit;
            } else {
                ResponseClass::throw($twig, "Esta intentando crear un usuario sin nombre");
            }
        }

        ResponseClass::responseLayout($twig, $user, "edit.html.twig");
    } catch (LoaderError $e) {
        ResponseClass::throw($twig, "");
    } catch (Exception $e) {
        ResponseClass::throw($twig, $e->getMessage());
    }
} elseif ($action == 'delete' && $id) {


    try {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute(['id' => $id]);
            header('Location: index.php');
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        ResponseClass::responseLayout($twig, $user, "delete.html.twig");
    } catch (LoaderError $e) {
        ResponseClass::throw($twig, "");
    } catch (Exception $e) {
        ResponseClass::throw($twig, $e->getMessage());
    }
} else {

    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo $twig->render('index.html.twig', ['users' => $users]);
}