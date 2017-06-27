<?php
/**
 * author vking
 * 文章
 */
namespace Modules\group;
class lib{
    const GROUP_ID_ADMIN=0;
    const GROUP_ID_POWER_EDITOR=1;
    const GROUP_ID_DESIGNERS=2;

    function __construct() {
    }
    function getGroupRules($user){
        $gr=C('group_rules');
        $rules=$gr[$user->group_id]['rules'];
        $rules=is_string($rules)?explode(',',$rules):$rules;
        $nav=[];
        foreach ($rules as $key){
            $nav[$key]=true;
        }
        return ['data'=>$nav];
    }
}