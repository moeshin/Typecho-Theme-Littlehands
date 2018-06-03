<?php
/**
 * 归档
 *
 * @package custom
 */
?>
<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<div class="col-mb-12 col-8" id="main" role="main">
    <article class="post" itemscope itemtype="http://schema.org/BlogPosting">
        <h1 class="post-title" itemprop="name headline"><a itemprop="url" href="<?php $this->permalink() ?>"><?php $this->title() ?></a></h1>
        <div class="post-content" itemprop="articleBody">
			<div id="archives">
				<?php 
					$this->widget('Widget_Contents_Post_Recent', 'pageSize=10000')->to($archives);
					$year=0; $mon=0;
					$output = '';
					$index = preg_replace('/\/$/','',$this->options->index).'/';
					while($archives->next()){
						$year_tmp = date('Y',$archives->created);
						$mon_tmp = date('m',$archives->created);
						$y=$year; $m=$mon;
						if ($mon != $mon_tmp && $mon > 0) $output .= '</ul>';
						if ($year != $year_tmp && $year > 0) $output .= '</div>';
						if ($year != $year_tmp) {
							$year = $year_tmp;
							$output .= '<div class="archives"><h2><a href="'.$index.$year.'">'.$year.' 年</a></h2>';
						}
						if ($mon != $mon_tmp) {
							$mon = $mon_tmp;
							$output .= '<ul><h3><a href="'.$index.$year.'/'.$mon.'">'.$mon.' 月</a></h3>';
						}
						$day = date('d',$archives->created);
						$output .= '<li><a href="'.$index.$year.'/'.$mon.'/'.$day.'">'.$day.'日</a>: <a href="'.$archives->permalink.'">'.
						$archives->title.'</a> <a href="'.$archives->permalink.'#comments">('.$archives->commentsNum.')</a></li>';
					}
					$output .= '</ul>';
					echo $output;
				?>
			</div>
        </div>
    </article>
    <?php $this->need('comments.php'); ?>
</div><!-- end #main-->

<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>