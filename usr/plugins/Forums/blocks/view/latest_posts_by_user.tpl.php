<table class="forum" >
	<thead>
		<tr>
			<th>Post</th>
			<th>Thread</th>
			<th>Forum</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($posts as $post):?>
		<tr>
			<td>
				<?php echo Html::l('&quot;'.Strings::shorten(Bbcode::remove($post['body']), 100, true).'&quot;', 'Forums', 'showThread', array($post['forum_id'], $post['fk_forum_thread_id'], $post['thread_seo_url'])); ?>
				<div>
					<?php echo $language->youReplied;?> <?php echo date('M d, Y', $post['created']).' '.$language->at.' '.date('H:i', $post['created']); ?>
				</div>
			</td>
			<td>
				<div>
					<?php echo Html::l(Strings::shorten($post['thread_title'], 30, true),  'Forums', 'showThread', array($post['forum_id'], $post['fk_forum_thread_id'], $post['thread_seo_url'])) ; ?>
				</div>
			</td>
			<td><?php echo Html::l($post['forum_name'], 'Forums', 'showForum', array($post['forum_id'])); ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>