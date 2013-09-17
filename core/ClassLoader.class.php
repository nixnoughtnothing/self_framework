<?php

/**
* ClassLoaderクラス
*/
class ClassLoader
{
    protected $dirs;

    // PHPにオートローダクラスを登録するメソッド
    public function register()
    {
        // オートロード時に配列内のクラスを呼び出す
        spl_autoload_register(array($this,'loadClass'));
    }



    // ディレクトリを登録するメソッド
    public function registerDir($dir)
    {
        // $dir には　右のパスが代入される/var/www/html/application/core と/var/www/html/application/models
        $this->dirs[] = $dir;
    }


    // オートロード時にPHPから自動的に呼び出され、クラスファイルの読み込みを行うメソッド
    public function loadClass($class)
    {
        foreach ($this->dirs as $dir) {

            // $file = /var/www/html/application/core OR models/ class名/.php
            $file = $dir . '/' . $class . '.php';

            // 読み込み可能であれば、requireする
            if(is_readable($file)){
                require $file;

                return;
            }
        }
    }
}