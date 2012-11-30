<?php
/*
This file must be here for the admin panel
*/
?>
<div class="sweany_admin sweany_admin_translations">
	<h1>Translations</h1>
	<?php if (count($langs)):?>
		<?php echo Form::start('translations'); ?>
			<table style="border:1px solid black;margin-left:auto; margin-right:auto;">
				<thead>
					<tr>
						<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Default (<?php echo $default;?>)</th>
						<?php foreach ($trans as $name):?>
						<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;"><?php echo $name; ?></th>
						<?php endforeach; ?>
						<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Todo</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($langs as $lang):?>
					<tr>
						<td style="border:1px solid black;"><?php echo $lang->text;?></td>
						<?php $todo = false; ?>
						<?php foreach ($trans as $tran => $name): ?>
							<?php if (!isset($lang->translation->$tran)) {$todo=true;} ?>
							<td style="border:1px solid black;">
								<?php $def_value =  isset($lang->translation->$tran) ?$lang->translation->$tran : ''; ?>
								<?php echo Form::inputField('[group]['.$lang->group.']['.$tran.']', $def_value); ?>
							</td>
						<?php endforeach; ?>
						<td style="border:1px solid black;color:red;"><?php echo $todo ? 'Todo' : '';?></td>
					</tr>
				<?php endforeach;?>
				</tbody>
			</table><br/>
			<?php echo Form::submitButton('save', 'Save'); ?>
		<?php echo Form::end(); ?>
	<?php endif;?>
</div>