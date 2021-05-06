<?php

declare(strict_types=1);

namespace App;

use App\Controller\AbstractController;
use App\Controller\AppController;
use App\Request;
use App\Exception\AppException;
use App\Exception\ConfigurationException;

require_once("./classes/Exception/AppException.php");
require_once("./classes/Exception/ConfigurationException.php");
require_once("classes/Controller/AbstractController.php");
require_once("classes/Controller/AppController.php");
require_once("classes/Request.php");
require_once("Utils/debug.php");

$configuration = require_once("./config/config.php");

$request = new Request($_GET, $_POST, $_SERVER);

try {
    AbstractController::initConfiguration($configuration);
    (new AppController($request))->run();
} catch (ConfigurationException $e) {
    echo '<h1>Wystąpił błąd aplikacji</h1>';
    echo 'Problem z aplikacją, proszę spróbować za chwilę. Proszę skontaktować się z administratorem';
} catch (AppException $e) {
    echo '<h1>Wystąpił błąd aplikacji</h1>';
    echo '<h4>' . $e->getMessage() . '</h4>';
} catch (\Throwable $e) {
    echo '<h1>Wystąpił błąd aplikacji</h1>';
    echo '<h4>' . $e->getMessage() . '</h4>';
}
