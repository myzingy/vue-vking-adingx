<?php
/**
 * author vking
 * 文章
 */
namespace Modules\campaigns\insights;
use Think\Model\RelationModel;
class model extends RelationModel{
    const INSIGHT_TYPE_TODAY=0;
    const INSIGHT_TYPE_YESTODAY=99;
    const INSIGHT_TYPE_LAST_7DAY=7;
    const INSIGHT_TYPE_LAST_14DAY=14;
	protected $tableName = 'campaigns_insights';
	protected $pk     = 'id';
	
	protected $_validate = array(
     	array('id','require','ID require！'),
    );
	protected $_link = array(
        'campaigns_insights_action_types'=> array(
            'mapping_type'      => self::HAS_MANY,
            'class_name'        => 'campaigns_insights_action_types',
            'foreign_key'=>'campaigns_insights_id',
            'mapping_name'=>'campaigns_insights_action_types',
        ),
        'campaigns'=> array(
            'mapping_type'      => self::BELONGS_TO,
            'class_name'        => 'campaigns',
            'foreign_key'=>'campaign_id',
            'mapping_name'=>'campaigns',
            'as_fields'=>'effective_status'
        ),
	);
	function __construct(){
		parent::__construct();
	}
}