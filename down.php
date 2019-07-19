<?php
header("Content-type: text/html; charset=utf-8");

class Download
{

    protected $googleUrl = "https://translate.google.cn/translate_a/single";

    protected $text = "";

    protected $tl = "";

    protected $tk = "";

    protected $imgObj = null;

    protected $outputImgFileName = "createdByZimu.jpg";

    public function index()
    {
        // 接受form
        if ($_POST) {
            $this->text = $_POST['text'];
            $this->tl   = $_POST["tl"];
            $this->tk   = $_POST["tk"];
            // 接收图片
            $allowedExts = array("jpeg", "jpg", "png");
            $temp        = explode(".", $_FILES["file"]["name"]);
            $extension   = end($temp); // 文件后缀
            if ((($_FILES["file"]["type"] == "image/jpeg")
                    || ($_FILES["file"]["type"] == "image/jpg")
                    || ($_FILES["file"]["type"] == "image/png"))
                && ($_FILES["file"]["size"] < 20971520)    // 小于 20M
                && in_array($extension, $allowedExts)) {
                if ($_FILES["file"]["error"] > 0) {
                    echo "错误：: " . $_FILES["file"]["error"] . "<br>";
                } else {
                    move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
                    $path = "upload/" . $_FILES["file"]["name"];
                }
            } else {
                echo "非法的文件格式";
            }
            if ($_FILES["file"]["type"] == "image/png") {
                $this->imgObj = imagecreatefrompng($path);
            } elseif (($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")) {
                $this->imgObj = imagecreatefromjpeg($path);
            }

            unlink($path); // 删除保存的图片
            $this->downloadImg();
        }
    }

    // 下载
    protected function downloadImg()
    {
        ob_start();
        $this->image();
        $s = ob_get_clean();
        ob_clean();
        header("content-type:image/jpeg");
        header('Content-Disposition: attachment; filename="' . $this->outputImgFileName . '"');
        header('Content-Length: ' . strlen($s));
        echo $s;
    }

    // 合成图片
    protected function image()
    {
        // 获取图片宽高
        $imgWitch  = imagesx($this->imgObj);
        $imgHeight = imagesy($this->imgObj);
        // 创建背景
        $bg = imagecreatetruecolor($imgWitch, $imgHeight + 110);
        // 给定黑色
        $black = imagecolorallocate($bg, 0, 0, 0);
        imagefill($bg, 0, 0, $black);
        // 图片放入背景中
        imagecopy($bg, $this->imgObj, 0, 50, 0, 0, $imgWitch, $imgHeight);
        // 写入文字
        // $font     = dirname(__FILE__) . '\font\msyh.ttc';
        // linux时
        $font     = dirname(__FILE__) . '/font/msyh.ttc';
        $white    = imagecolorallocate($bg, 250, 250, 250);
        $fontSize = 15;
        $fontBox  = imagettfbbox($fontSize, 0, $font, $this->text);
        imagettftext($bg, 15, 0, ceil(($imgWitch - $fontBox[2]) / 2), $imgHeight + 75, $white, $font, $this->text);
        // 翻译字幕
        $text2     = $this->api();
        $fontSize2 = 13;
        $fontBox2  = imagettfbbox($fontSize2, 0, $font, $text2);
        imagettftext($bg, 13, 0, ceil(($imgWitch - $fontBox2[2]) / 2), $imgHeight + 100, $white, $font, $text2);
        imagejpeg($bg);
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
        $url       = $this->googleUrl . "?client=webapp&sl=zh-CN&tl=" . $this->tl . "&hl=zh-CN&dt=at&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&clearbtn=1&otf=1&ssel=0&tsel=0&kc=2&tk=" . $this->tk . "&q=" . urlencode($this->text);
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
        return $mat[1];
    }
}

$op = new Download();
$op->index();

?>