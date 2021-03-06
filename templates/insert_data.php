<?php
/**
 * Template Insert data
 */

$cmt_db = CMT_DB::instance();
$box_id_max =  $cmt_db->get_increment_of_box();
$upload_arr = wp_upload_dir();
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
        <input type="text" name="box_id_max" value="<?php echo $box_id_max ?>" disabled />
    </div>

    <input type="hidden" name="file_ds_noi_tra" value="<?php echo $upload_arr['baseurl'] . '/cmt/ds-noi-tra.csv' ?>">
    <input type="hidden" name="file_ds_lay_ho" value="<?php echo $upload_arr['baseurl'] . '/cmt/ds-lay-ho.csv' ?>">
    
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
