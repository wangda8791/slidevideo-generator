1. Install diascope and its pre-requisite tools

- install gawk

sudo apt-get update

sudo apt-get install gawk


- install imagemagick

sudo apt-get update

sudo apt-get install imagemagick

- install sox

sudo apt-get install sox

- install ffmpeg

sudo add-apt-repository ppa:mc3man/trusty-media

sudo apt-get update

sudo apt-get install ffmpeg gstreamer0.10-ffmpeg

- install mjpegtools

sudo apt-get update

sudo apt-get install mjpegtools

- download diascope

curl -L -O http://tenet.dl.sourceforge.net/project/diascope/diascope/diascope-0.2.1e.tgz

- install diascope

tar xvzf diascope-0.2.1e.tgz

cd diascope-0.2.1e

sudo sh install.sh


2. Install apache and PHP
sudo apt-get update

sudo apt-get install apache2 libapache2-mod-php5 php5

- configure web project

* document root

/var/www/html

* conf file

/etc/apache2/apache2.conf