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
		register_rest_route( 'cmt/v1', '/create/users', array(
			'methods'  => WP_REST_Server::CREATABLE,
			'callback' => array( $this, 'create_users' ),
		) );

		register_rest_route( 'cmt/v1', '/update/users', array(
			'methods'  => WP_REST_Server::CREATABLE,
			'callback' => array( $this, 'update_users' ),
		) );
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
			$page = $request->get_param( 'page' ) ?? 0;

			$filter = new Filter_User();
			$filter->page = $page;
			$cmt_db->get_users($filter);

		} catch ( Throwable $e ) {
			$res->message = $e->getMessage();
		}

		wp_send_json( $res );
	}

	public static function instance(): CMT_API {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

