<?php
/*
This file must be here for the admin panel
*/
?>
<div class="sweany_admin sweany_admin_users">
	<h1>Users</h1>

	<?php if (count($users)):?>
		<table style="border:1px solid black;margin-left:auto; margin-right:auto;">
			<thead>
				<tr>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Id</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Username</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Email</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Admin</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Enabled</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Deleted</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Locked</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Fake</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Last login</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Created</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Modified</th>
					<th style="border:1px solid black;text-align:center;padding:5px;font-weight:bold;">Deleted</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($users as $user):?>
				<?php

					if ( $user->is_locked ) {
						$color = 'color:red;';
					} else if ( $user->is_deleted ) {
						$color = 'color:gray;';
					} else if ( !$user->is_enabled ) {
						$color = 'color:blue;';
					} else {
						$color = 'color:green;';
					}
				?>
				<tr>
					<td style="border:1px solid black;text-align:center;"><?php echo $user->id;?></td>
					<td style="border:1px solid black;text-align:center;<?php echo $color;?>"><?php echo $user->username;?></td>
					<td style="border:1px solid black;text-align:center;"><?php echo $user->email;?></td>
					<td style="border:1px solid black;text-align:center;"><?php echo $user->is_admin;?></td>
					<td style="border:1px solid black;text-align:center;"><?php echo $user->is_enabled;?></td>
					<td style="border:1px solid black;text-align:center;"><?php echo $user->is_deleted;?></td>
					<td style="border:1px solid black;text-align:center;"><?php echo $user->is_locked;?></td>
					<td style="border:1px solid black;text-align:center;"><?php echo $user->is_fake;?></td>
					<td style="border:1px solid black;text-align:center;padding:5px;"><?php echo $user->last_login	? TimeHelper::date('Y-m-d H:i', $user->last_login)	: '';?></td>
					<td style="border:1px solid black;text-align:center;padding:5px;"><?php echo $user->created		? TimeHelper::date('Y-m-d H:i', $user->created)		: '';?></td>
					<td style="border:1px solid black;text-align:center;padding:5px;"><?php echo $user->modified	? TimeHelper::date('Y-m-d H:i', $user->modified)	: '';?></td>
					<td style="border:1px solid black;text-align:center;padding:5px;"><?php echo $user->deleted		? TimeHelper::date('Y-m-d H:i', $user->deleted)		: '';?></td>
					</tr>
			<?php endforeach;?>
			</tbody>
		</table>
	<?php endif;?>
	<br/>
</div>