#!/bin/bash
#PAGE_ID=550007418463679
PAGE_ID=882970615126496
VIDEO_PATH=/tmp/video
VIDEO_DIR=$VIDEO_PATH/
VIDEO_LOG=$VIDEO_PATH.upload.log
VIDEO_DIR_BAK=$VIDEO_PATH-bak/
URI=https://graph-video.facebook.com/v2.3/$PAGE_ID/videos
TOKEN=user_robot_token
funUpload(){
    FILE_NAME=$1
    FILE_SIZE=$2
    #echo "$FILE_NAME-----$FILE_SIZE"
    #get upload_session_id
    RES=`curl -X POST $URI -F "access_token=$TOKEN" -F "upload_phase=start" -F "file_size=$FILE_SIZE"`
    #echo $RES
    UP_SID=`echo $RES | jq '.upload_session_id'`
    UP_SID=${UP_SID//\"}
    #echo $UP_SID
    if [[ -z $UP_SID ]];then
        echo "-1,$RES"
        return
    fi

    #set file
    RES2=`curl -X POST $URI -F "access_token=$TOKEN" -F "upload_phase=transfer" \
    -F "start_offset=0" -F "upload_session_id=$UP_SID" -F "video_file_chunk=@$FILE_NAME"`
    #echo $RES2
    start_offset=`echo $RES2 | jq '.start_offset'`
    end_offset=`echo $RES2 | jq '.end_offset'`
    if [[ $start_offset != $end_offset ]];then
        echo "-2,$RES2"
        return;
    fi

    #upload file
    RES3=`curl -X POST $URI -F "access_token=$TOKEN" -F "upload_phase=finish" -F "upload_session_id=$UP_SID"`
    #echo $RES3
    success=`echo $RES3 | jq '.success'`
    if test ! $success; then
        echo "-3,$RES3";
        return;
    fi

    UP_VID=`echo $RES | jq '.video_id'`
    UP_VID=${UP_VID//\"}
    echo $UP_VID
    return
}
echo "">$VIDEO_LOG;
if [[ ! -d $VIDEO_DIR_BAK ]];then
    mkdir $VIDEO_DIR_BAK
fi
for file in $VIDEO_DIR*
do
    if test -f $file
    then
	filesize=$(stat -c '%s' $file)
	if [[ $filesize -gt 0 ]];then
	    video_id=`funUpload $file $filesize`
	    if echo $video_id | egrep -q '^[0-9]+$'; then
	        echo "video_id:$video_id";
	        echo "\"$file\":\"$video_id\"">>$VIDEO_LOG
	        mv -f $file $VIDEO_DIR_BAK
	    else
	        echo "ERROR:$video_id";
	    fi
	fi
    else
        echo "$file size 0"
    fi
done
