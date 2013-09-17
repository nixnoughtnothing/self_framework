<?php

require 'core/ClassLoader.class.php';

// ClassLoaderクラスをインスタンス化して変数$loaderに代入
$loader = new ClassLoader();


// registerDirメソッドに/var/www/html/application/core と/var/www/html/application/modelsを代入している
$loader->registerDir(dirname(__FILE__).'/core');
$loader->registerDir(dirname(__FILE__).'/models');

$loader->register();


// -----おまけ------- //

echo __FILE__;　
// このファイルの絶対パスを表示　/var/www/html/application/bootstarp.php

echo dirname(__FILE__);
// このファイルが含まれているディレクトリまでの絶対パスを表示 /var/www/html/application