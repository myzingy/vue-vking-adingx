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
         'insights'=>array(
             'mapping_type'      => self::HAS_MANY,
             'class_name'        => insights_model,
             'foreign_key'=>'campaign_id',
             'mapping_name'=>'insights',
         ),
	);
	function __construct(){
		parent::__construct();
	}
}