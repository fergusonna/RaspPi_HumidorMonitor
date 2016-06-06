#!/usr/bin/python
##
## Script to insert temperature and humidity readings to a MySQL database
##
import time
import HTU21DF
import sys
import MySQLdb

timestamp = (time.time())
raw_temp = HTU21DF.read_temperature()
raw_humidity = HTU21DF.read_humidity()

round_temp = round(raw_temp, 1)
humidity = round(raw_humidity,1)

temperature = round((1.8*round_temp)+32, 1)

while True:
        db = MySQLdb.connect(host="SERVER",user="USERNAME",passwd="PASSWORD",db="DATABASE")

        cur = db.cursor()

        sql = """INSERT INTO TempHumid(id,ComputerTime,Temperature,Humidity) VALUES(NULL,'%s','%s','%s')"""%(timestamp,temperature,humidity)

        try:
                cur.execute(sql)
                db.commit()
        except:
                db.rollback()

        db.close()
        time.sleep(1800)