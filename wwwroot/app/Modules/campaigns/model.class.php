<?php
/**
 * author vking
 * 文章
 */
namespace Modules\campaigns;
use Think\Model\RelationModel;
class model extends RelationModel{
	protected $tableName = 'campaigns';
	protected $pk     = 'id';
	
	protected $_validate = array(
     	array('id','require','ID require！'),
    );
	protected $_link = array(

	);
	function __construct(){
		parent::__construct();
	}
}