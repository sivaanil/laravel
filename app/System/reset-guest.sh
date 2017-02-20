#!/bin/bash
# cron job run once per day to reset guest environment back to defaults

mypath=/home/c2-maintenance/sites/unified/app/System

$mypath/close-guest.sh
$mypath/repop-guest.sh
$mypath/restart-guest.sh

service guacd restart
service tomcat7 restart
