<?php

namespace {
    require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'Core.link.php';

    date_default_timezone_set('Europe/Paris');
    header('Content-type: text/html; charset=utf-8');

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

    from('Application')
        ->import('Model.*');

    from('Hoathis')
        ->import('Kit.Aggregator');

    \Hoa\Session::start();

    $dispatcher = new \Hoa\Dispatcher\Basic();
    $router     = new \Hoa\Router\Http();

    $dispatcher->setKitName('Hoathis\Kit\Aggregator');

    \Hoa\Database\Dal::initializeParameters(array(
        'connection.list.default.dal'      => 'Pdo',
        'connection.list.default.dsn'      => 'mysql:host=localhost;dbname=hoathis',
        'connection.list.default.username' => 'root',
        'connection.list.default.password' => 'toor', // DEV Mdp , F### :D
        'connection.autoload'              => 'default'
    ));

    // TODO : ici c'est un vrai boxons idem dans les fonctions des controleurs
    $router
        ->get('h', '/m/(?<page>.*?)', 'Hoathis', 'Index')
        ->get_post('a', '/admin/(?<_able>.*?)/(?<page>.*?)', 'admin', 'index')
        ->get_post('ai', '/admin/(?<_able>.*?)', 'admin', 'index')
        ->get_post('e', '/e/(?<page>.*?)', 'Hoathis', 'Edit')
        ->get_post('u', '/u/(?<user>.*?).html', 'Main', 'User')
        ->get_post('c', '/a/(?<action>.*?).html', 'Main', 'Index')
        ->get_post('f', '/(?<action>.*?).html', 'Front', 'Connexion')
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


}