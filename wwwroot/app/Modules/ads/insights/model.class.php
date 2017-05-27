<?php
/**
 * author vking
 * 文章
 */
namespace Modules\ads\insights;
use Think\Model\RelationModel;
class model extends RelationModel{
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
	);
	function __construct(){
		parent::__construct();
	}
}