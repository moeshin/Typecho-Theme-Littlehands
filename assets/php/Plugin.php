<?php
/**
 * 主题插件
 * 
 * @package theme_plugin 
 * @author 小さな手は
 * @link https://www.littlehands.site/
 */
class theme_plugin{
	
	/**
	 * 版本号
	 *
	 * @access public
	 */
	public static $version = '1.0.0';
	
	/**
	 * 管理页面页脚自定义
	 *
	 * @access public
	 * @return void
	 */
	public static function admin_footer(){
		$options = Helper::options();
		$themeUrl = $options->themeUrl;
		$siteUrl = $options->siteUrl;
		switch(preg_replace('#.*?/admin/(.*?)(?:\.php|)$#','$1',$_SERVER['PHP_SELF'],1)){
			case 'options-theme':
				echo "<script>var themeUrl = '$themeUrl',siteUrl = '$siteUrl';</script><script src=\"$themeUrl/assets/js/options-theme.js\"></script><link rel=\"stylesheet\" href=\"$themeUrl/assets/css/options-theme.css\">";
				break;
		}
	}
	
	/**
	 * 处理文章内容
	 *
	 * @access public
	 * @param string
	 * @param Widget_Abstract_Contents
	 * @param string
	 * @return string 
	 */
	public static function Widget_Abstract_Contents_contentEx($content,$archive,$last) {
		if($last) $content = $last;
		require_once 'phpQuery.php';
		$pq = phpQuery::newDocumentHTML($content);
		pq('a')->attr('target','_blank');
		foreach(pq(':not(a)>img') as $val){
			pq($val)->wrap('<a class="lg" href="'.pq($val)->attr('src').'"></a>');
		}
		pq('pre')->addClass('line-numbers');
		pq('pre>code:not([class])')->addClass('language-default');
		$advanced = Helper::options()->advanced;
		if($advanced){
			if(in_array('lazyload',$advanced)){
				$img = getLazyLoadImg();
				foreach(pq('img') as $val){
					pq($val)->attr('data-original',pq($val)->attr('src'))->attr('src',$img);
				}
			}
			if(in_array('prism', $advanced)) foreach(pq('pre code:not([class*=lang]),pre code:[class*=":"]') as $val){
				$val = pq($val);
				$class = $val->attr('class');
				if(count(explode(' ',$class)) == 1){
					$test = explode(':',$class);
					$bool = count($test) == 1;
					$list = explode('#',$bool?$val->attr('rel'):$test[1]);
					$k1 = $v1 = $k2 = $v2 = null;
					$v = [];
					foreach($list as $value){
						$key = explode('.',$value);
						if(count($key)>0) switch($key[0]){
							case 'l': $v['data-line'] = implode(',',explode('+',$key[1])); break;
							case 's': $v['data-start'] = $key[1]; break;
						}
					}
					$val->attr('class',$bool?('language-'.$class):$test[0])->removeAttr('rel')->parent()->attr($v);
				}
			}
		}
		return $pq->html();
	}
	
	/**
	 * MarkDown解析
	 *
	 * @access public
	 * @param string
	 * @return string 
	 */
	public static function markdown($text) {
		require_once 'Parsedown.php';
		return Parsedown::instance()->setBreaksEnabled(true)->text($text);
	}

	public static function filter($value, $that)
    {
        /** 如果访问权限被禁止 */
        if ($value['hidden']) {
            $value['text'] = '<form class="protected" action="'
                . $that->widget('Widget_Security')->getTokenUrl($value['permalink'])
                . '" method="post">' .
                '<p class="word">' . _t('请输入密码访问') . '</p>' .
                '<p><input type="password" class="text" name="protectPassword" />
            <input type="hidden" name="protectCID" value="' . $value['cid'] . '" />
            <input type="submit" class="submit" value="' . _t('提交') . '" /></p>' .
                '</form>';

            $value['tags'] = array();
            $value['commentsNum'] = 0;
            $value['hidden'] = false;
        }

        return $value;
    }
	
	/**
	 * 初始化-注册插件
	 * 
	 * @access public
	 */
	public static function init() {
		/** 注册插件 */
		$advanced = Helper::options()->advanced;
		Typecho_Plugin::factory('admin/footer.php')->end = ['theme_plugin', 'admin_footer'];
		Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = ['theme_plugin', 'Widget_Abstract_Contents_contentEx'];
		if(inArray('Parsedown', $advanced)){
			Typecho_Plugin::factory('Widget_Abstract_Contents')->markdown = ['theme_plugin', 'markdown'];
			Typecho_Plugin::factory('Widget_Abstract_Comments')->markdown = ['theme_plugin', 'markdown'];
		}
		if(!class_exists('ShortCode')&&inArray('ShortCode', $advanced))
			require_once 'ShortCode.php';
		if (inArray('ShowTitleWithPassword', $advanced)) {
            Typecho_Plugin::factory('Widget_Abstract_Contents')->filter = ['theme_plugin', 'filter'];
        }
	}
}
?>
