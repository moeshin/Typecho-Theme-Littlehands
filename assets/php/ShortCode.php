<?php
/** 注册插件 */
Typecho_Plugin::factory('Widget_Abstract_Contents')->content = ['ShortCode', 'content'];
Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = ['ShortCode', 'contentEx'];
require_once 'TOC.php';

/**
 * Short Code
 * 
 * @package ShortCode 
 * @author 小さな手は
 * @version 1.0.1
 * @link https://www.littlehands.site/
 */
class ShortCode{
	
	/**
	 * 已注册的短代码列表
	 *
	 * @access private
	 * @var array
	 */
	private static $ShortCodes = [];

	/**
	 * 实例
	 *
	 * @access private
	 * @var array
	 */
	private static $instance = null;

	/**
	 * 是否强制处理文本
	 *
	 * @access public
	 * @var bool
	 */
	public static $isForce = false;

	/**
	 * 注册短代码
	 *
	 * @access public
	 * @param mixed $names 短代码名称，可以一个字符串或字符串数组
	 * @param mixed $callbacks 短代码对应回调函数，可以一个回调函数或回调函数数组
	 * @param bool $overried 覆盖已注册的短代码<br>可选，默认<code>false</code>
	 * @return ShortCode
	 */
	public static function set($names,$callbacks,$overried = false){
		if(!is_array($names)) $names = [$names];
		if(!is_array($callbacks)) $callbacks = [$callbacks];
		$i = count($callbacks)-1;
		foreach($names as $j => $name){
			$k = $j;
			if($i<$j) $k = $i;
			$callback = $callbacks[$k];
			if(!array_key_exists($name,self::$ShortCodes)||$overried)
				self::$ShortCodes[$name] = $callback;
		}
		return self::instance();
	}

	/**
	 * 移除短代码
	 *
	 * @access public
	 * @param string $name 短代码名称
	 * @param callback $callback 只有回调函数相同，短代码才会被移除<br>可选，默认<code>Null</code>
	 * @return ShortCode
	 */
	public static function remove($name,$callback = null){
		if(isset(self::$ShortCodes[$name]))
			if(self::$ShortCodes[$name] === $callback||empty($callback))
				unset(self::$ShortCodes[$name]);
		return self::instance();
	}
	
	/**
	 * 移除所有短代码
	 *
	 * @access public
	 * @return ShortCode
	 */
	public static function removeAll(){
		self::$ShortCodes[$key] = [];
		return self::instance();
	}
	
	/**
	 * 获取短代码列表
	 * 
	 * @access public
	 * @return array
	 */
	public static function get(){
		return self::$ShortCodes;
	}
	
	/**
	 * 强制处理文本
	 * 使用此插件后Markdown或AutoP失效，使用此函数，并传入<code>true</code>值
	 * @access public
	 * @param bool
	 * @return array
	 */
	public static function isForce($bool = null){
		if(is_bool($bool)) self::$isForce = $bool;
		return self::$isForce;
	}
	
	/**
	 * 文本处理
	 *
	 * @access public
	 * @param string
	 * @retur string
	 */
	public static function handle($content){
		$pattern  = [];
		$RegExp = '((?:"[^"]*"|'."'[^']*'|[^'".'"\]])*)';
		foreach(array_keys(self::$ShortCodes) as $name)
			array_push($pattern,
				"#\\\\\[|\[($name)$RegExp\](.*?)\[/$name\]#i",
				"#\\\\\[|\[($name)$RegExp\]()#i"
			);
		return preg_replace_callback($pattern,function($a){
			if(count($a) == 1)
				return $a[0];
			$name = strtolower($a[1]);
			$ShortCodes = self::$ShortCodes;
			$callback = $ShortCodes[$name];
			if(array_key_exists($name,$ShortCodes)&&is_callable($callback))
				return call_user_func($callback,$name,$a[2],$a[3],$a[0]);
			else
				return $a[0];
		},$content);
	}
	
	/**
	 * 插件处理 content
	 *
	 * @access public
	 * @param string
	 * @param Widget_Abstract_Contents
	 * @param string
	 * @return string
	 */
	public static function content($content,$archive,$last){
		if($last) $content = $last;
		$content = self::handle($content);
		if(Typecho_Plugin::export()['handles']['Widget_Abstract_Contents:content'] === [[__Class__,__Function__]]||self::$isForce)
			return $archive->isMarkdown?$archive->markdown($content):$archive->autoP($content);
		return $content;
	}
	
	/**
	 * 插件处理 contentEx
	 *
	 * @access public
	 * @param string
	 * @param Widget_Abstract_Contents
	 * @param string
	 * @return string
	 */
	public static function contentEx($content,$archive,$last){
		if($last) $content = $last;
		return TOC::build($content,$archive->is('single'));
	}
	
	/**
	 * 获取实例
	 * 
	 * @access private
	 * @return ShortCode
	 */
	private static function instance(){
		return self::$instance?self::$instance:new ShortCode();
	}
	
	/**
	 * 构造函数
	 *
	 * @access public
	 */
	public function __construct(){
		self::$instance = $this;
	}
	
}
?>