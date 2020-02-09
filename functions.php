<?php
timer_start(); //记录开始时间
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
require_once 'assets/php/Plugin.php';
theme_plugin::init();
function themeConfig($form) {
	$themeUrl = Helper::options()->themeUrl;
	$siteUrl = Helper::options()->siteUrl;
	
	Typecho_Widget::widget('Widget_User')->to($user);
	$logoUrl = new Typecho_Widget_Helper_Form_Element_Text('logoUrl',NULL,
	'https://cdn.v2ex.com/gravatar/'.md5(strtolower($user->mail)).'?s=100',
	_t('站点 LOGO 地址'),_t('在这里填入一个图片 URL 地址, 以在网站标题前加上一个64*64的LOGO'));
	$form->addInput($logoUrl);
	
	$backgroundUrl = new Typecho_Widget_Helper_Form_Element_Text('backgroundUrl',NULL,
		$siteUrl.str_replace('\\','/',preg_replace('#'.preg_quote(__TYPECHO_ROOT_DIR__).'[\\\|\/]#','',__DIR__,1)).'/background.jpg',
		_t('背景图片'),_t('在这里填入一个图片 URL 地址, 应到网站背景<br>背景图片默认显示为:居中上顶 不重复 固定')
	);
	$form->addInput($backgroundUrl);
	
	$faviconUrl = new Typecho_Widget_Helper_Form_Element_Text('faviconUrl',NULL,'/favicon.ico',
	_t('站点ICO图标地址'),_t('在这里填入一个ICO图标URL地址, 以在网站标题上显示一个ICO图标'));
	$form->addInput($faviconUrl);
	
	$gravatar = new Typecho_Widget_Helper_Form_Element_Text('gravatar',NULL,'//cdn.v2ex.com/gravatar/',_t('Gravatar头像源'));
	$form->addInput($gravatar);
	
	$lazyload_img = new Typecho_Widget_Helper_Form_Element_Text('lazyload_img',NULL,'{themeUrl}/loading.gif',_t('懒加载占位图片'),'内容替换：<br>{siteUrl}：网站地址<br>{themeUrl}：主题地址');
	$form->addInput($lazyload_img);
	
	$baidu = new Typecho_Widget_Helper_Form_Element_Text('baidu',NULL,NULL,_t('百度统计ID'));
	$form->addInput($baidu);
	
	$sticky = new Typecho_Widget_Helper_Form_Element_Text('sticky',NULL,NULL,_t('文章置顶'),_t('填写文章id，多个则使用|分开，置顶顺序按从左到右'));
	$form->addInput($sticky);
	
	$reward = new Typecho_Widget_Helper_Form_Element_Text('reward',NULL,NULL,_t('打赏'),_t('格式：[支付方式](二维码链接)<br>例如：[支付宝](http://example.com/alipay.png)'));
	$form->addInput($reward);
	
	$QPlayer = new Typecho_Widget_Helper_Form_Element_Hidden('QPlayer', NULL, '{"isRandom":true,"isRotate":true}');
	$form->addInput($QPlayer);
	
	$advanced = new Typecho_Widget_Helper_Form_Element_Checkbox('advanced',[
			'Console' => _t('显示控制台'),
			'QPlayer' => _t('启用QPlayer'),
			'pjax' => _t('使用PJAX加载网页'),
			'hitokoto' => _t('在页脚引用<a href="http://hitokoto.cn/">一言（hitokoto）</a>'),
			'lazyload' => _t('懒加载'),
			'emoji' => _t('评论表情'),
			'ShortCode' => _t('使用短代码'),
			'Parsedown' => _t('使用Parsedown解析代替原解析'),
			'prism' => _t('代码框拓展<p class="description">如果当你使用其他的MarkDown解析后最好不要启用<red>代码框拓展</red><br>在注明代码语言后用:号隔开，然后使用以下参数：l表示高亮行，多个用+号分开，连续使用-号；n表示开始行号<br>参数名与参数值之间用.号隔开，参数与参数间用#隔开<br>具体格式：```或~~~语言:参数名.参数值#参数名.参数值<br>例如：```php:l.8+10-15#s.6 则高亮第8、10到15行，从第6行开始<br><strong>注意：</strong>使用的符号都是<red>英文</red>符号</p>')
		],[
			'pjax',
			'hitokoto',
			'lazyload',
			'emoji',
			'ShortCode',
			'Parsedown',
			'prism'
		],_t('高级'));
	$form->addInput($advanced->multiMode());
	
	$form->addInput(new Typecho_Widget_Helper_Form_Element_Hidden('emoji',NULL,'<div class="emoji-package"><div class="emoji-btn"><span data-url="{themeUrl}/emoji/paopao/">泡泡表情</span></div><div class="emoji-body"><ul><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/1.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/2.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/3.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/4.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/5.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/6.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/7.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/8.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/9.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/10.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/11.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/12.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/13.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/14.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/15.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/16.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/17.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/18.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/19.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/20.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/21.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/22.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/23.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/24.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/25.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/26.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/27.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/28.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/29.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/30.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/31.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/32.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/33.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/34.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/35.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/36.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/37.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/38.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/39.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/40.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/41.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/42.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/43.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/44.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/45.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/46.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/47.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/48.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/49.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/50.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/51.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/52.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/53.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/54.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/55.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/56.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/57.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/58.png"></li><li><img class="emoji" data-src="{themeUrl}/emoji/paopao/59.png"></li></ul></div></div>'));
	
	$header = new Typecho_Widget_Helper_Form_Element_Textarea('header',NULL,NULL,_t('自定义页眉'),_t('填写需要的代码，比如css样式、js等'));
	$form->addInput($header);
	
	$footer = new Typecho_Widget_Helper_Form_Element_Textarea('footer',NULL,'Powered by <a href="http://www.typecho.org/" target="_blank">Typecho</a>, Theme by <a href="https://www.littlehands.site/" target="_blank">Littlehands</a>, {inf}<br/>'."\n".'© {Y} <a href="{siteUrl}">{title}</a>. All right reserved. <a href="http://www.miitbeian.gov.cn/" target="_blank">京ICP备00000000号</a>',_t('自定义页脚'),
	_t('填写需要的代码，比如版权、备案号、统计代码等<br/>内容替换：<br/>{Y}：当前年份<br/>{title}：网站名称<br/>{siteUrl}：网站地址<br/>{inf}：页面信息'));
	$form->addInput($footer);
	
	$sidebarBlock = new Typecho_Widget_Helper_Form_Element_Checkbox(
		'sidebarBlock', [
			'ShowRecentPosts' => _t('显示最新文章'),
			'ShowRecentComments' => _t('显示最近回复'),
			'ShowCategory' => _t('显示分类'),
			'ShowArchive' => _t('显示归档'),
			'ShowOther' => _t('显示其它杂项'),
			'Console' => _t('控制台')
		],[
			'ShowRecentPosts',
			'ShowRecentComments',
			'ShowCategory',
			'ShowArchive',
			'ShowOther',
			'Console'
		],
		_t('侧边栏显示')
	);
	$form->addInput($sidebarBlock->multiMode());
}

