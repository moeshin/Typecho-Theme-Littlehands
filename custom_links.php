<?php
/**
 * 友链
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
			<div class="links">
				<?php
				echo preg_replace_callback('#<li>\s<a [\s\S]*?>(<img [\s\S]*?data-original=")(.*?)("[\s\S]*?>)</a>\s*(<a [\s\S]*?)>([\s\S]*?)</a>\s</li>#',function($matches){
					$src = $matches[2];
					if(!filter_var($src,FILTER_VALIDATE_URL)){
						if(filter_var($src,FILTER_VALIDATE_EMAIL))
							$src = preg_replace('/\/$/','',$this->options->gravatar).'/'.md5(strtolower($src)).'?s=100';
						else if(is_md5($src))
							$src = preg_replace('/\/$/','',$this->options->gravatar).'/'.strtolower($src).'?s=100';
						else
							$src = "https://q.qlogo.cn/g?b=qq&nk=$src&s=100";
					}
					$title = '';
					$img = preg_replace_callback('# alt="([\s\S]*?)"#', function ($matches) use (&$title) {
                        $title = $matches[1];
					    return '';
					},$matches[1].$src.$matches[3]);
					if (!empty($title)) {
					    $title = "title=\"$title\"";
                    }
					return $matches[4].$title.'><div class="links-body">'.$img.'<p>'.$matches[5].'</p></div></a>';
				},$this->content);
				?>
			</div>
        </div>
    </article>
    <?php $this->need('comments.php'); ?>
</div><!-- end #main-->

<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>