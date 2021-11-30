<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
	switch(http_response_code()){
		case 403: $status = '403 Forbidden'; break;
		case 404: $status = '404 Not Found'; break;
		default: $status = 'OBJK';
	}
	header("HTTP/1.0 233 $status");
?>
<!DOCTYPE HTML>
<html class="no-js">
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php $this->archiveTitle(array(
            'category'  =>  _t('分类 %s 下的文章'),
            'search'    =>  _t('包含关键字 %s 的文章'),
            'tag'       =>  _t('标签 %s 下的文章'),
            'author'    =>  _t('%s 发布的文章')
        ), '', ' - '); ?><?php $this->options->title(); ?></title>

	<!-- 使用url函数转换相关路径 -->
	<link href="<?php echo cdn('normalize/7.0.0/normalize.min.css'); ?>" rel="stylesheet">
	<link rel="stylesheet" href="<?php $this->options->themeUrl('grid.css'); ?>">
	<link rel="stylesheet" href="<?php $this->options->themeUrl('style.css'); ?>">
	
	<!-- 背景图片 -->
	<style>body:before{background-image:url(<?php $this->options->backgroundUrl() ?>);}</style>
	<!-- 网站ICO -->
	<link rel="shortcut icon" href="<?php $this->options->faviconUrl() ?>"/>
	<link rel="bookmark" href="<?php $this->options->faviconUrl() ?>"/>
	
	<!-- JS and CSS	-->
	<!--[if lt IE 9]>
	<div class="browsehappy" role="dialog"><?php _e('当前网页 <strong>不支持</strong> 你正在使用的浏览器. 为了正常的访问, 请 <a href="http://browsehappy.com/">升级你的浏览器</a>'); ?>.</div>
	<script src="<?php echo cdn('jquery/1.8.0/jquery.min.js'); ?>"></script>
	<script src="<?php echo cdn('html5shiv/r29/html5.min.js'); ?>"></script>
	<script src="<?php echo cdn('respond.js/1.4.2/respond.min.js'); ?>"></script>
	<style>body{background-image:url(<?php $this->options->backgroundUrl() ?>);}body:before{content:none;}</style>
	<![endif]-->
	<script class='tmp'>
		window.indexUrl = '<?php $this->options->index(); ?>';
		window.siteUrl = '<?php $this->options->siteUrl(); ?>';
		window.themeUrl = '<?php $this->options->themeUrl(); ?>';
		window.baiduID = '<?php $this->options->baidu(); ?>';
	</script>
	<script src="<?php echo cdn('jquery/3.2.1/jquery.min.js'); ?>"></script>
	<script src="<?php echo cdn('jquery_lazyload/1.9.7/jquery.lazyload.min.js'); ?>"></script>
	<script src="<?php echo cdn('jQuery.Marquee/1.5.0/jquery.marquee.min.js'); ?>"></script>
	<script src="<?php $this->options->themeUrl('main.js'); ?>"></script>
	<script src="<?php echo cdn('sweetalert/1.1.3/sweetalert.min.js'); ?>"></script>
	<script src="<?php echo cdn('lightgallery/1.10.0/js/lightgallery.min.js'); ?>"></script>
	<script src="<?php echo cdn('lg-fullscreen/1.2.1/lg-fullscreen.min.js'); ?>"></script>
	<script src="<?php echo cdn('lg-zoom/1.3.0/lg-zoom.min.js'); ?>"></script>
	<script src="<?php echo cdn('clipboard.js/1.7.1/clipboard.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-core.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-markup.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-css.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-clike.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-javascript.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-apacheconf.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-bash.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-c.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-cpp.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-csharp.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-css-extras.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-git.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-http.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-ini.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-java.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-json.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-markdown.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-nginx.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-php.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-php-extras.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-powershell.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-python.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-sql.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/components/prism-vim.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/plugins/line-highlight/prism-line-highlight.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/plugins/line-numbers/prism-line-numbers.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/plugins/autolinker/prism-autolinker.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/plugins/highlight-keywords/prism-highlight-keywords.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/plugins/previewers/prism-previewers.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/plugins/toolbar/prism-toolbar.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/plugins/show-language/prism-show-language.min.js'); ?>"></script>
	<script src="<?php echo cdn('prism/1.10.0/plugins/normalize-whitespace/prism-normalize-whitespace.min.js'); ?>"></script>
  	<link href="<?php echo cdn('sweetalert/1.1.3/sweetalert.min.css'); ?>" rel="stylesheet">
	<link href="<?php echo cdn('lightgallery/1.10.0/css/lightgallery.min.css'); ?>" rel="stylesheet">
	<link href="<?php echo cdn('prism/1.10.0/themes/prism-okaidia.min.css'); ?>" rel="stylesheet">
	<link href="<?php echo cdn('prism/1.10.0/plugins/line-highlight/prism-line-highlight.min.css'); ?>" rel="stylesheet">
	<link href="<?php echo cdn('prism/1.10.0/plugins/line-numbers/prism-line-numbers.min.css'); ?>" rel="stylesheet">
	<link href="<?php echo cdn('prism/1.10.0/plugins/toolbar/prism-toolbar.min.css'); ?>" rel="stylesheet">
	<link href="<?php echo cdn('prism/9000.0.1/plugins/autolinker/prism-autolinker.min.css'); ?>" rel="stylesheet">
	<link href="<?php echo cdn('prism/1.10.0/plugins/previewers/prism-previewers.min.css'); ?>" rel="stylesheet">
	<link rel="stylesheet" href="<?php $this->options->themeUrl('/assets/css/animation.min.css'); ?>">
	<?php if(inArray('pjax', $this->options->advanced)): ?>
	<script src="<?php echo cdn('jquery.pjax/2.0.1/jquery.pjax.min.js'); ?>"></script>
	<link href="<?php echo cdn('nprogress/0.2.0/nprogress.min.css'); ?>" rel="stylesheet">
	<script src="<?php echo cdn('nprogress/0.2.0/nprogress.min.js'); ?>"></script>
	<script src="<?php $this->options->themeUrl('pjax.js?'); ?>"></script>
	<?php endif; ?>
	<?php if(inArray('Console', $this->options->advanced)): ?>
	<script>main.console();</script>
	<?php endif; ?>
	
	<?php $this->options->header(); ?>
    <!-- 通过自有函数输出HTML头部信息 -->
    <?php $this->header('commentReply='); ?>
