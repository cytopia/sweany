<?php
/*
 *	Used for redirect messages
 */
?>
<div class="sweany_info_message">
	<h1><?php echo $language->title; ?></h1>

	<h2><?php echo $title; ?></h2>
	<p>
		<?php echo $body; ?><br/><br/>
		<a title="<?php echo $title; ?>" href="<?php echo $url;?>"><?php echo $language->text; ?></a>
	</p>
</div>