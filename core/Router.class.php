<?php


/*
* Routerクラス
* Requestクラスから、「ルーティング定義配列」と「PATH_INFO」を受取、ルーティングパラメータを特定するクラス
*
*/
class Router
{

    protected $routes;

    // 「ルーティング定義配列」をコンストラクタのパラメータで受け取り、それをcompileRoutes()メソッドに渡している
    public function __construct($definitions)
    {
        $this->routes = $this->compileRoutes($definitions);
    }



    /* コンストラクタから受け取った「ルーティング定義配列」中の
    動的パラメータ指定を正規表現で扱える形式に変換するメソッド */
    public function compileRoutes($definitions)
    {
        $routes = array();

        foreach($definitions as $url => $params){

            // ltrimで$urlの頭の/を取り除いたあと、URLをexplode()でスラッシュごとに分割
            // 　第一引数で分割する区切りとなる文字、第二引数で分割の対象となる文字を置く。それを$tokensという配列に入れる
            $tokens = explode('/',ltrim($url,'/'));

            // $tokenにはurlを分割した要素が入っている。$iは添字。
            foreach ($tokens as $i=> $token) {

                // 分割した値の中にコロンで始まる文字列があった場合、
                if(0 === strpos($token,':')){

                    // その文字列がはいった$tokenの2文字目から全て取り出す。（つまり「:」の部分を除いた部分すべて）。それを$nameに格納する。
                    $name  = substr($token,1);

                    // 文字列を正規表現に変換して再格納する。正規表現で「(?<name>...)  ＝名前付き捕捉　」[^/]+の部分はよくわからず。。
                    $token = '(?P<' .$name. '>[^/]+)';
                }
                // 正規表現をくっつけたurl（動的パラメータの部分）をもとの配列に戻してあげる。
                // ここでkeyの値が必要になってくるから、最初のforeach文でkeyまで出しておくのは覚えておく
                $tokens[$i] = $token;
            }
            
            // 動的パラメータを正規表現に変換したので、ltrim()関数でとった「/」を戻し、かつ、
            // implode(連結文字,配列)関数を使い、第2引数で指定した配列を第一引数で指定した「/」で連結して返す。それを$pattarnに入れる。
            $pattarn = '/'. implode('/',$tokens);

            // 連想配列$routesの添字に正規表現に変換済みの値として$pattanを格納する
            $routes['$pattern'] = $params;
        }

        // 全部終わったら、$routesを返す
        return $routes;
    }



    // 「（正規表現に）変換済みルーティング定義配列」と「PATH_INFO←すでに存在している」のマッチングを行うresolveメソッド
    public function resolve($path_info)
    {
        // $path_infoの最初の文字が「/」でなければ、
        if('/' !== substr($path_info,0,1)){

            // $path_infoの頭に「/」をつける
            $path_info = '/'.$path_info;
        }

        // このクラス内で定義した$routes（$this->routes)には、正規表現に変換済みのルーティング定義配列が入っているので、foreachでひとつひとつ取り出します。
        foreach($this->routes as $pattern => $params){

            // $path_infoが$patternと完全にマッチした場合、1(true)をかえす。また第三引数に変数をした場合はその変数にマッチしたものが格納される。
            // 例；preg_match("/^abcde$/", $string);は・$stringが 「abcde」 にマッチの意味。
            if(preg_match('#^'.$pattern.'$#',$path_info,$matches)){

                // 「$matches(マッチしたもの)＝$path_infoの配列 」と「$prams」マージする。
                $params = array_merge($params,$matches)

                return $params;
            }
        }

        return false;
    }
}