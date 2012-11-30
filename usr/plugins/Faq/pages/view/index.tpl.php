<div class="plugin_faq">
	<?php /*** Questions ***/ ?>
	<div class="plugin_faq_questions">
		<?php foreach ($entries as $entry): ?>
			<h2><?php echo t($entry->name); ?></h2>
			<?php foreach ($entry->Faq as $faq): ?>
				<?php echo Html::l(t($faq->question), 'Faq', null, null, null, $faq->anchor);?><br/>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>
	<?php /*** Answers ***/ ?>
	<div class="plugin_faq_answers">
		<?php foreach ($entries as $entry): ?>
			<h2><?php echo t($entry->name); ?></h2>
			<?php foreach ($entry->Faq as $faq): ?>
				<a id="<?php echo $faq->anchor;?>">F: <?php echo t($faq->question);?></a>
				<p>A: <?php echo t($faq->answer);?></p>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>
</div>