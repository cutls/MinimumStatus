# MinimumStatus日本語インストールガイド

![screenshot](https://raw.githubusercontent.com/cutls/MinimumStatus/master/minimal.png)  

## Gitとかが使えないレンタルサーバーに設置する編

1. お手元のPCで`git clone https://github.com/cutls/MinimumStatus.git`するか、ZIPでダウンロードして解凍してください。
1. config.sample.phpの名前をconfig.phpに変更します。
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
1. db.config.phpの名前をdb.phpに変更します。**MySQLの監視をしない場合でも変えてください**
1. MySQLの監視をしたい場合、db.phpを編集します。  
3行目の`/* Delete this line if you use MySQL status`を行ごと削除して、設定します。(省略)
1. セキュリティのため、bot.phpを推測されにくい名前に変更します。`bot.minami.nitta.php`など
1. 監視される側のサイトに(例として`example.com`とします)`ndstatus.json`を置きます。そして`ndstatus.json`のアクセスを確認します(例の場合`example.com/ndstatus.json`にちゃんとアクセスできたら成功)。  
MastodonなどNginxのconfをいじらないと対処できない場合は後述します。
1. FTPなどでサーバーにアップロードしてください。.mdや.pngで終わるファイルはアップロード不要です。  
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

## Nginxの設定(Mastodonなど)
Mastodonなど、ファイルを置くだけでは対処できない場合、Nginxのconfをいじらなければなりません。  
Mastodonの場合について記述します。  
rootで、  
`/etc/nginx/sites-available/<site>.conf`だったり、`/etc/nginx/conf.d/<site>.conf`だったりします。とりあえず`ls /etc/nginx/sites-available`、`ls /etc/nginx/conf.d`と試してみて、それっぽいconfを見つけ出してエディタで開きます。  
`vi`でも`vim`でも`nano`でもなんでもいいです。  
それでもconfがなければ、`/etc/nginx/nginx.conf`か、それか全く別のところにNginxが入ってるのでしょう。  
そして、自分のインスタンスのNginxのconfのserverのところを以下のように追記します。  
```
 ## これはもとからある
 location /sw.js {
    add_header Cache-Control "public, max-age=0";
    try_files $uri @proxy;
  }
  ## この下に以下を追記
  location /ndstatus.json {
     return 200 '{"status":"OK"}';
  }
```  
そして、`systemctl restart nginx`などで、Nginxを再起動させます。  
  
Mastodon以外の場合でも、Nginxを使っているなら`server{}`内のよさげなところに上のMastodonでの例のように`location /ndstatus.json`と書いてやればOKです。  
Apacheやその他Webサーバーでは全く設定が異なります。

## 毎日のログを見る

`<ステータスページ>.com(例)/<ログを見たいサイト.com>.json`を開きます。  
コピーして、JSONを整形します。([JSON整形](https://tools.m-bsys.com/development_tooles/json-beautifier.php))。  
各行の6桁の数字_totalが6桁の数字が表す日の合計数で、  
各行の6桁の数字_successが6桁の数字が表す日のアクセス成功数で、  
6桁の数字はyymmddつまり190520=2019年5月20日です。