#!/bin/bash

echo "Restarting TCP listener..."
supervisorctl start browser-listener
