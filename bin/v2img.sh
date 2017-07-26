#!/bin/bash
function str_replace()
{
    echo ${1//$2/$3}
}
VIDEO_DIR=/var/www/html/wwwroot/uploads/
IMAGE_DIR=/var/www/html/wwwroot/video-thumb/
for file in $VIDEO_DIR*
do
    if test -f $file
    then
	filesize=$(stat -c '%s' $file)
	if [[ $filesize -gt 0 ]];then
	    img_file=`str_replace $file.jpg $VIDEO_DIR $IMAGE_DIR`
	    #img_file=${$file.jpg//$VIDEO_DIR/$IMAGE_DIR}
	    if [[ ! -f $img_file ]];then
    	        echo $file ==> $filesize ==> $img_file
		ffmpeg -ss 00:00:05 -i $file -f image2 -y $img_file

	    fi
			

	fi 
    else
        echo $file
    fi
done

