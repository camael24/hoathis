<?php

    $host    = 'https://packagist.org/';
    $cache   = './Data/Temporary/';
    $include = array();

    $gPackage = (array_key_exists(1, $argv) ? $argv[1] : '');

    $pack = function ($package) use (&$include) {
        $package = strtolower($package);
        if ($package === '') {
            return $include;
        }
        else {
            if (array_key_exists($package, $include)) {
                return $include[$package];
            }
        }
    };

    $json = function ($url, $decode = false) use (&$host, &$cache) {

        $uri = $host . $url;
        $c   = $cache . md5($url) . '.json';
        echo '> GET : ' . $uri . "\n";
        $file = null;
        if (file_exists($c) === false) {

            $file = file_get_contents($uri);
            file_put_contents($c, $file);
        }
        else {
            $file = file_get_contents($c);
        }

        if ($decode === true) {
            return json_decode($file, true);
        }
        else {
            return $file;
        }

    };

    $main = $json('packages.json', true);


    foreach ($main['includes'] as $uri => $m) {
        $j        = $json($uri, true);
        $packages = $j['packages'];
        foreach ($packages as $name => $value) {
            $include[$name] = $value;
        }
    }

    print_r($pack($gPackage));