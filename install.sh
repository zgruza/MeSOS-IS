#!/bin/bash
RE='\033[0;31m'
NC='\033[0m'
GR='\033[0;32m'
if [ "$EUID" -ne 0 ]
  then echo -e "${RE}Please run as root${NC}"
  exit
fi
clear
apt update
apt upgrade -y
apt install software-properties-common -y
#add-apt-repository ppa:ondrej/php -y
apt install apt-transport-https lsb-release ca-certificates wget -y
apt install apache2 -y
apt install unzip -y
wget -qO /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list
apt update
apt install -y php8.2-common php8.2-cli
apt install -y php8.2-curl php8.2-gd php8.2-mbstring php8.2-xml php8.2-zip
apt install -y libapache2-mod-php8.2
add-apt-repository ppa:deadsnakes/ppa -y
apt update
apt-get install -y build-essential tk-dev libncurses5-dev libncursesw5-dev libreadline6-dev libdb5.3-dev libgdbm-dev libsqlite3-dev libssl-dev libbz2-dev libexpat1-dev liblzma-dev zlib1g-dev libffi-dev
wget https://www.python.org/ftp/python/3.8.0/Python-3.8.0.tar.xz
tar xf Python-3.8.0.tar.xz
cd Python-3.8.0
./configure --enable-optimizations --prefix=/usr
make
make altinstall
cd ..
rm -r Python-3.8.0
rm Python-3.8.0.tar.xz
update-alternatives --install /usr/bin/python python /usr/bin/python3.8 1
python -m pip install --upgrade pip
python -m pip install beautifulsoup4
echo "@chromium-browser --kiosk http://127.0.0.1/" >> /etc/xdg/lxsession/LXDE-pi/autostart 
#echo "xserver-command=X -nocursor" >> /etc/lightdm/lightdm.conf MANUALLY!!!
apt install imagemagick -y
mv "/etc/ImageMagick-6/policy.xml" "/etc/ImageMagick-6/policy.xmlout"
echo "www-data   ALL=(ALL:ALL) NOPASSWD: ALL" >> /etc/sudoers
service php* restart
service apache2 restart
apt install cron -y
chmod -R 777 /var/www/html # Bad Practice yeaah... but easiest
echo -e "\n\n"
echo -e "${RE}Updating data..${NC}"
cd /var/www/html && /usr/bin/php cron.php
echo -e "${GR}**********************************${NC}"
echo -e "${GR}         INSTALL COMPLETED        ${NC}"
echo -e "${GR}**********************************${NC}"
echo -e "\n\n"
echo -e "${RE}Before you go>>${NC}"
echo -e "${RE} >> CHECK CRON JOB! (0 * * * * cd /var/www/html && /usr/bin/php cron.php) [Command: ${GR}crontab -e${NC}]"
echo -e "${RE} >> CHECK CORRECT TIMEZONE AND LOCALE (UTF-8) [Command: ${GR}localectl${NC}]"
echo -e "-------------------------------------"
echo -e 'Imma out..\n\nBye Bye..'
