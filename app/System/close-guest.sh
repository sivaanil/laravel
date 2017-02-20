#!/bin/bash

# Try to stop cleanly first
echo "Killing VNC..."
/usr/bin/php /home/c2-maintenance/sites/unified/artisan csquared:killallvnc
sleep 5

#echo "Stopping TCP listener..."
supervisorctl stop browser-listener
sleep 5

# Cleanup if anything didn't stopped properly
killall firefox
killall Xvnc4
rm -f /home/c2-guest/.vnc/*.pid
rm -f /tmp/.X1-lock
rm -f /tmp/.X11-unix/X1
