#!/bin/bash

echo "Removing guest user home directory and contents"
rm -r /home/c2-guest/
echo "Populating guest user home directory and contents"
tar -xf /root/c2-guest-home.tar.gz -C /
