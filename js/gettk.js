
const GOOGLE_URL =  "https://translate.google.cn/translate_a/single";

function google_trans(text, tl) {
	var tk = token(text);

	var url = GOOGLE_URL + "?client=webapp&sl=zh-CN&tl="
	+ tl
	+ "&hl=zh-CN&dt=at&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&clearbtn=1&otf=1&ssel=0&tsel=0&kc=2&tk="
	+ tk
	+ "&q="
	+ text;

	var re = new XMLHttpRequest();
	re.open('GET',url);
	re.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	re.setRequestHeader('Host','translate.google.cn');
	re.setRequestHeader('User-Agent','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko/20100101 Firefox/68.0');
	re.setRequestHeader('Accept','*/*');
	re.setRequestHeader('Accept-Language','zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2');
	re.setRequestHeader('Accept-Encoding','gzip, deflate, br');
	re.setRequestHeader('Connection','keep-alive');
	re.setRequestHeader('Referer','https://translate.google.cn/');
	re.setRequestHeader('Access-Control-Allow-Origin', '*');
	re.setRequestHeader('Cookie','NID=188=WWhZ-A3AZa6Y97OKUCR5CLAR5RDw8BZUJtcHxWpAG7xY4Ng7uWRzZ-ZOUSpD9U75rOFoN8eF8Uco5DBZjjYgAodrVYNgOnrL6MCMP6lq5SAYkMaZjKtHX8pfwRG-wYnaxBO7RtqUBTtf9acE_48PxVLdA_nRJLG7Jky8N7srlTw; _ga=GA1.3.1399173103.1563412742; _gid=GA1.3.766588131.1563412742; 1P_JAR=2019-7-18-6');
	re.setRequestHeader('Pragma','no-cache');
	re.setRequestHeader('Cache-Control','no-cache');
	re.setRequestHeader('TE','Trailers');
	re.onreadystatechange = function(){
		if(re.readyState === 4 && re.status === 200){
			alert(re.responseText);
		}
	};
	re.send(null);
}

function token(a) {
	var k = "";
	var b = 406644;
	var b1 = 3293161072;

	var jd = ".";
	var sb = "+-a^+6";
	var Zb = "+-3^+b+-f";

	for (var e = [], f = 0, g = 0; g < a.length; g++) {
		var m = a.charCodeAt(g);
		128 > m ? e[f++] = m : (2048 > m ? e[f++] = m >> 6 | 192 : (55296 == (m & 64512) && g + 1 < a.length && 56320 == (a.charCodeAt(g + 1) & 64512) ? (m = 65536 + ((m & 1023) << 10) + (a.charCodeAt(++g) & 1023),
			e[f++] = m >> 18 | 240,
			e[f++] = m >> 12 & 63 | 128) : e[f++] = m >> 12 | 224,
			e[f++] = m >> 6 & 63 | 128),
			e[f++] = m & 63 | 128)
	}
	a = b;
	for (f = 0; f < e.length; f++)
		a += e[f],
			a = RL(a, sb);
	a = RL(a, Zb);
	a ^= b1 || 0;
	0 > a && (a = (a & 2147483647) + 2147483648);
	a %= 1E6;
	return a.toString() + jd + (a ^ b)
}

function RL(a, b) {
	var t = "a";
	var Yb = "+";
	for (var c = 0; c < b.length - 2; c += 3) {
		var d = b.charAt(c + 2),
			d = d >= t ? d.charCodeAt(0) - 87 : Number(d),
			d = b.charAt(c + 1) == Yb ? a >>> d: a << d;
		a = b.charAt(c) == Yb ? a + d & 4294967295 : a ^ d
	}
	return a
}