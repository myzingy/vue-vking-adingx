<?php
namespace Home\Model;
use Think\Model;
class UserModel extends Model{
	protected $tableName='user_consumer';
	protected $fields = array(
		'id', 'device_id', 'device_token', 'phone', 'name', 'gender', 'birth',
		'photo', 'addtime', 'points', 'status', 'device_type','push_token', 
		'_pk'=>'id'
	);
	
}
