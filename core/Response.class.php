<?php


/*
* Responseクラス
* レスポンスを表すクラス。HTTPヘッダとHTMLなどのコンテンツを返すのが主な役割。
* 4つのプロパティとそれらを制御する4つのメソッドを実装します。 
*/

class Response
{
    // $contentプロパティにはHTMLなど実際にクライアントに返す内容を格納します。それを設定するのが、setContentメソッド。
    protected $content;

    // HTTPのステータスコードを格納する。ステータスコードとはレスポンスがどのような状態にあるか表すもので、
    // 例えば、404 Not Found　とか 500 Internal Server Errorとか。初期値は200(＝通信成功)にしている。
    protected $status_code = 200;
    protected $status_text = 'OK';


    // HTTPヘッダーを格納するプロパティです。「ヘッダーの名前をキー」に「ヘッダーの内容を値」にして、連想配列形式で格納するようにします。
    // それらを設定するのが、setHttpHeader()メソッドです。
    protected $http_headers= array();


    // 各プロパティに設定された値をもとにレスポンスの送信を行います。（HTML+ ステータスコード + HTTPヘッダを送る）
    public function send()
    {   
        // ステータスコードの指定を行っている。内容は「HTTPのプロトコルのバージョン　ステータスコード　ステータステキスト」
        // 通獣は HTTP/1.1 200 OK のようになる。
        header('HTTP/1.1'.$this->status_code.''.$this->status_text);

        foreach ($this->http_headers as $name => $value) {

            // $http_headersのプロパティにHTTPレスポンスヘッダの指定があれば、header()関数を用いて送信します。
            header($name.':'.$value);
        }

        // レスポンスの内容を送信しています。これはechoを用いて出力を行うだけで送信されます。？なぜ？？？　
        echo $this->content;
    }


    // setContentメソッド（インスタンス後、引数に入れたHTMLがこのクラスのプロパティ$content($this->content)に格納される流れ。
    public function setContent($content)
    {
        $this->content = $content;
    }

    // HTTPのステータスコード（）404 Not Found　とか 500 Internal Server Errorとかを設定するメソッド。
    public function setStatusCode($status_code,$status_text = '')
    {
        $this->status_code = $status_code;
        $this->status_text = $status_text;
    }

    // HTTPヘッダーを格納する。「ヘッダーの名前をキー」に「ヘッダーの内容を値」にして、連想配列形式で格納するようにします。
    public function setHttpHeader($name,$value)
    {
        $this->http_headers[$name] = $value;
    }
}