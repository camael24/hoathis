<?php
    try {
        require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'Core.link.php';

        date_default_timezone_set('Europe/Paris');
        header('Content-type: text/html; charset=utf-8');

        from('Hoa')
            ->import('Database.Dal')
            ->import('Dispatcher.Basic')
            ->import('Dispatcher.Kit')
            ->import('Router.Http')
            ->import('Session.~')
            ->import('Session.Flash')
            ->import('Xyl.~')
            ->import('Xyl.Interpreter.Html.~')
            ->import('File.ReadWrite')
            ->import('Http.Response')
            ->import('Mail.Message')
            ->import('Mail.Content.*')
            ->import('Mail.Transport.Smtp')
            ->import('Socket.Client')
            ->import('File.Read');

        from('Application')
            ->import('Model.*')
            ->import('Controller.Generic')
            ->import('Controller.Admin.*');


        from('Hoathis')
            ->import('Mail.~')
            ->import('Xyl.Interpreter.Html.~')
            ->import('Flash.Popup')
            ->import('Kit.Aggregator');


        $dispatcher = new \Hoa\Dispatcher\Basic();
        $router     = new \Hoa\Router\Http();

        $dispatcher->setKitName('Hoathis\Kit\Aggregator');

        Hoa\Database\Dal::initializeParameters(array(
                'connection.list.default.dal' => Hoa\Database\Dal::PDO,
                'connection.list.default.dsn' => 'sqlite:hoa://Data/Variable/Database/Hoathis.sqlite',
                'connection.autoload'         => 'default'
            )
        );

        Hoa\Mail\Message::setDefaultTransport(new Hoa\Mail\Transport\Smtp(new Hoa\Socket\Client('tcp://mail.hoa-project.net:587'), 'julien.clauzel@hoa-project.net', '***'));
        /*
        * Controlleur, , Action , Variable
        * http://sample.hoathis.hoa/ => Project , List , $project = sample
        * http://sample.hoathis.hoa/edit.html => Project , Edit , $project = sample
        * http://sample.hoathis.hoa/delete.html => Project , Delete , $project = sample
        * http://hoathis.hoa/thehawk => User , Profil , $user = thehawk
        * http://hoathis.hoa/thehawk/edit.html => User , Edit , $user = thehawk
        * http://hoathis.hoa/thehawk/delete.html => User , Delete , $user = thehawk
        * http://hoathis.hoa/ => Main , Index
        * http://hoathis.hoa/search.html => Main , Search
        * http://hoathis.hoa/a/ => Admin , Index
        * http://hoathis.hoa/a/users.html => Admin , Users
        * http://hoathis.hoa/a/users/1 => Admin , Users , $user = thehawk
        */
// $router->setSubdomainSuffix('hoathis');

        $router
            ->get_post('admin-user-id', '/a/user/(?<_able>[^-]+)-(?<id>[^\.]+)\.html', 'admin\user')
            ->get_post('admin-user', '/a/user/(?<_able>[^\.]+)\.html', 'admin\user')
            ->get_post('admin-project-id', '/a/project/(?<_able>[^-]+)-(?<id>[^\.]+)\.html', 'admin\project')
            ->get_post('admin-project', '/a/project/(?<_able>[^\.]+)\.html', 'admin\project')
            ->get_post('admin-home', '/a/', 'admin\main', 'index')

            ->get_post('api-able', '/api/(?<_able>[^\.]+)\.html', 'api', 'index')

            ->get_post('project-caller', '/p/(?<project>.*)/(?<_able>[^\.]+)\.html', 'project' , 'info')
            ->get('project-home', '/p/(?<project>.*)/info.html', 'project', 'info')
            ->get('project', '/p/(?<project>.*)/', 'project', 'info')

            ->get_post('user-caller', '/(?<user>[^/]{3,})/(?<_able>[^\.]+)\.html', 'user', 'index')
            ->get('user-home', '/(?<user>[^/]{3,})/', 'user', 'profil')

            ->get_post('home-caller', '/(?<_able>[^\.]+)\.html', 'main')
            ->get('home', '/', 'main', 'index');


        $view = new \Hoa\Xyl\Xyl(new Hoa\File\Read('hoa://Application/View/Main.xyl'), new Hoa\Http\Response\Response(), new Hoathis\Xyl\Interpreter\Html\Html(), $router);


        $dispatcher->dispatch($router, $view

        );
    } catch (\Hoa\Core\Exception\Exception $e) {
        if ($e instanceof \Hoa\Session\Exception\Expired) {

            if (array_key_exists('QUERY_STRING', $_SERVER))
                $hash = urlencode($_SERVER['QUERY_STRING']);

            $flash = \Hoathis\Flash\Popup::getInstance();
            $flash->info('You have been disconnect, because you don`t use your session since long time ...');
            header('Location:/connect.html?redirect=' . $hash);
            exit();
        } else {

            $complement = '';
            if (array_key_exists('QUERY_STRING', $_SERVER))
                $complement = $_SERVER['QUERY_STRING'];

            $read = new \Hoa\File\ReadWrite('hoa://Data/Variable/Log/Exception.log');
            $read->writeAll('[' . date('d/m/Y H:i:s') . '] ' . $complement . ' ' . str_replace(array(
                        "\n",
                        "\t",
                        "\r"
                    ), '', $e->raise(true)
                ) . "\n"
            );

            $flash = \Hoathis\Flash\Popup::getInstance();
            $flash->error($e->getFormattedMessage() . '<br />An error has open, an report has been sent to the administrator thanks for your help');
            header('Location:/');
            exit();
        }


    }


