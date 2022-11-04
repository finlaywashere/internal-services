#!/bin/bash

/usr/bin/scanimage -d 'escl:https://192.168.33.31:443' --batch='/tmp/document%d.jpg' --source=ADF --resolution=300 --format=jpeg
