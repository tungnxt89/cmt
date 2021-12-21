<?php
/**
 * Template list users
 */

if ( ! isset( $cmt_db ) || ! isset( $users ) ) {
	return;
}

//$totals = $cmt_db->total_users();

$filter = new Filter_User();
$fields_arr = get_object_vars($filter);
unset($fields_arr['page']);
?>

<table class="wp-list-table widefat fixed striped table-view-list posts">
	<thead>
		<tr>
			<?php
			foreach ( $fields_arr as $key => $value ) {
				echo "<th class={$key}>{$key}</th>";
			}
			?>
		</tr>
	</thead>

	<tbody id="the-list">
	<?php
	foreach ( $users as $user ) {
		if( ! is_object( $user ) ) {
			continue;
		}
		?>
		<tr id="post-<?php echo $user->id; ?>" class="">
			<?php
			foreach ( $fields_arr as $key => $value ) {
				echo "<th class={$key}>{$user->$key}</th>";
			}
			?>
		</tr>
		<?php
	}
	?>
	</tbody>

	<tfoot>
	<tr>
		<?php
		foreach ( $fields_arr as $key => $value ) {
			echo "<th class={$key}>{$key}</th>";
		}
		?>
		
	</tr>
	</tfoot>

</table>
