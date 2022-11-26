#!/bin/bash

rm -rf $1
mkdir -p $1

/usr/bin/scanimage -d 'escl:https://192.168.33.31:443' --batch="$1/document%d.jpg" --source=ADF --resolution=300 --format=jpeg
convert $1/document*.jpg $1/document.pdf
