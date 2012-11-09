<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Julien
 * Date: 09/11/12
 * Time: 10:08
 * To change this template use File | Settings | File Templates.
 */

define ('LESS', '/usr/local/lib/lessphp');

echo 'Bootstrap compile in php' . "\n";
if (!file_exists(LESS . '/lessc.inc.php'))
    exit('Dependance lessphp' . "\n" . '                       [FAILED]' . "\n");


require(LESS . '/lessc.inc.php');

$less = new lessc();

$compile = function ($file, $dir, $filename) use (&$less) {
    $file   = realpath($file);
    $output = realpath($dir) . '/' . $filename;
    echo $output . "\n";
    if (!file_exists($file))
        exit('Input file ' . $file . ' not exists' . "\n" . '                       [FAILED]' . "\n");
    if (file_exists($output)) {
        unlink($output);
        echo '# Remove old file !' . "\n";

    }


    $outputF = $less->compileFile($file);
    file_put_contents($output, $outputF);

    echo '# Compile ' . $file . ' > ' . $output . "\n";

};

$compile('Bootstrap/less/bootstrap.less', '../../../Application/Public/Classic/Css/', 'bootstrap.css');
$compile('Bootstrap/less/responsive.less', '../../../Application/Public/Classic/Css/', 'bootstrap-responsive.css');





