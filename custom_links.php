<?php
/**
 * 友链
 *
 * @package custom
 */
?>
<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
$this->need('header.php');
class Custom_Links
{
    private $count;
    private $lazyLoad;
    private $srcSelector;
    private $gravatar;

    public function __construct($content)
    {
        require_once 'assets/php/phpQuery.php';
        $options = Helper::options();
        $this->gravatar = preg_replace('/\/$/','',$options->gravatar).'/';
        $this->lazyLoad = in_array('lazyload', $options->advanced) ? getLazyLoadImg() : false;
        $this->srcSelector = $this->lazyLoad ? 'data-original' : 'img';
        $pq = phpQuery::newDocumentHTML($content);
        $pq->find('ul')->each(array($this, 'ul'));
        echo $pq->html();
    }

    public function ul($dom)
    {
        $pq = pq($dom);
        $this->count = null;
        $pq->children()->each(array($this, 'li'));
        if ($this->count > 0) {
            $pq->addClass('links-group');
        }
    }

    public function li($dom)
    {
        if ($this->count === null) {
            $this->count = 0;
        } elseif ($this->count === 0) {
            return;
        }
        $pq = pq($dom);
        $children = $pq->children();
        $length = $children->length;
        if ($length != 2) {
            return;
        }
        $a = pq($children->get(0));
        if ($a->length != 1) {
            return;
        }
        $img = $a->is('img') ? $a : $a->find('img');
        if ($img->length != 1) {
            return;
        }
        $src = $img->attr($this->srcSelector);
        $desc = $img->attr('alt');
        $a = pq($children->get(1));
        if ($a->length != 1) {
            return;
        }
        $name = $a->text();
        $url = $a->attr('href');
        ++$this->count;
        if (!empty($desc)) {
            $desc = "title=\"$desc\"";
        }
        if (!filter_var($src,FILTER_VALIDATE_URL)) {
            if (filter_var($src,FILTER_VALIDATE_EMAIL))
                $src = $this->gravatar . md5(strtolower($src)) . '?s=100';
            else if (is_md5($src) || empty($src))
                $src = $this->gravatar . strtolower($src) . '?s=100';
            else if (preg_match('/^\d{5,}$/', $src) !== false)
                $src = "https://q.qlogo.cn/g?b=qq&nk=$src&s=100";
        }
        if ($this->lazyLoad) {
            $src = "src=\"$this->lazyLoad\" data-original=\"$src\"";
        } else {
            $src = "src=\"$src\"";
        }
        $html = <<<HTML
<a target="_blank" href="$url" $desc><div class="links-body"><img $src alt="$name"><p>$name</p></div></a>
HTML;
        $pq->wrap($html);
    }
}
?>
<div class="col-mb-12 col-8" id="main" role="main">
    <article class="post" itemscope itemtype="http://schema.org/BlogPosting">
        <h1 class="post-title" itemprop="name headline"><a itemprop="url" href="<?php $this->permalink() ?>"><?php $this->title() ?></a></h1>
        <div class="post-content" itemprop="articleBody">
			<div class="links">
<?php new Custom_Links($this->content); ?>
			</div>
        </div>
    </article>
    <?php $this->need('comments.php'); ?>
</div><!-- end #main-->

<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>
