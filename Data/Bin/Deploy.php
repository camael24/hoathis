<?php

namespace {

    function cin($out = null) {

        if (null !== $out)
            cout($out);

        return trim(fgets(STDIN));
    }

    function cinq($out = null) {

        $in = strtolower(cin($out));

        switch ($in) {

            case 'y':
            case 'ye':
            case 'yes':
            case 'yeah': // hihi
                return true;
                break;

            default:
                return false;
        }
    }

    function set($question) {
        $in = strtolower(cin($question));
        if ($in == '')
            return false;


        return $in;

    }

    function cout($out) {

        return fwrite(STDOUT, $out);
    }

    function check($out, $test, $die = true) {

        if (false === $test) {

            cout('✖  ' . $out);

            if (true === $die)
                exit;
            else
                return;
        }

        cout('✔  ' . $out);

        return;
    }

    cout('** Deployment procedure **' . "\n\n");

    $go = cinq('Do you want change your Hoa directory [y/n]');

    if (true === $go) {
        include_once "WhereisHoa.php";

    }

    $index = file_get_contents('../../Application/Public/index.php');

    $dsn = set('Write your DSN [mysql:host=127.0.0.1;dbname=hoathis] >');
    if ($dsn !== false) {
        $index = str_replace('mysql:host=127.0.0.1;dbname=hoathis', $dsn, $index);
    }

    $login = set('Write your login [camael] > ');
    if ($login !== false) {
        $index = str_replace('camael', $login, $index);
    }
    $password = set('Write your password > ');
    if ($password !== false) {
        $index = str_replace('toor', $password, $index);
    }

    $index = str_replace('// $router->setSubdomainSuffix(\'foo\');', '$router->setSubdomainSuffix(\'hoathis\');', $index);


    file_put_contents('../../Application/Public/index.php', $index);

}
