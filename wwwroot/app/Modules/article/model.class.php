<?php
/**
 * author vking
 * 文章
 */
namespace Modules\article;
use Think\Model\RelationModel;
class model extends RelationModel{
	protected $tableName = 'cms_article'; 
	protected $pk     = 'id';
	
	protected $_validate = array(
     	array('title','require','标题必须设定！'),
     	array('content','require','内容必须设定！'),
    );
	protected $_link = array(
	   	'content'=> array(
			'mapping_type'      => self::HAS_ONE,
	        'class_name'        => 'cms_article_content',
	        'foreign_key'=>'aid',
	        'as_fields'=>'content',
	        'mapping_name'=>'content',
		),
		'classic'=> array(
			'mapping_type'      => self::MANY_TO_MANY,
	        'class_name'        => 'cms_classify',
	        'foreign_key'=>'aid',
	        'mapping_fields'=>'id,name',
	        'relation_foreign_key'=>'cid',
            'relation_table'=>'cms_article_classify',//三表关联查询  MANY_TO_MANY
	        'mapping_name'=>'classic',
		),
	);
	function __construct(){
		parent::__construct();
	}
}