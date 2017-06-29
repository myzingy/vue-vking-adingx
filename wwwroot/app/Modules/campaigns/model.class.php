<?php
/**
 * author vking
 * 文章
 */
namespace Modules\campaigns;
use Think\Model\RelationModel;
use \Modules\campaigns\insights\model as insights_model;
class model extends RelationModel{
	protected $tableName = 'campaigns';
	protected $pk     = 'id';
	
	protected $_validate = array(
     	array('id','require','ID require！'),
    );
	protected $_link = array(
        'insights'=> array(
            'mapping_type'      => self::HAS_MANY,
            'class_name'        => 'campaigns_insights',
            'foreign_key'=>'campaign_id',
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
    function setDateStop($date_stop){
        $this->_link['insights']['condition']=" date_stop='".$date_stop."' ";
        $this->_link['insights']['mapping_limit']=1;
        parent::__construct();
    }
}