# RaspPi_HumidorMonitor
Raspberry Pi powered Humidor Monitor

Credit to the following for their hard work and for allowing their code to be modified/used as part of this project under MIT and/or GPL licenses:

	https://github.com/jervine/rpi-temp-humid-monitor (Google graph/chart design)

	https://github.com/dalexgray/RaspberryPI_HTU21DF (Python HTU21DF drivers)

	https://github.com/bpass03/RaspPi_HumidorMonitor (HTML/PHP/CSS design, Python MySQL integration, build components)

Concept

	Initially, the project started with researching the advantages or disadvantages of building from the Arduino 
	or Raspberry Pi platform.


Parts & Process

	Canakit Raspberry Pi 3 ($75)

	Temperature/Humidity Sensor (HTU21D-F) ($15)

	Five-Pin Flexcable (A9BAG-0508F-ND) ($2.50)


Details


insertMySQL.py

	Edit this file to include the proper MySQL settings for your database


launch_htu21df.sh

	Edit this file with the file location for htu21df.py

	Place this file in /etc/init.d/

	This file will be called by the modified rc.local to start monitoring on boot


rc.local (/etc/rc.local)

	Edit and add the following line before exit 0 

	Add sudo sh /etc/init.d/launch_htu21df.sh >> /root/htu21df.log & 

