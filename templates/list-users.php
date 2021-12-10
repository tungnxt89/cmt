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
		<th scope="col" id="author" class="manage-column column-author">Mã danh sách đề nghị</th>
		<th scope="col" id="author" class="manage-column column-author sorted desc">Ngay Khai<span class="sorting-indicator"></span></th>
		<th scope="col" id="categories" class="manage-column column-categories">Mã tờ khai</th>
		<th scope="col" id="tags" class="manage-column column-tags">Tên</th>
		<th scope="col" id="comments" class="manage-column column-comments num sortable desc">Số CCCD</th>
		<th scope="col" id="date" class="manage-column column-date sortable asc">Ngày sinh</th>
		<th scope="col" id="date" class="manage-column column-date sortable asc">Giới tính</th>
		<th scope="col" id="date" class="manage-column column-date sortable asc">Địa chỉ</th>
		<th scope="col" id="date" class="manage-column column-date sortable asc">Địa chỉ đầy đủ</th>
		<th scope="col" id="date" class="manage-column column-date sortable asc">Đã trả</th>
	</tr>
	</thead>

	<tbody id="the-list">
	<?php
	foreach ( $users as $user ) {
		?>
		<tr id="post-<?php echo $user->id; ?>"
			class="iedit author-self level-0 post-1 type-post status-publish format-standard hentry category-uncategorized entry">
			<th scope="row" class="check-column"><label class="screen-reader-text" for="cb-select-1">
					Select Hello world! </label>
				<input id="cb-select-1" type="checkbox" name="post[]" value="1">
				<div class="locked-indicator">
					<span class="locked-indicator-icon" aria-hidden="true"></span>
					<span class="screen-reader-text">
				“Hello world!” is locked				</span>
				</div>
			</th>
			<td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
				<div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
				<strong>
					<a class="row-title" href="#"
						aria-label="“Hello world!” (Edit)"><?php echo $user->box_id; ?></a>
				</strong>

				<div class="hidden" id="inline_1">
					<div class="post_title"><?php echo $user->box_id; ?></div>
					<div class="post_name">hello-world</div>
					<div class="post_author">1</div>
					<div class="comment_status">open</div>
					<div class="ping_status">open</div>
					<div class="_status">publish</div>
					<div class="jj">10</div>
					<div class="mm">05</div>
					<div class="aa">2021</div>
					<div class="hh">07</div>
					<div class="mn">24</div>
					<div class="ss">21</div>
					<div class="post_password"></div>
					<div class="page_template">default</div>
					<div class="post_category" id="category_1">1</div>
					<div class="tags_input" id="post_tag_1"></div>
					<div class="sticky"></div>
					<div class="post_format"></div>
				</div>
				<div class="row-actions">
					<span class="edit"><a href="#" aria-label="Edit “Hello world!”">Edit</a> | </span>
					<span class="inline hide-if-no-js">
						<button type="button" class="button-link editinline"
							aria-label="Quick edit “Hello world!” inline"
							aria-expanded="false">Quick&nbsp;Edit</button> | </span><spanclass="trash">
						<a href="http://lp3/wp-admin/post.php?post=1&amp;action=trash&amp;_wpnonce=a04ddc5ecc"
								class="submitdelete"
								aria-label="Move “Hello world!” to the Trash">Trash</a> | </span><span
							class="view"><a href="http://lp3/2021/05/10/hello-world/" rel="bookmark"
											aria-label="View “Hello world!”">View</a></span></div>
				<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span>
				</button>
			</td>
			<td class="author column-author" data-colname="Author"><a
						href="edit.php?post_type=post&amp;author=1"><?php echo $user->ma_dsdn; ?></a>
			</td>
			<td class="author column-author" data-colname="Author"><a
						href="edit.php?post_type=post&amp;author=1"><?php echo $user->ngay_khai; ?></a>
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
			<td class="date column-date" data-colname="Date"><?php echo $user->returnx; ?></td>
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
	</tr>
	</tfoot>

</table>
