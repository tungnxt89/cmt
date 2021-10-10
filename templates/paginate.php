<?php
	$next_page = true;
	$prev_page = true;

	if($page_current >= $total_pages) {
		$next_page = false;
	} elseif ($page_current == 1) {
		$prev_page = false;
	}
?>

<div class="tablenav-pages">
	<span class="displaying-num"><?php echo $total_users ?> items</span>
	<span class="pagination-links">
		<span class="first-page button <?php echo $prev_page ? '' : 'disabled'?>" aria-hidden="true">«</span>
		<span class="prev-page button <?php echo $prev_page ? '' : 'disabled'?>" aria-hidden="true">‹</span>
		<span class="screen-reader-text">Current Page</span>
		<span id="table-paging" class="paging-input">
			<span class="tablenav-paging-text">
				<input class="current-page" id="current-page-selector" type="text" name="paged" 
			value="<?php echo $page_current ?>" size="1" aria-describedby="table-paging"> of 
			<span class="total-pages"><?php echo $total_pages ?></span>
			</span>
		</span>
		<a class="next-page button <?php echo $next_page ? '' : 'disabled'?>" href="#wrapper-list-users">
			<span class="screen-reader-text">Next page</span><span aria-hidden="true">›</span>
		</a>
		<span class="last-page button <?php echo $next_page ? '' : 'disabled'?>" aria-hidden="true">»</span>
	</span>
</div>