# MinimumStatus

[日本語インストールガイド](INSTALL.ja.md)  
Legacy PHP Non-DB Status Page

## What's this

Watch your website and MySQL database status.  

![screenshot](https://raw.githubusercontent.com/cutls/MinimumStatus/master/minimal.png)  
This icons are OGP icon images.

### Require

* PHP 5.6/7.2
* Apache or Nginx

## Usage
  
* `git clone https://github.com/cutls/MinimumStatus.git`
* rename `config.sample.php` to `config.php` and `db.sample.php` to `db.php` and fill configs of MinimumStatus
* for security, you should rename `bot.php` to another name
* upload `ndstatus.json` to root of your website
* set cron job to run `bot.php` or renamed file
* set your server config

## Make badge of your website status!

Like [![check](https://status.cutls.com/badge/?site=thedesk.top)](https://status.cutls.com) 

It's on `https://example.com/badge?site=<yoursite>.com`. using badgen.net