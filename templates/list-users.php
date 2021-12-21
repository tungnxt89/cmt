<?php
/**
 * Template list users
 */

if ( ! isset( $cmt_db ) || ! isset( $users ) ) {
	return;
}

$totals = $cmt_db->total_users();
?>

<table class="wp-list-table widefat fixed striped table-view-list posts">
	<thead>
	<tr>
		<td id="cb" class="manage-column column-cb check-column">
			<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
			<input id="cb-select-all-1" type="checkbox">
		</td>
		<th scope="col" id="title" class="manage-column column-author sortable desc">
            <span>Box ID</span>
            <span class="sorting-indicator"></span>
		</th>
		<th scope="col" id="title" class="manage-column column-author sortable desc">
            <span>STT trong box</span>
            <span class="sorting-indicator"></span>
		</th>
		<th scope="col" id="author" class="manage-column column-author">Mã danh sách đề nghị</th>
		<th scope="col" id="author" class="manage-column column-author sorted desc">Ngay Khai
			<span class="sorting-indicator"></span>
		</th>
		<th scope="col" id="author" class="" width="120">Noi tra
			<span class="sorting-indicator"></span>
		</th>
		<th scope="col" id="author" class="" width="100">Lay ho
			<span class="sorting-indicator"></span>
		</th>
		<th scope="col" id="categories" class="manage-column column-categories">Mã tờ khai</th>
		<th scope="col" id="tags" class="manage-column column-tags">Tên</th>
		<th scope="col" id="comments" class="manage-column column-comments num sortable desc">Số CCCD</th>
		<th scope="col" id="date" class="manage-column column-date sortable asc">Ngày sinh</th>
		<th scope="col" id="date" class="manage-column column-date sortable asc">Giới tính</th>
		<th scope="col" id="date" class="manage-column column-date sortable asc">Địa chỉ</th>
		<th scope="col" id="date" class="manage-column column-date sortable asc">Địa chỉ đầy đủ</th>
		<th scope="col" id="date" class="manage-column column-date sortable asc">
			Đã chia the <input name='full_returnx' type="checkbox">
		</th>
		<th scope="col" id="date" class="manage-column column-date sortable asc">Ghi chu khi da tra the</th>
	</tr>
	</thead>

	<tbody id="the-list">
	<?php
	foreach ( $users as $user ) {
		if( ! is_object( $user ) ) {
			continue;
		}
		?>
		<tr id="post-<?php echo $user->id; ?>"
			class="iedit author-self level-0 post-1 type-post status-publish format-standard hentry category-uncategorized entry">
			<th scope="row" class="check-column">
				<input id="cb-select-1" type="checkbox" name="post[]" value="<?php echo $user->id; ?>">
			</th>
			<td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
				<div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
				<strong>
					<?php echo $user->box_id; ?>
				</strong>
			</td>
			<td class="" data-colname="stt-box">
				<strong>
					<?php echo $user->stt_barcode; ?>
				</strong>
			</td>
			<td class="author column-author" data-colname="Author">
				<?php echo $user->ma_dsdn; ?>
			</td>
			<td class="author column-author" data-colname="Author">
				<?php echo $user->ngay_khai; ?>
			</td>
			<td class="author column-author">
				<input type="text" name="noi_tra" value="<?php echo $user->noi_tra ?? ''; ?>" style="width: 100%">
			</td>
			<td class="author column-author">
				<input type="text" name="lay_ho" value="<?php echo $user->lay_ho ?? ''; ?>" style="width: 100%">
			</td>
			<td class="categories column-author" data-colname="Categories"><?php echo $user->ma_to_khai; ?></td>
			<td class="tags column-tags" data-colname="Tags">
				<span><?php echo $user->name; ?></span>
			</td>
			<td class="comments column-comments" data-colname="Comments">
				<span><?php echo $user->so_cccd; ?></span>
			</td>
			<td class="date column-date" data-colname="Date"><?php echo $user->birthday; ?></td>
			<td class="date column-date" data-colname="Date"><?php echo $user->sex; ?></td>
			<td class="date column-date" data-colname="Date"><?php echo $user->address; ?></td>
			<td class="date column-date" data-colname="Date"><?php echo $user->address_full; ?></td>
			<td class="date column-date" data-colname="Date">
				<?php echo $user->returnx === 1 ? $user->returnx : '<input name="returnx" type="checkbox" value="'.$user->id.'">' ?>
			</td>
			<td class="date column-date" data-colname="Date">
				<textarea name="note" style="width: 100%"><?php echo $user->note; ?></textarea>
			</td>
		</tr>
		<?php
	}
	?>
	</tbody>

	<tfoot>
	<tr>
		<td class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-2">Select
				All</label><input id="cb-select-all-2" type="checkbox"></td>
		<th scope="col" class="manage-column column-title column-primary sortable desc"><a
					href="http://lp3/wp-admin/edit.php?orderby=title&amp;order=asc"><span>Title</span><span
						class="sorting-indicator"></span></a></th>
		<th scope="col" class="manage-column column-author">Author</th>
		<th scope="col" class="manage-column column-categories">Categories</th>
		<th scope="col" class="manage-column column-tags">Tags</th>
		<th scope="col" class="manage-column column-comments num sortable desc"><a
					href="http://lp3/wp-admin/edit.php?orderby=comment_count&amp;order=asc"><span><span
							class="vers comment-grey-bubble" title="Comments"><span
								class="screen-reader-text">Comments</span></span></span><span
						class="sorting-indicator"></span></a></th>
		<th scope="col" class="manage-column column-date sortable asc"><a
					href="http://lp3/wp-admin/edit.php?orderby=date&amp;order=desc"><span>Date</span><span
						class="sorting-indicator"></span></a></th>
		<th scope="col" class="manage-column column-date sortable asc"><a
					href="http://lp3/wp-admin/edit.php?orderby=date&amp;order=desc"><span>Date</span><span
						class="sorting-indicator"></span></a></th>
		<th scope="col" class="manage-column column-date sortable asc"><a
					href="http://lp3/wp-admin/edit.php?orderby=date&amp;order=desc"><span>Date</span><span
						class="sorting-indicator"></span></a></th>
		<th scope="col" class="manage-column column-date sortable asc"><a
					href="http://lp3/wp-admin/edit.php?orderby=date&amp;order=desc"><span>Date</span><span
						class="sorting-indicator"></span></a></th>
		<th scope="col" class="manage-column column-date sortable asc"><a
					href="http://lp3/wp-admin/edit.php?orderby=date&amp;order=desc"><span>Date</span><span
						class="sorting-indicator"></span></a></th>
		<th scope="col" class="manage-column column-date sortable asc"><a
					href="http://lp3/wp-admin/edit.php?orderby=date&amp;order=desc"><span>Date</span><span
						class="sorting-indicator"></span></a></th>
		<th scope="col" class="manage-column column-date sortable asc"><a
					href="http://lp3/wp-admin/edit.php?orderby=date&amp;order=desc"><span>Date</span><span
						class="sorting-indicator"></span></a></th>
		<th scope="col" class="manage-column column-date sortable asc"><a
					href="http://lp3/wp-admin/edit.php?orderby=date&amp;order=desc"><span>Date</span><span
						class="sorting-indicator"></span></a></th>
		<th scope="col">Da chia the</th>
		<th scope="col">Ghi chu</th>
	</tr>
	</tfoot>

</table>
