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
		<?php $this->footer(); ?>
	</body>
</html>
