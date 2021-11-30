<?php
/**
 * 这是一套根据 Typecho 0.9 系统的默认皮肤修改的主题
 * 
 * @package Littlehands
 * @author MoeShin
 * @version 1.0.2
 * @link https://moeshin.com/
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
 ?>
<div class="col-mb-12 col-8" id="main" role="main">
	<?php
	/** 文章置顶 */
	$sticky = $this->options->sticky;
	if($sticky){
		$sticky_cids = explode('|',$sticky);
		$sticky_html = "<span style='color:red'>[置顶] </span>";
		
		$db = Typecho_Db::get();
		$pageSize = $this->options->pageSize;
		$select1 = $this->select()->where('type = ?', 'post');
		$select2 = $this->select()->where('type = ? && status = ? && created < ?', 'post','publish',time());
		
		$this->row = [];
		$this->stack = [];
		$this->length = 0;
		
		$order = '';
		foreach($sticky_cids as $i => $cid) {
			if($i == 0) $select1->where('cid = ?', $cid);
			else $select1->orWhere('cid = ?', $cid);
			$order .= " when $cid then $i";
			$select2->where('table.contents.cid != ?', $cid);
		}
		if ($order) $select1->order('', "(case cid$order end)");
		if (($this->_currentPage || $this->currentPage) == 1) foreach($db->fetchAll($select1) as $sticky_post){
			$sticky_post['sticky'] = $sticky_html;
			$this->push($sticky_post);
		}
		if($this->user->hasLogin()){
            $uid = $this->user->uid;
            if($uid) $select2->orWhere('authorId = ? && status = ?', $uid, 'private');
		}
		$sticky_posts = $db->fetchAll($select2->order('table.contents.created', Typecho_Db::SORT_DESC)->page($this->_currentPage, $this->parameter->pageSize));
		foreach($sticky_posts as $sticky_post) $this->push($sticky_post);
		$this->setTotal($this->getTotal()-count($sticky_cids));
    }
	while($this->next()): ?>
		<article class="post" itemscope itemtype="http://schema.org/BlogPosting">
			<h1 class="post-title" itemprop="name headline"><a itemprop="url" href="<?php $this->permalink() ?>"><?php $this->sticky(); $this->title() ?></a></h1>
			<ul class="post-meta">
				<li itemprop="author" itemscope itemtype="http://schema.org/Person"><?php _e('作者: '); ?><a itemprop="name" href="<?php $this->author->permalink(); ?>" rel="author"><?php $this->author(); ?></a></li>
				<li><?php _e('时间: '); ?><time datetime="<?php $this->date('c'); ?>" itemprop="datePublished"><?php $this->date(); ?></time></li>
				<li><?php _e('分类: '); ?><?php $this->category(','); ?></li>
				<li itemprop="interactionCount"><a itemprop="discussionUrl" href="<?php $this->permalink() ?>#comments"><?php $this->commentsNum('评论', '1 条评论', '%d 条评论'); ?></a></li>
			</ul>
			<div class="post-content" itemprop="articleBody">
				<?php excerpt($this); ?>
				<p><a href="<?php $this->permalink() ?>">阅读全文</a></p>
			</div>
		</article>
	<?php endwhile; ?>

	<?php $this->pageNav('&laquo; 前一页', '后一页 &raquo;'); ?>
</div><!-- end #main-->

<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>
