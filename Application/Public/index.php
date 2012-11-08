<?php

namespace {
    require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'Core.link.php';

    date_default_timezone_set('Europe/Paris');
    header('Content-type: text/html; charset=utf-8');

    from('Hoa')
        ->import('Database.Dal')
        ->import('Dispatcher.Basic')
        ->import('Dispatcher.Kit')
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

    /*
    *                                                     Controlleur, , Action    , Variable
    * http://sample.hoathis.hoa/                       => Project      , List      , $project  = sample
    * http://sample.hoathis.hoa/edit.html              => Project      , Edit      , $project  = sample
    * http://sample.hoathis.hoa/delete.html            => Project      , Delete    , $project  = sample
    * http://hoathis.hoa/thehawk                       => User         , Profil    , $user     = thehawk
    * http://hoathis.hoa/thehawk/edit.html             => User         , Edit      , $user     = thehawk
    * http://hoathis.hoa/thehawk/delete.html           => User         , Delete    , $user     = thehawk
    * http://hoathis.hoa/                              => Main         , Index
    * http://hoathis.hoa/search.html                   => Main         , Search
    * http://hoathis.hoa/a/                            => Admin        , Index
    * http://hoathis.hoa/a/users.html                  => Admin        , Users
    * http://hoathis.hoa/a/users/1                     => Admin        , Users     , $user     = thehawk
     */
    $router
        ->get('pp', '(?<project>[^\.]+)@/(?<_able>[^\.]+)\.html', 'project', 'list')
        ->get('p', '(?<project>[^\.]+)@/', 'project', 'list')


        ->get_post('api', 'www@/a/(?<_able>[^/])/(?<id>.*?)', 'admin')
        ->get('ap', 'www@/a/(?<_able>[^\.]+)\.html', 'admin')
        ->get('a', 'www@/a/', 'admin', 'index')


        ->get_post('up', 'www@/(?<user>[^/]{3,})/(?<_able>[^\.]+)\.html', 'user', 'index')
        ->get('u', 'www@/(?<user>[^/]{3,})/', 'user', 'profil')


        ->get_post('w', 'www@/(?<_able>[^\.]+)\.html', 'main')
        ->get('i', 'www@/', 'main', 'index');
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