<?php

namespace Cms;

class IndexController
{

    public function indexAction()
    {
        $html = <<<HTML
<!DOCTYPE html>
<html xml:lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Hello World!</title>
    </head>
    <body>
        <p>Hello World!</p>
    </body>
</html>
HTML;

        echo $html;
    }

}
