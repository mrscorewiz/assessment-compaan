<?php
namespace Frame;

define ('APP_PATH', __DIR__);

\spl_autoload_register(function ($class) {
    $file = __DIR__ . '/src/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    return false;
});

class Registry {

    private $registry;

    public function __construct()
    {
        $this->registry = [];
    }

    public function __set(string $key, $value) : self
    {
        $this->registry[$key] = $value;
        return $this;
    }

    public function __get(string $key)
    {
        return $this->registry[$key] ?? null;
    }

    public function has(string $key) : bool
    {
        return array_key_exists($key, $this->registry);
    }
}

class View {

    private $templateFolder;

    public function __construct(string $templateFolder)
    {
        $this->templateFolder = $templateFolder;
    }

    public function render(string $templatePath) : string
    {
        \ob_start();
        include $this->templateFolder . $templatePath . '.phtml';
        return \ob_get_clean();
    }
}

class Service {

    public static function create(array $args) : self
    {
        return new static($args);
    }
}

class ServiceContainer extends Registry {

    public function set(string $key, $value) : self
    {
        $service = null;

        if ($value instanceof Service) {
            $service = $value;
        } else if (is_array($value) && count($value) == 2) {
            list ($serviceClassName, $params) = $value;

            if (class_exists($serviceClassName)) {
                $ref = new ReflectionClass($serviceClassName);

                while ($class = $ref->getParentClass()) {
                    $ref = $class;
                }

                if ($ref->getName() == __NAMESPACE__ . 'Service') {
                    $service = call_user_func($serviceClassName . '::create', $params);
                }
            }
        }

        if (!$service) {
            throw new Error('Invalid service value');
        }

        return parent::set($key, $service);
    }

}

class Controller {
    protected $srv, $view;

    public function __construct(ServiceContainer $srv, View $view)
    {
        $this->srv = $srv;
        $this->view = $view;
    }
}




