#!/bin/sh
#
### BEGIN INIT INFO
# Short-Description: Start No-IP
# Description:       Reads internal IP address and initiates No-IP. 
### END INIT INFO

# Sleep 30 seconds before checking for IP
sleep 30

# Print the IP address
_IP=$(hostname -I) || true
if [ "$_IP" ]; then
  printf "My IP address is %s\n" "$_IP"
fi

# Run No-IP
/usr/local/bin/noip2