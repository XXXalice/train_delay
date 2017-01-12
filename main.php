<?php

    require_once('getWords.php');

    $code = '';

    //mysqlの接続情報を入力してください
    $mysqli = new mysqli($host,$dbuser,$dbpass,$dbname);
    if($mysqli->connect_error){
        echo('faild to connect to mysql.');
        exit;
    }
    $mysqli->set_charset('utf8');

    require 'vendor/autoload.php';
    use Fieg\Bayes\Classifier;
    use Fieg\Bayes\Tokenizer\WhitespaceAndPunctuationTokenizer;


    $tokenizer = new WhitespaceAndPunctuationTokenizer();
    $classifier = new Classifier($tokenizer);
    $mecab = new MeCab_Tagger();

    if(!empty($_POST['name'])){

        set($delay,"項目1",$mysqli);
        set($nodelay,"項目2",$mysqli);
        $str = getPercent(h($_POST['name']));
        $code = judge($str,"項目1","項目2");

    }

?>
<!doctype html>
<html lang='ja'>
    <head>
        <meta charset='utf8'>
        <title>判断</title>
    </head>
    <body>
        <form method='post' action=''>
            <p><label>ツイート内容を入力<input type='text' name='name' size='50'>
            <input type='submit' value='実行'></label><p>
            
        </form>
        <p><?php 

        if($code != ''){
            switch($code){
                case 1: echo "項目1の可能性が高いです。";
                break;
                case 2: echo "項目2の可能性が高いです。";
                break;
                case 3: echo "どちらも半々です。";
                break;
                default: echo "どっかおかしいです。";
            }
        }
        
         ?></p>
    </body>
</html>