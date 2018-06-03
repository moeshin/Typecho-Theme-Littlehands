<?php
/**
 * TOC
 * 
 * @package TOC 
 * @author 小さな手は
 * @version 1.0.0
 * @link https://www.littlehands.site/
 */
class TOC{
	
	/**
	 * DOMDocument的实例
	 *
	 * @access private
	 * @var DOMDocument
	 */
	private static $dom = null;
	
	/**
	 * DOMXPath的实例
	 *
	 * @access private
	 * @var DOMXPath
	 */
	private static $xpath = null;
	
	/**
	 * 建立目录
	 * 
	 * @access public
	 * @param string
	 * @return string
	 */
	public static function build($content,$single){
		$html = '';
		if($single){
			$dom = self::$dom?self::$dom:(self::$dom = new DOMDocument());
			libxml_use_internal_errors(true);
			$dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><body>'.$content.'</body>');
			libxml_use_internal_errors(false);
			
			if(self::$xpath){
				self::$xpath->__construct($dom);
				$xpath = self::$xpath;
			}else
				self::$xpath = $xpath = new DOMXPath($dom);
			$objs = $xpath->query('//h1|//h2|//h3|//h4|//h5|//h6');
			if (!$objs->length)
				return $content;
			$arr = [];
			$html = '<div class="TOC"><span>目录</span>';
			foreach($objs as $n => $obj){
				$obj->setAttribute('id','TOC'.$n);
				self::handle($obj,$n,$arr,$html);
			}
			foreach($arr as $n)
				$html .= '</li></ol>';
			$html .= '</div>';
			$content = self::html($xpath->document->getElementsByTagName('body')->item(0));
		}
		return preg_replace('#<p>\[toc\]</p>#i',$html,$content);
	}
	
	/**
	 * 处理目录
	 * 
	 * @param DOMElement $obj
	 * @param int $n
	 * @param array &$arr
	 * @param string &$html
	 * @return void
	 */
	public static function handle($obj,$n,&$arr,&$html){
		$i = str_replace('h','',$obj->tagName);
		$j = end($arr);
		if($i > $j){
			$arr[] = $i;
			$html .= '<ol>';
		}else if($i == $j)
			$html .= '</li>';
		else if(in_array($i,$arr)){
			$html .= '</li></ol>';
			array_pop($arr);
			self::handle($obj,$n,$arr,$html);
			return;
		}else{
			$arr = [$i];
			$html .= '</li>';
		}
		$html .= '<li><a href="#TOC'.$n.'">'.$obj->textContent.'</a>';
	}
	
	/**
	 * 获取DOMDocument的HTML
	 * 
	 * @param DOMElement $obj
	 * @return string
	 */
	public static function html($obj){
		$dom = self::$dom?self::$dom:(self::$dom = new DOMDocument());
		$html = '';
		foreach ($obj->childNodes as $child)
			$html .= $dom->saveHTML($child);
		return $html;
	}
}
?>