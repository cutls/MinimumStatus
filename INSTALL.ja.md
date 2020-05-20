# MinimumStatus日本語インストールガイド

![screenshot](https://raw.githubusercontent.com/cutls/MinimumStatus/master/minimal.png)  s

## Gitとかが使えないレンタルサーバーに設置する編

1. お手元のPCで`git clone https://github.com/cutls/MinimumStatus.git`するか、ZIPでダウンロードして解凍してください。
1. distの中のconfig.sample.phpの名前をconfig.phpに変更します。
1. config.phpを編集します。  
```
$config=[  
    "name"=>"このページの名前です。スクリーンショットでは一番上に「Cutls Statuses」に該当します。その「Cutls」の部分を変更できます。",
    "description"=>"タイトルの下に説明文を入力できます。スクリーンショットでは使っていません。空白でもOKです。",
    "copy"=>"コピーライトのところに入れる名前です。サイトの一番下で利用できます。",
    "website"=>[
        ["domain"=>"example1.com","name"=>"サイトの名前","https"=>true,"image"=>"https:// アイコン画像のURL .png","if_error"=>"Webhook URL"],
        ["domain"=>"example2.com","name"=>"サイトの名前","https"=>true,"image"=>"https:// アイコン画像のURL .png","if_error"=>"Webhook URL"],
        //以下サイトの数だけ繰り返します。httpsはSSL/TLSに対応していればtrueを、そうでなければfalseを指定してください。
        //アイコン画像のURLはhttpから始めてください。このステータスページをhttpsにした場合、アイコンもhttpsから始める必要があります。無い場合は空白でも構いません。
        //Webhook URLはわかる方のみ入れてください。分からない方は空欄にしてください。
    ]  
];  
```
1. セキュリティのため、bot.phpを推測されにくい名前に変更します。`bot.minami.nitta.php`など
1. **distの中だけを**FTPなどでサーバーにアップロードしてください。
1. ドメインの設定等をします。例えば、`https://status.example.com`でアクセスできるように指定します。SSL/TLSの設定なども行っておきます。
1. cronの設定をします。cronは一定時間ごとにコマンドを実行する機能です。レンタルサーバーの場合、コントロールパネルから設定します。  
レンタルサーバープランによっては使えなかったり、1分ごとの設定ができなかったりします。別に何分ごとに設定しても構いませんが、短いに越したことはありません。  
さくらのレンタルサーバーの設定例  
![screenshot](https://raw.githubusercontent.com/cutls/MinimumStatus/master/sakura.png)  
  
**cronが使えない場合**  
[Google Cloud Schedular](https://cloud.google.com/scheduler/)を使用します。無料でそこそこの信頼性があります。  
コンソールから「ジョブの作成」をします。  
以下のように設定します。URLは設定したドメイン+`bot.php`を変更したものを指定します。    
![screenshot](https://raw.githubusercontent.com/cutls/MinimumStatus/master/cron.png)  
1. 一分程度待ったり、「今すぐ実行」をして、再読込してステータスのカウントがTotal 1/1(100%)、Today 1/1(100%)になることを確認します。

## 毎日のログを見る

`<ステータスページ>.com(例)/<ログを見たいサイト.com>.json`を開きます。  
コピーして、JSONを整形します。([JSON整形](https://tools.m-bsys.com/development_tooles/json-beautifier.php))。  
各行の6桁の数字_totalが6桁の数字が表す日の合計数で、  
各行の6桁の数字_successが6桁の数字が表す日のアクセス成功数で、  
6桁の数字はyymmddつまり190520=2019年5月20日です。

## Webhook
エラーなときに指定されたURLにアクセスを飛ばします。  
cutls.comの`if_error`に`https://thedesk.top/notice`と書くと、エラーになった時に**初回の一度だけ**`https://thedesk.top/notice?site=cutls.com`にアクセスされます。再復活し、その後またエラーとなるまで再送されません。  

### Discordでエラー発生時に通知が来るようにする
Discordの管理権を持つサーバーにWebhookを追加します。  
![screenshot](https://raw.githubusercontent.com/cutls/MinimumStatus/master/discord-channel.png)  
![screenshot](https://raw.githubusercontent.com/cutls/MinimumStatus/master/discord-add-webhook.png)  
URLをコピーします。  
以下のようにPHPを書きます。  
さきほどのURLを`https://discordapp.com/api/webhooks/xxxxxxxxxxx`のところに入れてください。
```
<?php
    $website=$_GET["site"];
    discord('https://discordapp.com/api/webhooks/xxxxxxxxxxx',[content=>$_GET['mode'].'('.$website.')']);
    function discord($url, $data)
{
    $options = array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_AUTOREFERER => true,
	);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt_array($ch, $options);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
?>
```  
外からアクセスできるところに保存します。  
`config.php`の`if_error`のところに、そこにアクセスするためのURLまたは相対パスを記入します。  
以上です。  
エラー発生時は`error(watchingsite.com)`、回復時は`susuccess(ww.com)`のようなメッセージが送られます。