<?php

/*
* Requestクラス
*
*/
class Request
{

    // POSTの判定するメソッド
    public function isPost()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

                return true;
        }

        return false;
    }


    // $_GET値の取得しているメソッド
    public function getGet($name,$default = null)
    {
        if(isset($_GET[$name])){
            
                return $_GET[$name];
        }

        return $default;
    }


    // $_POST値の取得しているメソッド
    public function getPOST($name,$default = null)
    {
        if(isset($_POST[$name])){
            
                return $_POST[$name];
        }

        return $default;
    }


    // サーバーのホスト名の取得をしているメソッド
    public function getHOST()
    {
        if(!empty($_SERVER['HTTP_HOST'])){
            
                return $_SERVER['HTTP_HOST'];
        }

        return $_SERVER['SERVER_NAME'];
    }


    // HTTPSでアクサスされたかどうかを判定するメソッド
    public function isSsl()
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'ON'){
            
                return true;
        }

        return false;
    }

    // リクエストされたURLの取得
    public function getRequestUri()
    {
        return $_SERVER['REQUEST_URI'];
    }


    // ベースURLを取得するメソッド
    public function getBaseUrl()
    {
        // ホスト名取得（フロントコントローラまでのパスが含まれている）
        $script_name = $_SERVER['SCRIPT_NAME'];
        // URLのホスト部分より、あとの値を格納してくれる
        $request_uri = $this->getRequestUri();

        if(0 === strpos($request_uri,$script_name)){
            return $script_name;
        }else if(0 === strpos($request_uri,dirname($script_name))){
            return rtrim(dirname($script_name),'/');
        }

        return '';
    }


    // Path_infoを取得するメソッド
    public function getPathInfo()
    {
        // getBaseUrlからbaseurlを取得して、$base_urlに代入
        $base_url    = $this->getBaseUrl();

        // getRequestUriからrequesturlを取得して、$request_uriに代入
        $request_uri = $this->getRequestUri();

        // GETがあれば、GETの開始位置を$posに代入し、GET部分を取り除いたものを$request_uriに代入している
        if(false !== ($pos = strpos($request_uri,'?'))){
            $request_uri   = substr($request_uri,0,$pos);
        }

        // request_uri - base_urlをしてpath_infoを算出している。
        $path_info = (string)substr($request_uri,strlen($base_url));

        return $path_info;
    }
}
