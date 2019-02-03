# MinimumStatus

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
* upload `ndstatus.json` to root of your website
* set cron job to run `bot.php`
* set your server config