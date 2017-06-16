<?php
/**
 * author vking
 * 文章
 */
namespace Modules\accounts\insights;
use Think\Model\RelationModel;
class model extends RelationModel{
    const INSIGHT_TYPE_TODAY=0;
    const INSIGHT_TYPE_YESTODAY=99;
    const INSIGHT_TYPE_LAST_7DAY=7;
    const INSIGHT_TYPE_LAST_14DAY=14;

    protected $tableName = 'accounts_insights';
	protected $pk     = 'id';
	
	protected $_validate = array(
     	array('id','require','ID require！'),
    );
	protected $_link = array(
        'accounts_insights_action_types'=> array(
            'mapping_type'      => self::HAS_MANY,
            'class_name'        => 'accounts_insights_action_types',
            'foreign_key'=>'accounts_insights_id',
            'mapping_name'=>'accounts_insights_action_types',
        )
	);
	function __construct(){
		parent::__construct();
	}
}