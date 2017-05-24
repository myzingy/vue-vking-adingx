<?php
// +----------------------------------------------------------------------
// | 	Xy Blog [ By xiaoyu ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://dingxiaoyu.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: xiaoyu <614422099@qq.com>
// +----------------------------------------------------------------------

/**
 * RSS输出和生成类
 * @author    xiaoyu <614422099@qq.com>
 */
class Rss {//类定义开始

	private $config =   array(
	    'encoding'          	=>  'UTF-8',					// 缩略图扩展名 
        'rssVer'           		=>  '2.0',    					// 上传文件的最大值
        'channelTitle'      	=>  '',    						// 网站标题
        'channelLink'         	=>  '',    						// 网站首页地址
        'channelDescription'    =>  '',    						// 描述
        'language'             	=>  'zh_CN',    				// 使用的语言（zh-cn表示简体中文）
        'copyright'    			=>  '',    						// 授权信息
        'webMaster'     		=>  '',							// 管理员邮箱
        'managingEditor'    	=>  '',							// 编辑的邮箱地址
        'docs'       			=>  '',							// rss地址
        'pubDate'      			=>  '',							// 最后发布的时间
        'lastBuildDate'         =>  '',							// 最后更新的时间
        'generator'         	=>  'XYBlog RSS Generator',		// 生成器
		'category'				=>	'',
        );
	
	// 生成的原RSS
    private $content = '';
    // Items部分
    private $items = array();
	
    public function __get($name){
        if(isset($this->config[$name])) {
            return $this->config[$name];
        }
        return null;
    }

