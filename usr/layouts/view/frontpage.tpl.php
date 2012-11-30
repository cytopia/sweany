<div class="topbar" style="text-align:right;">
	<?php echo Html::getLanguageSwitcher(); ?>
</div>
<div id="whole">
	<div id="wraper">
		<div id="header">
			<div class="topmenu">
				<ul>
					<li onclick="window.location='/'"><a href="/"><?php echo t('Home'); ?></a></li>
					<li onclick="window.location='<?php echo Html::href('Forums');?>'"><a href="<?php echo Html::href('Forums');?>"><?php echo t('Forum'); ?></a></li>
					<li onclick="window.location='<?php echo Html::href('Faq');?>'"><a href="<?php echo Html::href('Faq');?>"><?php echo t('FAQ'); ?></a></li>
					<li onclick="window.location='<?php echo Html::href('Guestbook');?>'"><a href="<?php echo Html::href('Guestbook');?>"><?php echo t('Guestbook'); ?></a></li>
					<li onclick="window.location='<?php echo Html::href('Contact');?>'"><a href="<?php echo Html::href('Contact');?>"><?php echo t('Contact'); ?></a></li>
					<li onclick="window.location='<?php echo Html::href('Test');?>'"><a href="<?php echo Html::href('Test');?>"><?php echo t('Test'); ?></a></li>
				</ul>
			</div>
			<div style="clear:both;"></div>
		</div>
		<div id="content">
			<div style="float:left;">
				<?php echo $view; ?>
			</div>
			<div style="float:right; width:15em;padding-top:64px;">
				<div style="border:solid 1px black; height:180px;">
					<?php echo $bLoginLogoutBox;?><br/>
				</div>
			</div>
			<div style="clear:both;"></div>

		</div>

		<div id="footer">
			Sweany PHP
		</div>

	</div><!--#wraper -->
</div><!--#whole -->
<div style="clear:both;"></div>
