#!/bin/bash

### START CONFIG #################################################

#Make sure ALL /bin/ paths match your system's setup (via "whereis xxxx" command in a terminal)...

#FULL system path to your video recording directory
VIDEO_DIR="/home/YOUR_USERNAME_HERE/path/to/recordings/"

#Delete older than (days)
OLDER_THAN=4

### END CONFIG #################################################

/usr/bin/find $VIDEO_DIR* -mtime +$OLDER_THAN -exec rm -rf {} \;
