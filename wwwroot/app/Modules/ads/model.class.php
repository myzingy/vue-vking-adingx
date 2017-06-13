<?php
/**
 * author vking
 * 文章
 */
namespace Modules\ads;
use Think\Model\RelationModel;
class model extends RelationModel{
	protected $tableName = 'ads';
	protected $pk     = 'id';
	
	protected $_validate = array(
     	array('id','require','ID require！'),
    );
	protected $_link = array(
        'rules_run'=> array(
            'mapping_type'      => self::HAS_ONE,
            'class_name'        => 'ads_rules_run',
            'foreign_key'=>'id',
            'mapping_name'=>'rules_run',
            'as_fields'=>'rule_runtime'
        ),
        'insights'=> array(
            'mapping_type'      => self::HAS_MANY,
            'class_name'        => 'ads_insights',
            'foreign_key'=>'ad_id',
            'mapping_name'=>'insights',
            //'mapping_fields'=>'rule_runtime',
            "mapping_limit"=>4,
            "mapping_order"=>'date_stop desc'
        ),
	);
	function __construct(){
        $this->_link['insights']['condition']=" date_stop>='".date('Y-m-d',strtotime('-1 day'))."' ";
		parent::__construct();
	}
}