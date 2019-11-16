1. Install diascope and its pre-requisite tools

- install gawk

    sudo yum update
    sudo yum install gawk

- install imagemagick
    
    sudo yum install -y gcc php-devel php-pear
    sudo yum install -y ImageMagick ImageMagick-devel

- install sox

    sudo yum install sox

- install ffmpeg

https://trac.ffmpeg.org/wiki/CompilationGuide/Centos

- install mjpegtools

* Create the repository config file /etc/yum.repos.d/linuxtech.repo:

[linuxtech]

name=LinuxTECH

baseurl=http://pkgrepo.linuxtech.net/el6/release/
enabled=1

gpgcheck=1

gpgkey=http://pkgrepo.linuxtech.net/el6/release/RPM-GPG-KEY-LinuxTECH.NET

* Install mjpegtools rpm package:

    yum install mjpegtools

- download diascope

curl -L -O http://tenet.dl.sourceforge.net/project/diascope/diascope/diascope-0.2.1e.tgz

- install diascope

    tar xvzf diascope-0.2.1e.tgz
    cd diascope-0.2.1e
    sudo sh install.sh

2. Install apache and PHP

- install apache2

    sudo yum install httpd
    chkconfig --levels 235 httpd on
    /etc/init.d/httpd start

- install php

    yum install php
    /etc/init.d/httpd restart

3. configure web project

* copy project
    cp -R videogenerator /var/www/html

* copy project with git
    cd /var/www/html
    git clone https://github.com/wangda8791/slidevideo-generator.git

* resources folder - contains image directories
* videos folder - contains generated video files
