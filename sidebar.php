<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!-- mobile menu #begin-->
<button id="mobileMenuButton">菜单</button>
<div id="parclose"></div>
<div class="col-mb-12 col-offset-1 col-3 kit-hidden-tb" id="secondary" role="complementary">
	<form id="search" method="post" role="search">
		<label for="s" class="sr-only"><?php _e('搜索关键字'); ?></label>
		<input type="text" id="s" name="s" class="text" placeholder="<?php _e('输入关键字搜索'); ?>" <?php
			$searchText = getSearchText();
			if($searchText !== '')echo 'value="'.$searchText.'"';
		?> />
		<button type="submit" class="submit"><?php _e('搜索'); ?></button>
	</form>
	
	<?php if(inArray('ShowRecentPosts', $this->options->sidebarBlock)): ?>
	<section class="widget">
		<h3 class="widget-title"><?php _e('最新文章'); ?></h3>
		<ul class="widget-list">
			<?php $this->widget('Widget_Contents_Post_Recent')
			->parse('<li><a href="{permalink}">{title}</a></li>'); ?>
		</ul>
	</section>
	<?php endif; ?>

	<?php if(inArray('ShowRecentComments', $this->options->sidebarBlock)): ?>
	<section class="widget">
		<h3 class="widget-title"><?php _e('最近回复'); ?></h3>
		<ul class="widget-list">
		<?php $this->widget('Widget_Comments_Recent')->to($comments); ?>
		<?php while($comments->next()): ?>
			<li><a href="<?php $comments->permalink(); ?>"><?php $comments->author(false); ?></a>: <?php $comments->excerpt(35, '...'); ?></li>
		<?php endwhile; ?>
		</ul>
	</section>
	<?php endif; ?>

	<?php if(inArray('ShowCategory', $this->options->sidebarBlock)): ?>
	<section class="widget">
		<h3 class="widget-title"><?php _e('分类'); ?></h3>
		<?php $this->widget('Widget_Metas_Category_List')->listCategories('wrapClass=widget-list'); ?>
	</section>
	<?php endif; ?>

	<?php if(inArray('ShowArchive', $this->options->sidebarBlock)): ?>
	<section class="widget">
		<h3 class="widget-title"><?php _e('归档'); ?></h3>
		<ul class="widget-list">
			<?php $this->widget('Widget_Contents_Post_Date', 'type=month&format=Y年n月')->parse('<li><a href="{permalink}">{date}</a></li>'); ?>
		</ul>
	</section>
	<?php endif; ?>

	<?php if(inArray('ShowOther', $this->options->sidebarBlock)): ?>
	<section class="widget">
		<h3 class="widget-title"><?php _e('其它'); ?></h3>
		<ul class="widget-list">
			<?php if($this->user->hasLogin()): ?>
				<li class="last"><a href="<?php $this->options->adminUrl(); ?>" target="_blank"><?php _e('进入后台'); ?> (<?php $this->user->screenName(); ?>)</a></li>
				<li><a href="<?php $this->options->logoutUrl(); ?>"><?php _e('退出'); ?></a></li>
			<?php else: ?>
			<li class="last"><a href="<?php $this->options->adminUrl('login.php'); ?>" target="_blank"><?php _e('登录'); ?></a></li>
			<?php endif; ?>
			<li><a href="<?php $this->options->feedUrl(); ?>" target="_blank"><?php _e('文章 RSS'); ?></a></li>
			<li><a href="<?php $this->options->commentsFeedUrl(); ?>" target="_blank"><?php _e('评论 RSS'); ?></a></li>
			<?php if(inArray('Console', $this->options->sidebarBlock)): ?><li><a href="javascript:void(0);" onclick="main.console();">控制台</a></li><?php endif; ?>
		</ul>
	</section>
	<?php endif; ?>

</div><!-- end #sidebar -->
