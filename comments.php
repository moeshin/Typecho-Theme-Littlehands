<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
/**
 * 获取浏览器信息
 * 
 * @param string $ua
 * @return array
 */
function getBrowsers($ua){
	$title = '未知';
	$icon = 'unknow'; 
	if (preg_match('#MSIE ([a-zA-Z0-9.]+)#i', $ua, $matches)) {
		$title = 'Internet Explorer ';
		if ( strpos($matches[1], '7') !== false || strpos($matches[1], '8') !== false) $icon = 'ie8';
		elseif ( strpos($matches[1], '9') !== false) $icon = 'ie9';
		elseif ( strpos($matches[1], '10') !== false) $icon = 'ie10';
		else $icon = 'ie';
	}elseif (preg_match('#Edge/([a-zA-Z0-9.]+)#i', $ua, $matches)){
		$title = 'Microsoft Edge ';
		$icon = 'edge';
	}elseif (preg_match('#Firefox/([a-zA-Z0-9.]+)#i', $ua, $matches)){
		$title = 'Firefox ';
		$icon = 'firefox';
	}elseif (preg_match('#CriOS/([a-zA-Z0-9.]+)#i', $ua, $matches)){
		$title = 'Chrome for iOS ';
		$icon = 'crios';
	}elseif (preg_match('#Chrome/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
		$title = 'Chrome ';
		$icon = 'chrome';
		if (preg_match('#OPR/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
			$title = 'Opera ';
			$icon = 'opera15';
			if (preg_match('#opera mini#i', $ua)) $title = 'Opera Mini';
		}
	}elseif (preg_match('#Safari/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
		$title = 'Safari ';
		$icon = 'safari';
	}elseif (preg_match('#Opera.(.*)Version[ /]([a-zA-Z0-9.]+)#i', $ua, $matches)) {
		$title = 'Opera ';
		$icon = 'opera';
		if (preg_match('#opera mini#i', $ua)) $title = 'Opera Mini';   
	}elseif (preg_match('#Maxthon( |\/)([a-zA-Z0-9.]+)#i', $ua,$matches)) {
		$title = 'Maxthon ';
		$icon = 'maxthon';
	}elseif (preg_match('#360([a-zA-Z0-9.]+)#i', $ua, $matches)) {
		$title = '360 Browser ';
		$icon = '360se';
	}elseif (preg_match('#SE 2([a-zA-Z0-9.]+)#i', $ua, $matches)) {
		$title = 'SouGou Browser 2';
		$icon = 'sogou';
	}elseif (preg_match('#UCWEB([a-zA-Z0-9.]+)#i', $ua, $matches)) {
		$title = 'UCWEB ';
		$icon = 'ucweb';
	}elseif(preg_match('#wp-(iphone|android)/([a-zA-Z0-9.]+)#i', $ua, $matches)){
		$title = 'wordpress ';
		$icon = 'wordpress';
	}
	return array($title,$icon);
}
/**
 * 获取操作心痛信息
 * 
 * @param string $ua
 * @return array
 */
function getOS($ua){
	$title = '未知';
	$icon = 'unknow';
	if (preg_match('/win/i', $ua)) {
		if (preg_match('/Windows NT 10.0/i', $ua)) {
			$title = "Windows 10";
			$icon = "windows_win10";
		}elseif (preg_match('/Windows NT 6.1/i', $ua)) {
			$title = "Windows 7";
			$icon = "windows_win7";
		}elseif (preg_match('/Windows NT 5.1/i', $ua)) {
			$title = "Windows XP";
			$icon = "windows";
		}elseif (preg_match('/Windows NT 6.2/i', $ua)) {
			$title = "Windows 8";
			$icon = "windows_win8";
		}elseif (preg_match('/Windows NT 6.3/i', $ua)) {
			$title = "Windows 8.1";
			$icon = "windows_win8";
		}elseif (preg_match('/Windows NT 6.0/i', $ua)) {
			$title = "Windows Vista";
			$icon = "windows_vista";
		}elseif (preg_match('/Windows NT 5.2/i', $ua)) {
			if (preg_match('/Win64/i', $ua)) {
				$title = "Windows XP 64 bit";
			} else {
				$title = "Windows Server 2003";
			}
			$icon = 'windows';
		}elseif (preg_match('/Windows Phone/i', $ua)) {
			$matches = explode(';',$ua);
			$title = $matches[2];
			$icon = "windows_phone";
		}
	}elseif (preg_match('#iPod.*.CPU.([a-zA-Z0-9.( _)]+)#i', $ua, $matches)) {
		$title = "iPod ";
		$icon = "iphone";
	} elseif (preg_match('#iPhone OS ([a-zA-Z0-9.( _)]+)#i', $ua, $matches)) {
		$title = "Iphone ";
		$icon = "iphone";
	} elseif (preg_match('#iPad.*.CPU.([a-zA-Z0-9.( _)]+)#i', $ua, $matches)) {
		$title = "iPad ";
		$icon = "ipad";
	} elseif (preg_match('/Mac OS X.([0-9. _]+)/i', $ua, $matches)) {
		if(count(explode(7,$matches[1]))>1) $matches[1] = 'Lion ';
		elseif(count(explode(8,$matches[1]))>1) $matches[1] = 'Mountain Lion ';
		$title = "Mac OSX ";
		$icon = "macos";
	} elseif (preg_match('/Macintosh/i', $ua)) {
		$title = "Mac OS";
		$icon = "macos";
	} elseif (preg_match('/CrOS/i', $ua)){
		$title = "Google Chrome OS";
		$icon = "chrome";
	}elseif (preg_match('/Linux/i', $ua)) {
		$title = 'Linux';
		$icon = 'linux';
		if (preg_match('/Android.([0-9. _]+)/i',$ua, $matches)) {
			$title= $matches[0];
			$icon = "android";
		}elseif (preg_match('#Ubuntu#i', $ua)) {
			$title = "Ubuntu Linux";
			$icon = "ubuntu";
		}elseif(preg_match('#Debian#i', $ua)) {
			$title = "Debian GNU/Linux";
			$icon = "debian";
		}elseif (preg_match('#Fedora#i', $ua)) {
			$title = "Fedora Linux";
			$icon = "fedora";
		}
	}
	return array($title,$icon);
}
function getUA($ua){
	$imgurl = preg_replace('/\/$/','',Helper::options()->themeUrl).'/assets/img/ua/';
	$browser = getBrowsers($ua);
	$os = getOS($ua);
return '<span class="useragent"><img src="'.$imgurl.$browser[1].'.png"> '.$browser[0].'  <img src="'.$imgurl.$os[1].'.png"> '.$os[0].'</span>';
}

global $atUserList;
$atUserList = array();
/**
 * 评论列表重写
 */
function threadedComments($comments, $options) {
    global $atUserList;
	$commentClass = '';
	if ($comments->authorId) {
		if ($comments->authorId == $comments->ownerId) {
			$commentClass .= ' comment-by-author';
			$idcard = '博主';
		} else {
			$commentClass .= ' comment-by-user';
			$idcard = '用户';
		}
	}else{
		$commentClass .= ' comment-by-visitor';
		$idcard = '访客';
	} ?>
<li itemscope itemtype="http://schema.org/UserComments" id="li-<?php $comments->theId(); ?>" class="comment-body<?php
if ($comments->levels > 0) {
	echo ' comment-child';
	$comments->levelsAlt(' comment-level-odd', ' comment-level-even');
} else {
	echo ' comment-parent';
}
$comments->alt(' comment-odd', ' comment-even');
echo $commentClass;
?>">
	<div id="<?php $comments->theId(); ?>">
		<div itemprop="creator" itemscope itemtype="http://schema.org/Person" class="comment-author">
			<img class="avatar" src="<?php echo get_avatar($comments->mail); ?>" alt="<?php echo $comments->author; ?>" width="40" height="40"/>
			<cite class="fn" itemprop="name"><?php
                if (empty($comments->url)) {
                    echo $comments->author;
                } else {
                    echo <<<HTML
 <a href="$comments->url" target="_blank" rel="external nofollow">$comments->author</a>
HTML;
                } ?></cite>
			<span class="idcard"><?php echo $idcard; ?></span>
			<?php echo getUA($comments->agent); ?>
			<span class="comment-reply"><?php $comments->reply(); ?></span>
			<?php if($comments->status == 'waiting'): ?>
			<span class="waiting">待审核</span>
			<?php endif; ?>
		</div>
		<div class="comment-meta">
			<time href="<?php $comments->permalink(); ?>"><?php $comments->date('Y年m月d日 H:i:s'); ?></time>
		</div><?php
		$parent = $comments->parent;
		if ($parent) {
		    $parent = $atUserList[$parent];
		    if (!empty($parent)) {
                echo $parent;
		    }
		}
		$comments->content(); ?>
    </div><?php
    if ($comments->children) {
        $atUserList[$comments->coid] = <<<HTML
<p>回复 <a class="at-user" href="{$comments->permalink}">@$comments->author</a>：</p>
HTML;
        if ($comments->levels == 0) { ?>
    <div class="comment-children">
    <?php $comments->threadedComments($options); ?>
    </div>
</li><?php
        } else {
            echo '</li>';
            $comments->threadedComments($options);
        }
    } else {
        echo '</li>';
    }
}
?>

<div id="comments">
    <?php $this->comments()->to($comments); ?>
    <?php if ($comments->have()): ?>
	<h3><?php $this->commentsNum(_t('暂无评论'), _t('仅有一条评论'), _t('已有 %d 条评论')); ?></h3>
	<?php $comments->listComments(); ?>
    <?php $comments->pageNav('&laquo; 前一页', '后一页 &raquo;'); ?>
    
    <?php endif; ?>

    <?php if($this->allow('comment')): ?>
    <div id="<?php $this->respondId(); ?>" class="respond">
        <div class="cancel-comment-reply">
        <?php $comments->cancelReply(); ?>
        </div>
    	<h3 id="response"><?php _e('添加新评论'); ?></h3>
    	<form method="post" action="<?php $this->commentUrl() ?>" id="comment-form" role="form">
			<div class="note"><strong>注意：</strong>已开启评论过滤器，<span>无中文</span>将<span>无法</span>评论！</div>
			<?php if (!empty($this->options->advanced) && in_array('emoji', $this->options->advanced) && !empty($this->options->emoji)): ?>
			<div id="comment-more" unselectable="on"><?php
				echo str_replace(
				    array(
				        '{themeUrl}',
                        '{siteUrl}',
                        'data-src'
                    ),
                    array(
                        preg_replace('/\/$/i','',$this->options->themeUrl),
                        preg_replace('/\/$/i','',$this->options->siteUrl),
                        'src'
                    ),
                    $this->options->emoji);
			?></div>
			<style>#textarea{border-radius: 0 0 10px 10px !important;}</style>
			<?php endif; ?>
			<div contenteditable="true" placeholder="在这里输入你的评论..." name="text" id="textarea" class="textarea" data-equired><?php $this->remember('text'); ?></div>
			<?php if($this->user->hasLogin()): ?>
    		<p><?php _e('登录身份: '); ?><a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>. <a href="<?php $this->options->logoutUrl(); ?>" title="Logout"><?php _e('退出'); ?> &raquo;</a></p>
            <?php else: ?>
			<input type="text" name="author" id="author" class="text" value="<?php $this->remember('author'); ?>" placeholder="昵称(必填)" data-equired />
			<input type="email" name="mail" id="mail" class="text" value="<?php $this->remember('mail'); ?>" placeholder="邮箱<?php if($this->options->commentsRequireMail){ ?>(必填)" data-equired <?php } else echo '(选填)"'; ?>/>
			<input type="url" name="url" id="url" class="text" value="<?php $this->remember('url'); ?>" placeholder="网站<?php if($this->options->commentsRequireUrl){ ?>(必填)" data-equired <?php } else echo '(选填)"'; ?>/>
			<?php endif; ?>
            <button type="submit" class="submit"><?php _e('提交评论'); ?></button>
    	</form>
    </div>
    <?php else: ?>
    <h3><?php _e('评论已关闭'); ?></h3>
	<style>#comments{display: none;}</style>
    <?php endif; ?>
</div>