    public function __set($name,$value){
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

    public function __isset($name){
        return isset($this->config[$name]);
    }
	
	public function content($name){
        if (empty($this->content)) $this->BuildRSS();
		$this->content;
    }
	
    /**
     * 架构函数
     * @access public
     * @param array $config  上传参数
     */
    public function __construct($config=array()) {
		$this->config['pubDate'] = Date('Y-m-d H:i:s',time());
		$this->config['lastBuildDate'] = Date('Y-m-d H:i:s',time());
        if(is_array($config)) {
            $this->config   =   array_merge($this->config,$config);
        }
    }
	
	/**************************************************************************/
	// 函数名: AddItem
	// 功能: 添加一个节点
	// 参数: $title
	// $link
	// $description $pubDate
	/**************************************************************************/
	function AddItem($title, $link, $description ,$pubDate ,$guid ,$author = "XiaoYu" ,$category ) {
		$this->items[] = array('title' => $title ,
								'link' => $link,
								'description' => $description,
								'pubDate' => $pubDate,
								'category' => $category,//实现分组
								'author' => $author,
								'guid' => $guid);
	}
	/**************************************************************************/
	// 函数名: BuildRSS
	// 功能: 生成rss xml文件内容
	/**************************************************************************/
	function BuildRSS() {
		$s = "<?xml version=\"1.0\" encoding=\"{$this->encoding}\" ?>\r\n<rss xmlns:content=\"http://purl.org/rss/1.0/modules/content/\" xmlns:wfw=\"http://wellformedweb.org/CommentAPI/\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\" xmlns:atom=\"http://www.w3.org/2005/Atom\" xmlns:sy=\"http://purl.org/rss/1.0/modules/syndication/\" xmlns:slash=\"http://purl.org/rss/1.0/modules/slash/\" version=\"{$this->rssVer}\">\n";
		$s .= "\t<channel>\r\n";
		$s .= "\t\t<title><![CDATA[{$this->channelTitle}]]></title>\r\n";
		$s .= "\t\t<link><![CDATA[{$this->channelLink}]]></link>\r\n";
		$s .= "\t\t<description><![CDATA[{$this->channelDescription}]]></description>\r\n";
		$s .= "\t\t<language>{$this->language}</language>\r\n";
		if (!empty($this->docs)) {
			$s .= "\t\t<docs><![CDATA[{$this->docs}]]></docs>\r\n";
		}
		if (!empty($this->copyright)) {
			$s .= "\t\t<copyright><![CDATA[{$this->copyright}]]></copyright>\r\n";
		}
		if (!empty($this->webMaster)) {
			$s .= "\t\t<webMaster><![CDATA[{$this->webMaster}]]></webMaster>\r\n";
		}
		if (!empty($this->managingEditor)) {
			$s .= "\t\t<managingEditor><![CDATA[{$this->managingEditor}]]></managingEditor>\r\n";
		}
		if (!empty($this->pubDate)) {
			$s .= "\t\t<pubDate>{$this->pubDate}</pubDate>\r\n";
		}
		if (!empty($this->lastBuildDate)) {
			$s .= "\t\t<lastBuildDate>{$this->lastBuildDate}</lastBuildDate>\r\n";
		}
		if (!empty($this->generator)) {
			$s .= "\t\t<generator>{$this->generator}</generator>\r\n";
		}
		// items
		for ($i=0;$i<count($this->items);$i++) {
			$s .= "\t\t<item>\n";
			$s .= "\t\t\t<title><![CDATA[{$this->items[$i]['title']}]]></title>\r\n";
			$s .= "\t\t\t<link><![CDATA[{$this->items[$i]['link']}]]></link>\r\n";
			$s .= "\t\t\t<description><![CDATA[{$this->items[$i]['description']}]]></description>\r\n";
			$s .= "\t\t\t<pubDate>{$this->items[$i]['pubDate']}</pubDate>\r\n";
			if (!empty($this->items[$i]['category'])) {
				$s .= "\t\t\t<category>{$this->items[$i]['category']}</category>\r\n";
			}
			if (!empty($this->items[$i]['author'])) {
				$s .= "\t\t\t<author>{$this->items[$i]['author']}</author>\r\n";
			}
			if (!empty($this->items[$i]['guid'])) {
				$s .= "\t\t\t<guid>{$this->items[$i]['guid']}</guid>\r\n";
			}
			$s .= "\t\t</item>\n";
		}
		// close
		$s .= "\t</channel>\r\n</rss>";
		$this->content = $s;
	}
	/**************************************************************************/
	// 函数名: BuildHTML
	// 功能: 生成rss HTML文件内容
	/**************************************************************************/
	function BuildHTML() {
		$s = "<div style=\"display: none;\">";
		// items
		for ($i=0;$i<count($this->items);$i++) {
			$this->items[$i]['title']=preg_replace('/\s/','',$this->items[$i]['title']);
			$this->items[$i]['keyword']=preg_replace('/\s/','',$this->items[$i]['keyword']);
			$s .= '<a href="'.$this->items[$i]['link'].'" title="'.$this->items[$i]['title'].'">'.$this->items[$i]['title'].'</a>';
			$s .= '<a href="'.$this->items[$i]['link'].'" title="'.$this->items[$i]['keyword'].'">'.$this->items[$i]['keyword'].'</a>';
		}
		// close
		$s .= "\t</div>";
		$this->content = $s;
	}
	/**************************************************************************/
	// 函数名: BuildHTML
	// 功能: 生成rss HTML文件内容
	/**************************************************************************/
	function BuildSiteMap() {
		$s = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$s .= "\t<urlset>\r\n";
		// items
		for ($i=0;$i<count($this->items);$i++) {
			$s .= "\t\t<url>\n";
			$s .= "\t\t\t<mobile:mobile type=\"mobile\"/>\r\n";
			$s .= "\t\t\t<loc>{$this->items[$i]['link']}</loc>\r\n";
			$s .= "\t\t\t<lastmod>".substr($this->items[$i]['pubDate'],0,10)."</lastmod>\r\n";
			$s .= "\t\t\t<changefreq>daily</changefreq>\r\n";
			$s .= "\t\t\t<priority>0.8</priority>\r\n";
			$s .= "\t\t</url>\n";
		}
		// close
		$s .= "\t</urlset>";
		$this->content = $s;
	}
	/**************************************************************************/
	// 函数名: Show
	// 功能: 将产生的rss内容直接打印输出
	/**************************************************************************/
	function Show($type='RSS') {
		$Build='Build'.$type;
		if (empty($this->content)) $this->$Build();
		echo($this->content);
	}
	
	/**************************************************************************/
	// 函数名: SaveToFile
	// 功能: 将产生的rss内容保存到文件
	// 参数: $fname 要保存的文件名
	/**************************************************************************/
	function SaveToFile($fname) {
		if (empty($this->content)) $this->BuildRSS();
		$handle = fopen($fname, 'w+');
		if ($handle === false) return false;
		fwrite($handle, $this->content);
		fclose($handle);
	}
	
	/**************************************************************************/
	// 函数名: getFile
	// 功能: 从文件中获取输出
	// 参数: $fname 文件名
	/**************************************************************************/
	function getFile($fname) {
		$handle = fopen($fname, 'r');
		if ($handle === false) return false;
		while(!feof($handle)){
			echo fgets($handle);
		}
		fclose($handle);
		}
}