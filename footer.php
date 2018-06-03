<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

				</div><!-- end .row -->
			</div>
			<script class="tmp">
				main.loadtime = <?php timer_stop(true,4); ?>
			</script>
		</div><!-- end #body -->

		<footer id="footer" role="contentinfo">
			<?php if (inArray('hitokoto', $this->options->advanced)): ?>
			<div><a id="hitokoto" rel="nofollow" target="_blank"></a></div>
			<?php endif; ?>
			<?php
				$footer = !empty($this->options->footer)?$this->options->footer:NULL;
				if ($footer !== NULL) {
					$search = ['{Y}','{siteUrl}','{title}','{inf}'];
					$replace = [date('Y'),$this->options->siteUrl,$this->options->title,'Processed in <span id="loadtime">'.timer_stop(false,4).'</span> second(s), Gzip '.(is_Gzip()?'ON':'OFF').', SSL '.(is_SSL()?'ON':'OFF').'.'];
					echo str_replace($search,$replace,$footer);
				}
			?>
		</footer><!-- end #footer -->
		<?php if (inArray('QPlayer', $this->options->advanced)): ?>
		<div id="QPlayer">
			<div class="body">
				<div class="player">
					<span class="cover">
						<img src="https://p4.music.126.net/7VJn16zrictuj5kdfW1qHA==/3264450024433083.jpg?param=106x106">
					</span>
					<div class="ctrl">
						<div class="title">
							<strong>name</strong><span> - </span><span class="artist">artist</span>
						</div>
						<div class="progress">
							<div class="already" style="width:0%;">
								<div class="btn"></div>
							</div>
						</div>
						<div class="contr">
							<div class="timer left">00:00</div>
							<div class="center">
								<div class="last icon"></div>
								<div class="play icon"></div>
								<div class="next icon"></div>
							</div>
							<div class="right">
								<div class="list-btn icon"></div>
								<div class="lyric-btn icon"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="pop-btn">
					<div class="icon"></div>
				</div>
			</div>
			<div class="more">
				<ol class="list"></ol>
				<div class="lyric"><ol></ol></div>
			</div>
			<audio></audio>
		</div>
		<?php endif; ?>
		<?php $this->footer(); ?>
	</body>
</html>
