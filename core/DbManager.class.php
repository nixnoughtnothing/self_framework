<?php

/**
* DbManagerクラス 接続情報を管理するクラス
*
*
*/ 
class DbManager
{

    // $connectionsプロパティには接続情報である「PDOクラスのインスタンス」を配列で保持します。
    protected $connections               = array();

    // $repository_connection_mapプロパティに「データベースのテーブル」ごとの「Repositoryクラス」と「接続名」の対応を格納します。
    protected $repository_connection_map = array();

    // 全てのインスタンスを$repositoriesに格納する
    protected $repositories              = array();

    // データベースへの接続を行うconnect()メソッド
    // connect(接続を特定するための名前($connectionsプロパティの「キー」になる値,PDOクラスのコンストラクタに渡す情報)
    public function connect($name,$params)
    {
        // array_merge関数を使っているのは後ほどこの$params配列から値を取り出す際にキーが存在するかのチェックをしないで済むようにするため
        $params = array_merge(array(
            'dsn'     => null,
            'user'    => '',
            'password'=> '',
            'options' => array(),
            ),$params);
    
        // 実際にPDOクラスのインスタンスを作成している
        $pdo = new PDO(
            $params['dsn'],
            $params['user'],
            $params['password'],
            $params['options']
        );

        // 例外時の挙動を設定している 
        // PDO::setAttribute(PDO::ATTR_ERRMODE,エラーモードを表す引数)、ERROMODE_EXCEPTIONは例外をスローする。
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        // newした（インスタンス化した）PDOクラスをarray型の$connectionsに代入。
        $this->connections[$name] = $pdo;
    }


    // connect()メソッドで接続したコネクションを取得するのがgetConnection()メソッドです。
    public function getConnection($name = null)
    {

        if(is_null($name)){
            // 名前の指定なければ、current()関数で「PDOクラスのインスタンス」の配列の先頭の値を取得する
            return current($this->connections);
        }

        // $name（$connectionsのkeyにあたる）に引数の指定があれば、$nameをキーに指定して、$nameに該当する値を返す。
        return $this->connections[$name];
    } 







    public function setRepositoryConnectionMap($repository_name,$name)
    {

        $this->repository_connection_map[$repository_name] = $name;
    } 

    // Repositoryクラスに対応する接続を取得しようとした際に、
    // $repository_connection_mapプロパティに設定されているものはgetConnection()メソッドに接続名を指定し、
    // それでなければ最初に作成したものを取得する、という内容になります。
    public function getConnectionForRepository($repository_name)
    {

        if(isset($this->repository_connection_map[$repository_name])){
        
            $name = $this->repository_connection_map[$repository_name];    
            $con  = $this->getConnection($name);
        
        }else{

            $con  = $this->getConnection();
        }

        return $con;
    } 




    // ------------------------Repositoryクラスの管理------------------------ //


    // インスタンスの生成を行うメソッド（ここでは指定されたRepository名が$repositoriesに入っていない場合のみ、生成を行う。
    public function get($repository_name)
    {
        // もし、get()関数の引数に指定したRepository名が$repositoriesに入っていなければ、
        if(!isset($this->repositories[$repository_name])){

            // Repositoryのクラス名を指定している。ルールとして、名前のあとに「Repository」をつけたものをクラス名にする。
            $repository_class = $repository_name.'Repository';

            // 上記で作成したgetConnetionForRepository()メソッドを使った、コネクションを取得している。
            $con = $this->getConnectionForRepository($repository_name);

            // インスタンスの作成。PHPでは下記のように変数にクラス名を文字列でいれておくことで動的なクラス生成が可能になる。
            $repository = new $repository_class($con);

            // 作成したインスタンスを保持するために、$repositoriesに格納している。
            $this->repositories[$repository_name] = $repository;
        }

        // 上記の処理が完了すると$repositoriesプロパティに作成したインスタンスが格納されている状態なので、それを返します。
        return $this->repositories[$repository_name];
    }




    // ------------------------接続の解放処理------------------------ //

    // データベースとの接続を解放する処理を追加します。ひとこと掲示板のときもそうでしたが、
    // プログラムの作法として、データーベースへの接続は閉じるように作成しています。
    public function __destruct()
    {
        foreach($this->repositories as $repository){
            unset($repository);
        }

        foreach($this->repositories as $con){
            unset($con);
        }
    }
}



// --------DbManagerクラスの実際の使い方-------- //
/*

// DbMangerクラスをインスタンス化して$db_managerに代入する。
$db_manager = new DbManager();

// $db_managerからconnect()メソッドを利用する。引数の名前にmasterを代入。
$db_manager->connect('master',array
    'dsn'     => 'mysql:dbname=mydb;host=localhost',
    'user'    => 'myuser',
    'password'=> 'mypass',
));

$db_manager->getConnection('master');
$db_manager->getConnection();  #　→ masterがかえってくる
*/








