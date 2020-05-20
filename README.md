# MinimumStatus

**Above this version, MySQL status was removed.**  
**MySQLの監視は削除されました。**

[日本語インストールガイド](INSTALL.ja.md)  
Legacy PHP Non-DB Status Page

## What's this

Watch your website and MySQL database status.  

![screenshot](https://raw.githubusercontent.com/cutls/MinimumStatus/v2/minimal.png)  
![graph](https://raw.githubusercontent.com/cutls/MinimumStatus/v2/graph.png)  
This graph is provied by [QuickChart.io](https://quickchart.io/)

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

## Webhook when error

You can get notice when your sites have something wrong and recovered.  
`if_error` at config will be accessed when error, recovered **with** `?site=<domain>&mode=<error|success>`
 **param twice**(When the site is down, `mode=error` and when the site is recovery, `mode=success`).  
So, fill `if_error` `https://thedesk.top/notice`, this will access like `https://thedesk.top/notice?site=cutls.com&mode=<error|success>`.

## Make the badge of your website status!

Like [![check](https://status.cutls.com/badge/?site=thedesk.top)](https://status.cutls.com) 

It's on `https://example.com/badge?site=<yoursite>.com`. using badgen.net
