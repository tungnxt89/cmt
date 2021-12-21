<?php
$filter = new Filter_User();
$fields_arr = get_object_vars($filter);
unset($fields_arr['page']);

foreach ( $fields_arr as $key => $value ) {
	echo "<input name='display[]' type='checkbox' value={$key}>{$key}&nbsp;&nbsp;&nbsp;";
}

include_once 'insert_data.php';
include_once 'search.php';
?>

<div id="wrapper-list-users"></div>
<div class="tablenav bottom"></div>
