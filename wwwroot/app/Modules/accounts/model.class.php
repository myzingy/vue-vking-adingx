<?php
/**
 * author vking
 * 文章
 */
namespace Modules\accounts;
use Think\Model\RelationModel;
class model extends RelationModel{
	protected $tableName = 'accounts';
	protected $pk     = 'account_id';
	
	protected $_validate = array(
     	array('account_id','require','ID require！'),
    );
	protected $_link = array(
        'insights'=> array(
            'mapping_type'      => self::HAS_MANY,
            'class_name'        => 'accounts_insights',
            'foreign_key'=>'account_id',
            'mapping_name'=>'insights',
            //'mapping_fields'=>'rule_runtime',
            "mapping_limit"=>4,
            "mapping_order"=>'date_stop desc'
        ),
	);
	function __construct(){
        parent::__construct();
	}
}