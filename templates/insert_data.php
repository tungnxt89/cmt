<?php
/**
 * Template Insert data
 */
?>

<form action="" enctype="multipart/form-data" id="file_csv">
    <div class="el-upload left">
        <label for="">Nhập danh sách mã tờ khai</label>
        <input type="file" name="file_csv" webkitdirectory multiple>
    </div>
    <div class="el-upload left">
        <label>Nhập danh sách mã tổng hợp (chứa địa chỉ cụ thể)</label>
        <input type="file" name="file_address_csv" webkitdirectory multiple>
    </div>
    <div class="el-upload left">
        <label>File Barcode</label>
        <input type="file" name="file_barcode_csv" accept=".csv">
    </div>
    
    <div class="clear"></div>

    <div class="info">
        <div>
            <b>Tổng files:</b>
            <span id="total-files"></span>-
            <b>Tổng rows:</b>
            <span id="total-rows"></span>
        </div>
        <div>
            <b>Tiến trình:</b>
            <span id="progress"></span>
        </div>

    </div>
</form>