function themeInit($archive){
	Helper::options()->commentsAntiSpam = false;//强制关闭反垃圾评论
	Helper::options()->commentsHTMLTagAllowed .= '<img src="" class="">';//追加允许使用的HTML标签和属性
	if(class_exists('ShortCode'))
		ShortCode::set(['video','audio'],'ShortCode');
}

function themeFields($layout){
	/*$logoUrl = new Typecho_Widget_Helper_Form_Element_Text('logoUrl', NULL, NULL, _t('站点LOGO地址'), _t('在这里填入一个图片URL地址, 以在网站标题前加上一个LOGO'));
	$layout->addItem($logoUrl);*/
}

/**
 * 短代码处理
 *
 * @param string
 * @param string
 * @param string
 * @param string
 * @return string
 */
function ShortCode($name,$attr,$text,$code){
	switch($name){
		case 'video':
		    if (preg_match('#^https?://(?:www|m).bilibili.com/video/av(\d+)(.*)#i', $text, $matches)) {
                $av = $matches[1];
                $p = 1;
                if ($matches[2] && preg_match('/(?:\?|&)p=(\d+)(?:&|$)/i', $matches[2], $matches)) {
                    $p = $matches[1];
                }
                return "<div class=\"bilibili\"><iframe$attr src=\"https://player.bilibili.com/player.html?aid=$av&page=$p\" allowfullscreen></iframe></div>";
            }
			return '<video controls="controls"'.$attr.'><source src="'.$text.'"></video>';
		case 'audio':
			return '<audio controls="controls"'.$attr.'><source src="'.$text.'"></audio>';
	}
	return $code;
}

