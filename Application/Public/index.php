<?php

namespace {
    require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'Core.link.php';

    date_default_timezone_set('Europe/Paris');
    header('Content-type: text/html; charset=utf-8');

    try {
        from('Hoa')
            ->import('Database.Dal')
            ->import('Dispatcher.Basic')
            ->import('Router.Http')
            ->import('Session.~')
            ->import('Session.QNamespace')
            ->import('Xyl.~')
            ->import('Xyl.Interpreter.Html.~')
            ->import('File.Read')
            ->import('Http.Response');

        $dispatcher = new \Hoa\Dispatcher\Basic();
        $router = new \Hoa\Router\Http();


        $router
            ->get('h', '/l/(?<page>.*?).html', 'Hoathis', 'Index')
            ->get_post('c', '/(?<action>.*?).html', 'Main', 'Index')
            ->get('i', '/', 'Main', 'Index');

        $view = new \Hoa\Xyl\Xyl(
            new Hoa\File\Read('hoa://Application/View/Main.xyl'),
            new Hoa\Http\Response\Response(),
            new Hoa\Xyl\Interpreter\Html\Html(),
            $router
        );


        $dispatcher->dispatch(
            $router,
            $view

        );


    } catch (Hoa\Core\Exception\Exception $e) {
        echo '<pre>';

        echo 'Message : ' . $e->getFormattedMessage() . '<br /><br />';
        echo $e->getTraceAsString() . '<br /><hr />';
        echo $e->raise(true) . '<br />';

        echo '</pre>';
    }


}
