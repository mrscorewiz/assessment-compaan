<?php
    session_start();
    session_set_cookie_params(['httponly' => true]);

    require_once __DIR__ . '/../private/base.php';

    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL);

    $srv = new \Frame\ServiceContainer;

    $config = json_decode(file_get_contents(APP_PATH . '/config/config.json'));

    $router = new \Frame\Routing\Router;

    $router->pushRoute('^/results$', 'main::results', [], 'results');
    $router->pushRoute('^/form$', 'main::form');
    $router->pushRoute('^/login\?redirect=(.*)$', 'auth::login', ['redirect']);

    $db = new \Frame\Db\Mysql($config->db->main);
    $srv->formHandler = new \App\Service\FormHandler($db);
    $srv->authorization = new \App\Service\Authorization();
    $srv->router = $router;

    $view = new \Frame\View(APP_PATH . '/tpl/');

    $dispatchInfo = $router->resolve($_SERVER['REQUEST_URI']);

    if (!$dispatchInfo) {
        header('HTTP/1.0 404 Not Found');
        echo '404 Not Found';
        exit;
    }
    else {
        list ($controllerClass, $method) = explode('::', $dispatchInfo[0]);
        $controllerClass = '\\App\\Controller\\' . ucfirst($controllerClass);
        $controller = new $controllerClass($srv, $view);
        echo $controller->$method($dispatchInfo[1]);
        exit;
    }


