<?php

namespace Jbh
{
    class Request
    {

        static $_instance;

        protected $_module;
        protected $_controller;
        protected $_action;

        protected $_params = array();

        private function __construct()
        {}

        static public function getInstance()
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new self;
            }
            return self::$_instance;
        }

        public function setModuleName($module)
        {
            $this->_module = $module;
            return $this;
        }

        public function issetParam($name)
        {
            return isset($this->_params[$name]);
        }

        public function getParam($name, $default = null)
        {
            return isset($this->_params[$name]) ? $this->_params[$name] : $default;
        }

        public function setParam($name, $value)
        {
            $this->_params[$name] = $value;
            return $this;
        }

        public function getParams()
        {
            return $this->_params;
        }

        public function setControllerName($controller)
        {
            if (is_null($controller)) {
                $controller = 'index';
            }
            $this->_controller = $controller;
            return $this;
        }

        public function setActionName($action)
        {
            if (is_null($action)) {
                $action = 'index';
            }
            $this->_action = $action;
            return $this;
        }

        public function getModuleName()
        {
            return $this->_module;
        }

        public function getControllerName()
        {
            return $this->_controller;
        }

        public function getActionName()
        {
            return $this->_action;
        }
    }
}

namespace
{
    include_once 'Exception.php';

    if (is_file('./Request/Before.php')) {
        include_once './Request/Before.php';
    }

    use Jbh\Exception;

    define('JBH_ROUTER_APP_PATH', './app/');

    $request = parse_url($_SERVER['REQUEST_URI'])['path'];

    if (is_file('.' . $request)) {
        return false;
    } elseif (preg_match('`^(/[^/]*)+$`', $request)) {
        if (empty($request)) {
            $routes = array();
        } else {
            $routes = explode('/', $request = trim($request, '/'));
        }

        $request = \Jbh\Request::getInstance();
        $request->setModuleName($a = array_shift($routes));
        $request->setControllerName(array_shift($routes));
        $request->setActionName(array_shift($routes));

        if (count($routes)) {
            while (null !== ($v = array_shift($routes))) {
                $request->setParam($v, array_shift($routes));
            }
        }

        if (null === $request->getModuleName() || !is_dir(JBH_ROUTER_APP_PATH)) {
            throw new Exception('Page not found', 404);
        }

        $controllerName = ucfirst($request->getControllerName()) . 'Controller';
        $controllerFilename = JBH_ROUTER_APP_PATH
            . $request->getModuleName()
            . '/controllers/'
            . $controllerName . '.php';
        if (!is_file($controllerFilename)) {
            throw new Exception('Controller not found');
        }

        require_once $controllerFilename;
        $controllerClassName = ucfirst($request->getModuleName()) . '\\' . $controllerName;
        $controller = new $controllerClassName($request);

        $action = $request->getActionName();
        $methodName = $action . 'Action';
        if (!method_exists($controller, $methodName)) {
            throw new Exception('Action not found');
        }

        $controller->$methodName();
    }

    if (is_file('./Request/After.php')) {
        include_once './Request/After.php';
    }
}
