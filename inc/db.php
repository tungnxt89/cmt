<?php

/**
 * class CMT_DB
 * @author tungnx
 * @version 1.0.0
 */
class CMT_DB {
	public static $instance;
	public $wpdb;
	public $tb_cmt_users;
	public $limit = 10;

	protected function __construct() {
		global $wpdb;

		$this->wpdb         = $wpdb;
		$this->tb_cmt_users = $wpdb->prefix . 'cmt_users';
		$this->limit        = get_option( 'posts_per_page', $this->limit );
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @throws Exception
	 */
	public function createTables() {
		$this->create_tb_users();
	}

	/**
	 * Create table cmt_users
	 *
	 * @return bool|int
	 * @throws Exception
	 */
	public function create_tb_users() {
		$execute = $this->wpdb->query(
			"
			CREATE TABLE IF NOT EXISTS $this->tb_cmt_users(
				id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				box_id bigint(20) default 0,
				stt_barcode int default 0,
				ma_dsdn varchar(255) NOT NULL,
				ngay_khai date NOT NULL,
				noi_tra varchar(255) NOT NULL,
				lay_ho varchar(255) NOT NULL,
				ma_to_khai varchar(255) NOT NULL,
				name varchar(255) NOT NULL,
				so_cccd varchar(255) default 0,
				birthday varchar(255) NOT NULL,
				sex varchar(5) NOT NULL,
				address varchar(255) NOT NULL,
				address_full varchar(255) NOT NULL,
				returnx int(1) default 0,
				note text,
				PRIMARY KEY (id),
				KEY box_id (box_id),
				KEY ma_dsdn (ma_dsdn),
				KEY ma_to_khai (ma_to_khai),
				KEY name (name),
				KEY so_cccd (so_cccd),
				KEY bithday (birthday),
				KEY sex (sex),
				KEY address (address)
			)
			"
		);

		return $execute;
	}

	/**
	 * Insert users to table cmt_users
	 *
	 * @return bool|int
	 * @throws Exception
	 */
	public function insert_data_users( array $data, array $data_ma_to_khai_insert ) {
		$query = "
			INSERT INTO $this->tb_cmt_users (ma_dsdn, ngay_khai, noi_tra, lay_ho, ma_to_khai, name, so_cccd, birthday, sex, address)
			VALUES
		";

		$data_row_arr = [];

		foreach ( $data as $key => $data_row ) {
			if ( in_array( $data_row['ma_to_khai'], $data_ma_to_khai_insert ) ) {
				continue;
			}

			$data_row['ngay_khai'] = '20' . substr( $data_row['ma_to_khai'], 2, 2 ) . '-' . substr( $data_row['ma_to_khai'], 4, 2 ) . '-' . substr( $data_row['ma_to_khai'], 6, 2 );

			$row  = ' (';
			$row .= "'{$data_row['ma_dsdn']}',";
			$row .= "'{$data_row['ngay_khai']}',";
			$row .= "'{$data_row['noi_tra']}',";
			$row .= "'{$data_row['lay_ho']}',";
			$row .= "'{$data_row['ma_to_khai']}',";
			$row .= $this->wpdb->prepare( '%s,', $data_row['name'] );
			$row .= "'{$data_row['so_cccd']}',";
			$row .= "'{$data_row['birthday']}',";
			$row .= "'{$data_row['sex']}',";
			$row .= $this->wpdb->prepare( '%s', $data_row['address'] );
			$row .= ')';

			$data_row_arr[] = $row;
		}

		if ( empty( $data_row_arr ) ) {
			return true;
		}

		$data_row_str = implode( ',', $data_row_arr );
		$query       .= $data_row_str;

		$execute = $this->wpdb->query( $query );

		if ( $this->wpdb->last_error ) {
			error_log( __FUNCTION__ . ': ' . $this->wpdb->last_error );
		}

		return $execute;
	}

	/**
	 * Check mã tờ khai đã được inserted
	 *
	 * @return bool|int
	 * @throws Exception
	 */
	public function check_datas_ma_tk_inserted( array $data_ma_tk_insert ) {
		$data_ma_tk_str = implode( ',', $data_ma_tk_insert );

		$query = "
			SELECT ma_to_khai
			FROM $this->tb_cmt_users
			WHERE ma_to_khai IN ({$data_ma_tk_str})
		";

		$result = $this->wpdb->get_results( $query, OBJECT_K );

		if ( $this->wpdb->last_error ) {
			error_log( __FUNCTION__ . ': ' . $this->wpdb->last_error );
		}

		return $result;
	}

	/**
	 * Check mã tờ khai đã được inserted
	 *
	 * @return bool|int
	 * @throws Exception
	 */
	public function check_update_data_users( Filter_User $filter_user, array $data_update ) {
		$query = $this->wpdb->prepare(
			"
				UPDATE $this->tb_cmt_users
				SET address_full = %s
				WHERE name = %s
				AND sex = %s
				AND birthday = %s
				AND address LIKE %s
			",
			$data_update['address_full'],
			$filter_user->name,
			$filter_user->sex,
			$filter_user->birthday,
			'%' . trim( $filter_user->address ) . '%'
		);

		$result = $this->wpdb->query( $query );

		if ( $this->wpdb->last_error ) {
			error_log( __FUNCTION__ . ': ' . $this->wpdb->last_error );
		}

		return $result;
	}

	/**
	 * Get users
	 *
	 * @param Filter_User $filter
	 * @return array|object|null
	 * @throws Exception
	 */
	public function get_users( Filter_User $filter ) {
		$offset = $this->limit * ( $filter->page - 1 );

		$fields_arr = get_object_vars( $filter );
		unset( $fields_arr['page'] );
		$fields_arr    = array_keys( $fields_arr );
		$select_fields = implode( ',', $fields_arr );

		$WHERE = ' WHERE 1=1 ';

		if ( $filter->box_id ) {
			$WHERE .= $this->wpdb->prepare( 'AND box_id = %s ', $filter->box_id );
		}

		if ( $filter->ma_dsdn ) {
			$WHERE .= $this->wpdb->prepare( 'AND ma_dsdn LIKE "%s" ', '%' . $filter->ma_dsdn . '%' );
		}

		if ( $filter->noi_tra ) {
			$WHERE .= $this->wpdb->prepare( 'AND noi_tra LIKE %s ', '%' . $filter->noi_tra . '%' );
		}

		if ( $filter->name ) {
			$WHERE .= $this->wpdb->prepare( 'AND name LIKE %s ', '%' . $filter->name . '%' );
		}

		if ( $filter->birthday ) {
			$WHERE .= $this->wpdb->prepare( 'AND birthday = %s ', $filter->birthday );
		}

		if ( $filter->sex ) {
			$WHERE .= $this->wpdb->prepare( 'AND sex = %s ', $filter->sex );
		}

		$group_by = 'ngay_khai';
		$sort     = 'DESC';

		$query = $this->wpdb->prepare(
			"
			SELECT $select_fields
			FROM $this->tb_cmt_users
			$WHERE
			ORDER BY $group_by $sort
			LIMIT %d, %d
			",
			$offset,
			$this->limit
		);

		$result = $this->wpdb->get_results( $query );

		$query_count = "SELECT COUNT(id) AS total
			FROM $this->tb_cmt_users
			$WHERE
		";

		$total_items = (int) $this->wpdb->get_var( $query_count );

		$result['total_row'] = $total_items;

		if ( $this->wpdb->last_error ) {
			error_log( __FUNCTION__ . ': ' . $this->wpdb->last_error );
		}

		return $result;
	}

	/**
	 * Get total
	 *
	 * @return int
	 */
	public function total_users(): int {
		$query =
			"
			SELECT COUNT(*) AS total
			FROM $this->tb_cmt_users
			";

		$total = (int) $this->wpdb->get_var( $query );

		if ( $this->wpdb->last_error ) {
			error_log( __FUNCTION__ . ': ' . $this->wpdb->last_error );
		}

		return $total;
	}

	/**
	 * Check mã tờ khai đã được inserted
	 *
	 * @return bool|int
	 * @throws Exception
	 */
	public function check_update_cccd( Filter_User $filter_user, array $data_update ) {
		$query = $this->wpdb->prepare(
			"
				UPDATE $this->tb_cmt_users
				SET so_cccd = %s,
				box_id = %s,
				stt_barcode = %d
				WHERE name = %s
				AND box_id = 0
				AND sex = %s
				AND birthday = %s
				AND address_full = %s
			",
			$data_update['cccd'],
			$data_update['box_id'],
			$data_update['stt'],
			$filter_user->name,
			$filter_user->sex,
			$filter_user->birthday,
			trim( $filter_user->address )
		);

		error_log( 'SQL:' . $query );

		$result = $this->wpdb->query( $query );

		if ( $this->wpdb->last_error ) {
			error_log( __FUNCTION__ . ': ' . $this->wpdb->last_error );
		}

		return $result;
	}

	public function get_increment_of_box() {
		$query =
			"SELECT MAX(box_id)
			FROM $this->tb_cmt_users
			";

		$box_id = (int) $this->wpdb->get_var( $query );

		if ( $this->wpdb->last_error ) {
			error_log( __FUNCTION__ . ': ' . $this->wpdb->last_error );
		}

		return $box_id;
	}

	public function create_box_id() {

	}
}

CMT_DB::instance();
