<?php
header("Content-type: text/html; charset=utf-8");

class Download
{

    protected $google_url = "https://translate.google.cn/translate_a/single";

    protected $text = "";

    protected $tl = "";

    protected $tk = "";

    protected $imgObj = null;

    protected $outputImgFileName = "createdByZimu.jpg";

    public function index()
    {
        $this->text = $_POST['text'];
        $this->tl   = $_POST["tl"];
        $this->tk   = $_POST["tk"];
        $this->api();
    }

    protected function api()
    {
        // 翻译 通过接口获取
        $headers   = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=utf-8';
        $headers[] = 'Host: translate.google.cn';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko/20100101 Firefox/68.0';
        $headers[] = 'Accept: */*';
        $headers[] = 'Accept-Language: zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2';
        $headers[] = 'Accept-Encoding: gzip, deflate, br';
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Referer: https://translate.google.cn/';
        $headers[] = 'Cookie: NID=188=WWhZ-A3AZa6Y97OKUCR5CLAR5RDw8BZUJtcHxWpAG7xY4Ng7uWRzZ-ZOUSpD9U75rOFoN8eF8Uco5DBZjjYgAodrVYNgOnrL6MCMP6lq5SAYkMaZjKtHX8pfwRG-wYnaxBO7RtqUBTtf9acE_48PxVLdA_nRJLG7Jky8N7srlTw; _ga=GA1.3.1399173103.1563412742; _gid=GA1.3.766588131.1563412742; 1P_JAR=2019-7-18-6';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'TE: Trailers';
        $url       = $this->google_url . "?client=webapp&sl=zh-CN&tl=" . $this->tl . "&hl=zh-CN&dt=at&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&clearbtn=1&otf=1&ssel=0&tsel=0&kc=2&tk=" . $this->tk . "&q=" . urlencode($this->text);
        $ch        = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        $data = curl_exec($ch);
        curl_close($ch);
        $temp = '/\[\[\["([\s\S]*?)","/';
        preg_match($temp, $data, $mat);
        echo $mat[1];
    }
}

$op = new Download();
$op->index();

?>