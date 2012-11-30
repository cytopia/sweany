<?php foreach ($threads as $thread): ?>
	<div class="form_news_row">
		<div class="forum_news_title">
			<?php echo Html::l($thread['title'], 'Forums', 'showThread', array($thread['fk_forum_forums_id'], $thread['id'], $thread['seo_url'])); ?>
		</div>
		<div class="forum_news_body">
			<?php echo Strings::shorten(Bbcode::remove($thread['body']),200, true); ?>
			<?php echo Html::l('mehr', 'Forums', 'showThread', array($thread['fk_forum_forums_id'], $thread['id'], $thread['seo_url'])); ?>
		</div>
	</div>
<?php endforeach;?>