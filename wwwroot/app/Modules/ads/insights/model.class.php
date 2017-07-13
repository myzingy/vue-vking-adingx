<?php
/**
 * author vking
 * 文章
 */
namespace Modules\ads\insights;
use Think\Model\RelationModel;
class model extends RelationModel{
    const INSIGHT_TYPE_TODAY=0;
    const INSIGHT_TYPE_YESTODAY=99;
    const INSIGHT_TYPE_LAST_7DAY=7;
    const INSIGHT_TYPE_LAST_14DAY=14;
    const INSIGHT_TYPE_LIFETIME=100;

    protected $tableName = 'ads_insights';
	protected $pk     = 'id';
	
	protected $_validate = array(
     	array('id','require','ID require！'),
    );
	protected $_link = array(
        'ads_insights_action_types'=> array(
            'mapping_type'      => self::HAS_MANY,
            'class_name'        => 'ads_insights_action_types',
            'foreign_key'=>'ads_insights_id',
            'mapping_name'=>'ads_insights_action_types',
        ),
        'ads'=> array(
            'mapping_type'      => self::BELONGS_TO,
            'class_name'        => 'ads',
            'foreign_key'=>'ad_id',
            'mapping_name'=>'ads',
            'as_fields'=>'effective_status,rule_runtime'
        ),
	);
	function __construct(){
		parent::__construct();
	}
}