#!/bin/bash

### START CONFIG #################################################

#Have wget installed...

#FULL system path to video recording directory
VIDEO_DIR="/home/YOUR_USERNAME_HERE/path/to/recordings/"

#Video file name to save as
VIDEO_FILE="webcam_recording.asf"

#Foscam username
USER="USERNAME_HERE"

#Foscam password
PASS="PASSWORD_HERE"

#Webcam IP Address
IP="YOUR.IP.ADDRESS.HERE"

### END CONFIG #################################################

#---includes audio--- on foscams (Foscam FI8918W)
CAM_URL="http://$USER:$PASS@$IP/videostream.asf"

cd $VIDEO_DIR
nohup wget $CAM_URL -O $VIDEO_FILE > /dev/null 2>&1 &
