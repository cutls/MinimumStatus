# MinimumStatus

## What's new(v2)
**Update from v1, you cannot continue to view v1 log and downtime(it has no compatibility)**  
**v1からアップデートすると、v1のログやダウンタイム情報は使えなくなります。**

* Webhook can post you Error/Recovery events.(v1: only Error event)
* Load time graph is available
* New compact log viewer

[日本語概要](README.ja.md)  
[日本語インストールガイド](INSTALL.ja.md)  
Legacy PHP Non-DB Status Page

## What's this

Watch your website status.  

![screenshot](https://raw.githubusercontent.com/cutls/MinimumStatus/v2/minimal.png)  
![graph](https://raw.githubusercontent.com/cutls/MinimumStatus/v2/graph.png)  

### Require

* PHP 5.6/7.2
* Apache or Nginx

## Usage
  
* `git clone https://github.com/cutls/MinimumStatus.git`
* rename `dist/config.sample.php` to `config.php` and fill configs of MinimumStatus
* move `dist` to root directory of your virtual host
* for security, you should rename `bot.php` to another name
* set cron job to run `bot.php` or renamed file
* set your server config

## Notice of the site

Write HTML in `info_<domain>.html`  
![notice](https://raw.githubusercontent.com/cutls/MinimumStatus/v2/notice.png)  
(info_cutls.com.html)

## Webhook when error

You can get notice when your sites have something wrong and recovered.  
`if_error` at config will be accessed when error, recovered **with** `?site=<domain>&mode=<error|success>`
 **param twice**(When the site is down, `mode=error` and when the site is recovery, `mode=success`).  
So, fill `if_error` `https://thedesk.top/notice`, this will access like `https://thedesk.top/notice?site=cutls.com&mode=<error|success>`.

## Make the badge of your website status!

### Availability

Like [![check](https://status.cutls.com/badge/?site=thedesk.top)](https://status.cutls.com) 

It's on `https://example.com/badge?site=<yoursite>.com`. using badgen.net

### Operating or not

Like [![check](https://status.cutls.com/badge-service/?site=thedesk.top)](https://status.cutls.com) 

It's on `https://example.com/badge-service?site=<yoursite>.com`. using badgen.net
