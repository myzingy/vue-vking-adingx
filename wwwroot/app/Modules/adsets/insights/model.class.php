<?php
/**
 * author vking
 * 文章
 */
namespace Modules\adsets\insights;
use Think\Model\RelationModel;
class model extends RelationModel{
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
            'as_fields'=>'effective_status'
        ),
	);
	function __construct(){
		parent::__construct();
	}
}