<div class="topbar" style="text-align:right;">
	<?php echo Html::getLanguageSwitcher('Site', 'lang'); ?>
</div>

<div id="whole">
	<div id="wraper">
		<div id="header">
			<div class="topmenu">
				<ul>
					<li onclick="window.location='/'"><a href="/"><?php echo $language->home; ?></a></li>
					<li onclick="window.location='/Forums'"><a href="/Forums"><?php echo $language->forums; ?></a></li>
					<li onclick="window.location='/Contact'"><a href="/Contact"><?php echo $language->contact; ?></a></li>
					<li onclick="window.location='/Test'"><a href="/Test"><?php echo $language->test; ?></a></li>
				</ul>
			</div>
			<div style="clear:both;"></div>
		</div>
		<div id="content">
			<?php
				//TODO: this is only a temporary table layout!!!
			?>
			<div style="float:left;">
				<?php echo $view; ?>
			</div>
			<div style="float:right; width:15em;padding-top:64px;">
				<div style="border:solid 1px black; height:180px;">
					<?php /* TODO: need User helper, we do not want to access core Classes!!! */?>
					<?php if ( \Core\Init\CoreUsers::isLoggedIn() ): ?>
						<?php echo $blocks['logoutBox'];?><br/>
					<?php else: ?>
						<?php echo $blocks['loginBox']; ?>
					<?php endif; ?>
				</div>
			</div>
			<div style="clear:both;"></div>

		</div>

		<div id="footer">
			<?php echo $language->copyright; ?>
		</div>

	</div><!--#wraper -->
</div><!--#whole -->
<div style="clear:both;"></div>
