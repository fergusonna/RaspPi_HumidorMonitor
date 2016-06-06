#!/bin/sh
#
### BEGIN INIT INFO
# Provides:          htu21df
# Required-Start:    $remote_fs $syslog
# Required-Stop:     $remote_fs $syslog
# Should-Start:      $network $time
# Should-Stop:       $network $time
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: Start HTU21DF Reading
# Description:       Initiates reading from HTU21DF Temperature/
#			Humidity Sensor. Reads from sensor once 
#			every thirty (30) minutes and uploads
#			data to MySQL database. 
### END INIT INFO

sleep 20
cd 
killall pigpiod
cd
sudo /root/RaspberryPI_HTU21DF/pigpiod
cd
sudo python /YOUR_FILE_LOCATION/insertMySQL.py >> /YOUR_USER_FOLDER/htu21df.log &
cd
