<?php

/**
 * class CMT_API
 */
class CMT_API {
	public static $instance;

	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_api' ) );
	}

	public function register_api() {
		register_rest_route(
			'cmt/v1',
			'/create/users',
			array(
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'create_users' ),
			)
		);

		register_rest_route(
			'cmt/v1',
			'/update/users',
			array(
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'update_users' ),
			)
		);

		register_rest_route(
			'cmt/v1',
			'/users',
			array(
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'list_users' ),
			)
		);

		register_rest_route(
			'cmt/v1',
			'/barcode',
			array(
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'barcode' ),
			)
		);
	}

	public function create_users( WP_REST_Request $request ) {
		$res          = new stdClass();
		$res->status  = 'error';
		$res->message = '';
		$res->data    = new stdClass();

		$cmt_db = CMT_DB::instance();

		try {
			$data_insert            = $request->get_param( 'data_insert' ) ?? [];
			$data_ma_to_khai_insert = $request->get_param( 'data_ma_to_khai_insert' ) ?? [];
			$page                   = $request->get_param( 'page' ) ?? 0;

			if ( empty( $data_insert ) || empty( $page ) || empty( $data_ma_to_khai_insert ) ) {
				throw new Exception( 'Tham số không hợp lệ' );
			}

			$data_ma_to_khai_insert = array_keys( (array) $cmt_db->check_datas_ma_tk_inserted( $data_ma_to_khai_insert ) );

			$result = $cmt_db->insert_data_users( $data_insert, $data_ma_to_khai_insert );

			if ( $result ) {
				$res->status = 'success';
				$page ++;
				$res->data->page = $page;
			} else {
				throw new Exception( 'Error when insert Danh sách yêu cầu cấp CCCD' );
			}
		} catch ( Throwable $e ) {
			$res->message = $e->getMessage();
		}

		wp_send_json( $res );
	}

	public function update_users( WP_REST_Request $request ) {
		$res          = new stdClass();
		$res->status  = 'error';
		$res->message = '';
		$res->data    = new stdClass();

		$cmt_db = CMT_DB::instance();

		try {
			$data_update = $request->get_param( 'data_update' ) ?? [];
			$page        = $request->get_param( 'page' ) ?? 0;
			$success     = 0;

			if ( empty( $data_update ) || empty( $page ) ) {
				throw new Exception( 'Tham số không hợp lệ' );
			}

			foreach ( $data_update as $data ) {
				$filter           = new Filter_User();
				$filter->name     = $data['name'];
				$filter->birthday = $data['birthday'];
				$filter->sex      = $data['sex'];
				$filter->address  = $data['address_search'];

				$result = $cmt_db->check_update_data_users( $filter, $data );
				if ( ! is_bool( $result ) && $result ) {

				}
			}

			$res->status = 'success';
			$page ++;
			$res->data->page = $page;
		} catch ( Throwable $e ) {
			$res->message = $e->getMessage();
		}

		wp_send_json( $res );
	}

	/**
	 * Get list user
	 *
	 * @param WP_REST_Request $request
	 */
	public function list_users( WP_REST_Request $request ) {
		$res          = new stdClass();
		$res->status  = 'error';
		$res->message = '';
		$res->data    = new stdClass();

		$cmt_db = CMT_DB::instance();

		try {
			$page = (int) $request->get_param( 'page' ) ?? 0;

			$filter           = new Filter_User();
			$filter->page     = $page;
			$filter->name     = $request->get_param( 'name' ) ?? '';
			$filter->box_id   = $request->get_param( 'box_id' ) ?? '';
			$filter->birthday = $request->get_param( 'birthday' ) ?? '';
			$filter->sex      = $request->get_param( 'sex' ) ?? '';
			$filter->address  = $request->get_param( 'address' ) ?? '';

			$users = $cmt_db->get_users( $filter );

			$content = '';
			ob_start();
			include CMT::$PATH_PLUGIN . 'templates/list-users.php';
			$content = ob_get_contents();
			ob_clean();

			$res->data->content  = $content;
			$res->data->paginate = $this->get_paginate( $page );
			$res->status         = 'success';
		} catch ( Throwable $e ) {
			$res->message = $e->getMessage();
		}

		wp_send_json( $res );
	}

	public function barcode( WP_REST_Request $request ) {
		$res          = new stdClass();
		$res->status  = 'error';
		$res->message = '';
		$res->data    = new stdClass();

		$cmt_db = CMT_DB::instance();

		try {
			$data_search = $request->get_param( 'data' ) ?? [];
			$page        = $request->get_param( 'page' ) ?? 0;
			$success     = 0;

			if ( empty( $data_search ) || empty( $page ) ) {
				throw new Exception( 'Tham số không hợp lệ' );
			}

			foreach ( $data_search as $data ) {
				$birthday_search = substr( $data['birthday'], 0, 2 ) . '-' . substr( $data['birthday'], 2, 2 ) . '-' . substr( $data['birthday'], 4, 4 );

				$address_arr       = explode( ',', $data['address_full'] );
				$count_address_arr = count( $address_arr );
				$index_country     = $count_address_arr - 1;
				$index_district    = $count_address_arr - 2;
				$index_ward        = $count_address_arr - 3;

				$address_search = '';
				for ( $i = 0; $i < $index_ward; $i++ ) {
					$address_search .= $address_arr[ $i ] . ', ';
				}

				$address_search .= 'Phường' . $address_arr[ $index_ward ] . ', ';
				$address_search .= 'Quận' . $address_arr[ $index_district ] . ', ';
				$address_search .= 'Thành phố' . $address_arr[ $index_country ];

				$filter           = new Filter_User();
				$filter->name     = $data['name'];
				$filter->birthday = $birthday_search;
				$filter->sex      = $data['sex'];
				$filter->address  = $address_search;
				$data['box_id']   = intval( $data['box_id'] ) + 1;

				$result = $cmt_db->check_update_cccd( $filter, $data );
				if ( ! is_bool( $result ) && $result ) {

				}
			}

			$res->status = 'success';
			$page ++;
			$res->data->page = $page;
		} catch ( Throwable $e ) {
			$res->message = $e->getMessage();
		}

		wp_send_json( $res );
	}

	protected function get_paginate( int $page_current ) {
		$cmt_db         = CMT_DB::instance();
		$total_users    = $cmt_db->total_users();
		$posts_per_page = get_option( 'posts_per_page', 10 );

		$total_pages = floor( $total_users / $posts_per_page );

		if ( $total_users % $posts_per_page !== 0 ) {
			$total_pages++;
		}

		$content = '';
		ob_start();
		include CMT::$PATH_PLUGIN . 'templates/paginate.php';
		$content = ob_get_contents();
		ob_clean();

		return $content;
	}

	public static function instance(): CMT_API {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

