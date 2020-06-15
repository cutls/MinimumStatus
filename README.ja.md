# MinimumStatus

## What's new(v2) 
**v1からアップデートすると、v1のログやダウンタイム情報は使えなくなります。**

* Webhookでエラーだけでなく回復情報もポストするようになりました
* 読み込み時間のグラフ
* ログビューアが刷新されました


[日本語インストールガイド](INSTALL.ja.md)  
Legacy PHP Non-DB Status Page
レガシーPHPを用いたDBを使わないステータスページ

## What's this

ウェブサイトの状態を表示します。  

![screenshot](https://raw.githubusercontent.com/cutls/MinimumStatus/v2/minimal.png)  
![graph](https://raw.githubusercontent.com/cutls/MinimumStatus/v2/graph.png)  

### 要件

* PHP 5.6/7.2
* Apache or Nginx

## Usage
  
[日本語インストールガイド](INSTALL.ja.md)  

## サイトからのお知らせ

`info_<domain>.html`にHTMLを書いてください。
![notice](https://raw.githubusercontent.com/cutls/MinimumStatus/v2/notice.png)  

## Webhook when error

You can get notice when your sites have something wrong and recovered.  
`if_error` at config will be accessed when error, recovered **with** `?site=<domain>&mode=<error|success>`
 **param twice**(When the site is down, `mode=error` and when the site is recovery, `mode=success`).  
So, fill `if_error` `https://thedesk.top/notice`, this will access like `https://thedesk.top/notice?site=cutls.com&mode=<error|success>`.

## バッジ

### 可用性

例 [![check](https://status.cutls.com/badge/?site=thedesk.top)](https://status.cutls.com) 

URL: `https://example.com/badge?site=<yoursite>.com` badgen.net使用

### Operating or not

例 [![check](https://status.cutls.com/badge-service/?site=thedesk.top)](https://status.cutls.com) 

URL: `https://example.com/badge-service?site=<yoursite>.com` badgen.net使用

