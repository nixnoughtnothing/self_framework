<?php

/*
* DbRepositoryクラス
* データベースへのアクセスを行うクラス。テーブルごとにDbRepositoryクラスの子クラスを作成するようにする。
*
*/
abstract class DbRepository
{
    protected $con;

    // DbManagerクラスからPDOクラスのインスタンスを受け取って内部で保持するためのメソッド・
    // ここで受けとった「PDOクラスのインスタンス」に対してSQL文を実行します。
    public function __construct($con)
    {
        $this->setConnection($con);　
    }


    // DbManagerクラスからPDOクラスのインスタンスを受け取って内部で保持するためのメソッド
    public function setConnection($con)
    {
        $this->con = $con;
    }



    // クエリを実行

    public function execute($sql, $params = array())
    {
        $stmt = $this->con->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    /**
     * クエリを実行し、結果を1行取得
     *
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function fetch($sql, $params = array())
    {
        return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * クエリを実行し、結果をすべて取得
     *
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function fetchAll($sql, $params = array())
    {
        return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
}