/**
 * 获取现行时间
 *
 * @global float $timestart
 * @return float
 */
function timer_get(){
	$mtime = explode(' ',microtime());
	return $mtime[1] + $mtime[0];
}

/**
 * 记录开始时间
 *
 * @global float $timestart
 * @return void
 */
function timer_start(){
	global $timestart;
	$timestart = timer_get();
}

/**
 * 计算运行时间
 *
 * @global float $timestart
 * @param bool $display 是否输出
 * @param int $precision 保留小数位数
 * @return float
 */
function timer_stop($display = false,$precision = 3){
	global $timestart;
	$timetotal = timer_get()-$timestart;
	$r = number_format( $timetotal, $precision );
	if($display) echo $r;
	return $r;
}

/**
 * 获取搜索关键词
 *
 * @return string
 */
function getSearchText(){
	$text = str_replace('search/','',str_replace(preg_replace('/(http|https):\/\//','',Helper::options()->index.'/'),'',preg_replace('/\/$/','',$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])),$count);
	if($count == 1){
		return urldecode($text);
	}else{
		return '';
	}
}

/**
 * 检测服务器是否SSL连接（Https连接）
 *
 * @return bool
 */
function is_SSL(){
	if(!isset($_SERVER['HTTPS'])) return FALSE;
	if($_SERVER['HTTPS']===1) return TRUE; //Apache
	elseif($_SERVER['HTTPS']==='on') return TRUE; //IIS
	elseif($_SERVER['SERVER_PORT']==443) return TRUE; //其他
	return FALSE;
}

/**
 * 检测服务器是否Gzip压缩
 *
 * @return bool
 */
function is_Gzip(){
	return !(strpos(strtolower($_SERVER['HTTP_ACCEPT_ENCODING']),'gzip') === false)?true:false;
}

/**
 * 输出摘要
 * 
 * @param Widget_Abstract_Contents $archive
 * @return void
 */
function excerpt($archive){
	echo preg_replace('#<!--more-->[\s\S]*#','......',$archive->content);
}

/**
 * 调试输出
 * 
 * @param mixed
 * @return void
 */
function write() {
	$code = '';
	foreach(func_get_args() as $val)
		$code .= print_r($val,true)."\n";
	$code = htmlspecialchars($code);
	echo "<pre>$code</pre>";
}

/**
 * 创建一个匿名函数
 * 使用my_create_function 替代 my_create_function 解决 7.20 以上报错问题
 * @param string
 * @param string
 * @return function
 */
function my_create_function($param,$function) {
	return eval("return function({$param}){{$function}};");
}

/**
 * 判断是否为MD5
 *
 * @param string
 * @return function
 */
function is_md5($md5) {
	return preg_match("/^[a-z0-9]{32}$/", $md5);
}

/**
 * 是否存在于数组
 * 
 * @param mixed
 * @param array
 * @return bool
 */
function inArray($a, $arr) {
	if (empty($arr))
		return false;
	return in_array($a, $arr);
}

/**
 * 获取头像
 *
 * @param string $email
 * @return string
 */
function get_avatar($email) {
    if (preg_match('/^(\d+)@qq\.com$/i', $email, $matches)) {
        return "https://q1.qlogo.cn/g?b=qq&s=100&nk=$matches[1]";
    }
    $r = Helper::options()->commentsAvatarRating;
    $d = Typecho_Widget::widget('Widget_Options')->motx; // 默认头像
    return preg_replace('/\/$/', '', Helper::options()->gravatar) . '/' . md5(strtolower($email)) . "?s=100&r=$r&d=$d";
}