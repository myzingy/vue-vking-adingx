<?php
/**
 * author vking
 * 文章
 */
namespace Modules\adsets\insights;
use Think\Model\RelationModel;
class model extends RelationModel{
    const INSIGHT_TYPE_TODAY=0;
    const INSIGHT_TYPE_YESTODAY=99;
    const INSIGHT_TYPE_LAST_7DAY=7;
    const INSIGHT_TYPE_LAST_14DAY=14;
	protected $tableName = 'adsets_insights';
	protected $pk     = 'id';
	
	protected $_validate = array(
     	array('id','require','ID require！'),
    );
	protected $_link = array(
        'adsets_insights_action_types'=> array(
            'mapping_type'      => self::HAS_MANY,
            'class_name'        => 'adsets_insights_action_types',
            'foreign_key'=>'adsets_insights_id',
            'mapping_name'=>'adsets_insights_action_types',
        ),
        'adsets'=> array(
            'mapping_type'      => self::BELONGS_TO,
            'class_name'        => 'adsets',
            'foreign_key'=>'adset_id',
            'mapping_name'=>'adsets',
            'as_fields'=>'effective_status,daily_budget'
        ),
        'rules_run'=> array(
            'mapping_type'      => self::HAS_ONE,
            'class_name'        => 'adsets_rules_run',
            'foreign_key'=>'id',
            
            'mapping_name'=>'rules_run',
            'as_fields'=>'rule_runtime'
        ),
	);
	function __construct(){
		parent::__construct();
	}
}