</head>
<body>
<?php
//dump_cdn();
?>
<header id="header" class="clearfix">
    <div class="container">
        <div class="row">
            <div class="site-name col-mb-12 col-9">
				<?php if ($this->options->logoUrl): ?>
				<div id="logo-img">
					<a href="<?php $this->options->siteUrl(); ?>">
						<img src="<?php $this->options->logoUrl() ?>" alt="<?php $this->options->title() ?>" />
					</a>
				</div>
				<?php endif; ?>
				<div id="logo-text">
					<a href="<?php $this->options->siteUrl(); ?>">
						<?php $this->options->title() ?>
					</a>
				</div>
			</div>
        </div><!-- end .row -->
    </div>
</header><!-- end #header -->
<div id="body">
	<div class="container">
		<div class="row">
			<div class="col-mb-12">
				<nav id="nav-menu" class="clearfix" role="navigation">
					<a<?php if($this->is('index')): ?> class="current"<?php endif; ?> href="<?php $this->options->siteUrl(); ?>"><?php _e('首页'); ?></a>
					<?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
					<?php while($pages->next()): ?>
					<a<?php if($this->is('page', $pages->slug)): ?> class="current"<?php endif; ?> href="<?php $pages->permalink(); ?>" title="<?php $pages->title(); ?>"><?php $pages->title(); ?></a>
					<?php endwhile; ?>
				</nav>
			</div>
