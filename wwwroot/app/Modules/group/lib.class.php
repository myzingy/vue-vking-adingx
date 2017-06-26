<?php
/**
 * author vking
 * 文章
 */
namespace Modules\group;
class lib{
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