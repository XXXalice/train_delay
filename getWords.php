<?php 

    //データベースに登録されている学習させたい情報を学習します
    //単語でも構いませんが文章でも形態素解析をして形容詞、動詞、名詞をそれぞれ学習します
    function set($type,$categoly,$column){
        global $mysqli;
        global $tokenizer;
        global $classifier;
        global $mecab;
        foreach($type as $number){
            $query = $mysqli->query("select * from $column where number = $number");
            $row = $query->fetch_object();
            $nodes = $mecab->parseToNode($row->text);
            foreach($nodes as $i){
                if($i->getStat() != 2 && $i->getStat() != 3){
                    $word = $i->getSurface();
                    $info = explode(',',$i->getFeature());
                    if($info[0] == '形容詞' || $info[0]=='動詞' || $info[0]=='名詞'){
                        $classifier->train($categoly,$word);
                    }
                }
            }
        }
    }

    //入力された日本語の文章を読み取ることができるようにします
    function getPercent($text){
        global $mecab;
        global $classifier;
        return  $classifier->classify(implode(' ',mecab_split($text)));
    }

    //htmlspecialchars()
    function h($text){
        return htmlspecialchars($text);
    }

    //入力された文章を受け取り判定コードを返します
    function judge($str,$a,$b){
        if($str[$a] >= $str[$b]){
            return 1;
        }elseif($str[$a] >= $str[$b]){
            return 2;
        }else{
            return 3;
        }
    }

?>