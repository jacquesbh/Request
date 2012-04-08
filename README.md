# How to use

Just start a php server like this:

    php -S localhost:8080 Request.php

* * *

Then you can retrieve all request information like this:

    $request = \Jbh\Request::getInstance();
    
    // The module name
    $request->getModuleName();
    
    // The controller name
    $request->getControllerName();
    
    // The action name
    $request->getActionName();
    
    // All params
    $request->getParams();

    // A param like id
    // If id doesn't exist the method will return NULL by default
    $request->getParam('id');

    // With default value!
    $request->getParam('id', 1);

Have fun !
