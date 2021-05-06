<?php

declare(strict_types=1);

namespace App\Controller;

session_start();

use App\Exception\ConfigurationException;
use App\Exception\StorageException;
use App\Request;
use App\Model\AppModel;
use App\View;

require_once("classes/Exception/ConfigurationException.php");
require_once("classes/Exception/StorageException.php");
require_once("classes/Model/AppModel.php");
require_once("classes/View.php");
require_once("classes/Request.php");

abstract class AbstractController
{
    protected Request $request;
    protected AppModel $appModel;
    protected View $view;

    private static array $configuration = [];

    public static function initConfiguration(array $configuration): void
    {
        self::$configuration = $configuration;
    }

    public function __construct(Request $request)
    {
        if (empty(self::$configuration['db'])) {
            throw new ConfigurationException("Configuration error");
        }
        $this->request = $request;
        $this->appModel = new AppModel(self::$configuration['db']);
        $this->view = new View();
    }

    public function run()
    {
        try {
            $task = $this->checktask();
            if (!method_exists($this, $task)) {
                $task = 'start';
            }
            $this->$task();
        } catch (StorageException $e) {
            exit("Błąd");
        }
    }

    private function checktask(): string
    {
        return $this->request->paramGet('task');
    }

    private function start(): void
    {
        $this->view->renderView('start');
    }
}
