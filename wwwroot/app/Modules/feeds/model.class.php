<?php
/**
 * author vking
 * 文章
 */
namespace Modules\feeds;
use Think\Model\RelationModel;
class model extends RelationModel{
	protected $tableName = 'feeds';
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