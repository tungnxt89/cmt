<?php
/**
 * Template search
 *
 * @author tungnx
 * @version 1.0.0
 */
?>

<div class="cmt-search">
	<div>
		<input type="text" name="box_id" placeholder="Box ID" class="field-search">
		<input type="text" name="ma_dsdn" placeholder="Ma danh sach de nghi" class="field-search">
		<input type="text" name="noi_tra" placeholder="Noi tra" class="field-search">
		<input type="text" name="name" placeholder="Ten" class="field-search">
		<input type="text" name="birthday" placeholder="Ngay sinh" class="field-search">
		<select name="returnx" class="field-search">
			<option value="">Trang thai tra the</option>
			<option value="0">Chua tra</option>
			<option value="1">Da tra</option>
		</select>
		<select name="sex" class="field-search">
			<option value="">Chon gioi tinh</option>
			<option value="Nam">Nam</option>
			<option value="Nữ">Nữ</option>
		</select>
		<input type="text" name="address" placeholder="Dia chi" class="field-search">
		<select name="limit" class="field-search">
			<option value="20">20</option>
			<option value="50">50</option>
			<option value="100">100</option>
		</select>
        <button id="search">Tim kiem</button>
	</div>
</div>
