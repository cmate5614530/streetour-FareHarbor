<?php
/*
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
 if ( ! defined( 'TURITOP_BOOKING_SYSTEM_VERSION' ) ) {
     exit( 'Direct access forbidden.' );
 }

/**
 *
 *
 * @class      simpledevel_wc_wc_pospage_admin
 * @package    Simpledevel
 * @since      Version 1.0.1
 * @author     Daniel Sanchez Saez
 *
 */

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class turitop_booking_system_service_cpt_table extends WP_List_Table {

  /**
   * pages
   *
   * @var array
   * @since 1.0.1
   * @author Daniel Sanchez Saez <dssaez@gmail.com>
   * @access public
   */
  public $pages = array();

	/**
	 * turitop_booking_system_service_cpt_table constructor.
	 *
	 * @param array $args
	 */
	public function __construct( $args = array() ) {
		parent::__construct( array(
			'singular' => __( 'Service', 'turitop-booking-system' ),
			'plural'   => __( 'Services', 'turitop-booking-system' ),
			'ajax'     => false
		) );

    $this->pages = get_pages();

	}

	/**
	 * @return array
	 */
	function get_columns() {

		$columns = array(
			'cb'           => '<input type="checkbox" />',
			'turitop_id'   => __( 'Turitop ID', 'turitop-booking-system' ),
			'name'         => __( 'Name', 'turitop-booking-system' ),
      'summary'      => __( 'Summary', 'turitop-booking-system' ),
      'menu_order'   => __( 'Order', 'turitop-booking-system' ),
      'page'         => __( 'Page', 'turitop-booking-system' ),
      'update'       => '',
		);

		return apply_filters( 'turitop_booking_system_service_list_columns', $columns );
	}

	/**
	 * Column default function
	 *
	 * @param object $item
	 * @param string $column_name
	 *
	 * @return string|void
	 */
	function column_default( $item, $column_name ) {
    //wp_delete_post( $item->ID );
    $data = get_post_meta( $item->ID, 'turitop_booking_system_service_data', true );
    $lang = ( isset( $data[ 'langs' ][ 'en' ] ) ? $data[ 'langs' ][ 'en' ] : array_shift( $data[ 'langs' ] ) );

		switch ( $column_name ) {

			case 'turitop_id':
				return ( $item->post_title ) ? '<span><a href="' . admin_url( 'post.php?post=' . $item->ID . '&action=edit' ) . '">' . $item->post_title . '</a></span>' : '';
        //return ( $item->post_title ) ? '<span>' . $item->post_title . '</span>' : '';
				break;

			case 'name':
				//$name = get_post_meta( $item->ID, 'simpled_wc_pospage_settings_description_textarea', true );
        $name = ( isset( $lang[ 'name' ] ) ? $lang[ 'name' ] : '' );
				return ( $name ) ? '<span>' . $name . '</span>' : '';
				break;

      case 'summary':
        $summary = ( isset( $lang[ 'summary' ] ) ? $lang[ 'summary' ] : '' );
        return ( $summary ) ? '<span>' . $summary . '</span>' : '';
				break;

      case 'menu_order':
        return '<input id="turitop_booking_system_select_service_order_' . $item->ID . '" name="turitop_booking_system_select_service_order" type="text" value="' . $item->menu_order . '" style="width: 30px;">';
				break;

      case 'page':
        $page_id = ( isset( $data[ 'page_id' ] ) ? $data[ 'page_id' ] : 0 );
        ob_start();

        echo '<select id="turitop_booking_system_select_page_for_service_' . $item->ID . '" name="turitop_booking_system_select_page_for_service" style="width: 200px;">';

          echo '<option value="0">' . __( 'Choose a page', 'turitop-booking-system' ) . '</option>';

          foreach ( $this->pages as $page ) {
            $selected = ( $page->ID == $page_id ? "selected='selected'" : "" );
            echo '<option ' . $selected . ' value="' . $page->ID . '">' . $page->post_title . '</option>';
          }

        echo '</select>';

        $content = ob_get_clean();
        return $content;
				break;

      case 'update':
        return '<a href="action" data-service_id="' . $item->ID . '" class="simpled_button_link turitop_booking_system_synhronize_upload_button_services">Update</a>';
				break;

      case 'turitop_page_url':
				//$page_id = get_post_meta( $item->ID, 'simpled_wc_pospage_settings_page_id_select', true );
        //$url = get_page_link( $page_id );

				//return ( $url ) ? '<a href="' . $url . '" target="_blank">' . $url . '</a>' : '';

				break;

			default:
				return apply_filters( 'turitop_booking_system_service_column_default', '', $item, $column_name );
		}
	}

	/**
	 * Get views for the table
	 * @author Daniel Sanchez Saez
	 * @since  1.0.1
	 * @return array
	 */
	protected function get_views() {
		$views = array(
			'all'     => __( 'All', 'turitop-booking-system' ),
			'publish' => __( 'Published', 'turitop-booking-system' ),
			'trash'   => __( 'Trash', 'turitop-booking-system' )
		);

		$current_view = $this->get_current_view();

		foreach ( $views as $view_id => $view ) {

			$query_args = array(
				'posts_per_page'  => - 1,
				'post_type'       => TURITOP_BOOKING_SYSTEM_SERVICE_CPT,
				'post_status'     => 'publish',
				'suppress_filter' => false,
			);
			$status     = 'status';
			$id         = $view_id;

			if ( 'all' !== $view_id ) {
				$query_args['post_status'] = $view_id;
			}

			$href              = esc_url( add_query_arg( $status, $id ) );
			$total_items       = count( get_posts( $query_args ) );
			$class             = $view_id == $current_view ? 'current' : '';
			$views[ $view_id ] = sprintf( "<a href='%s' class='%s'>%s <span class='count'>(%d)</span></a>", $href, $class, $view, $total_items );
		}


		return $views;
	}

	/**
	 * return current view
	 * @author Daniel Sanchez Saez
	 * @since  1.0.1
	 * @return string
	 */
	public function get_current_view() {

		return empty( $_GET['status'] ) ? 'all' : $_GET['status'];
	}

	/**
	 * Prepares the list of items for displaying.
	 *
	 * @since 1.0.1
	 */
	function prepare_items() {

		$current_view = $this->get_current_view();
		if ( $current_view == 'all' ) {
			$current_view = 'any';
		}

		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$perpage               = apply_filters( 'turitop_booking_system_service_per_page', 15 );

		$args        = array(
			'post_type'      => TURITOP_BOOKING_SYSTEM_SERVICE_CPT,
			'post_status'    => $current_view,
			'posts_per_page' => $perpage,
			'paged'          => absint( $this->get_pagenum() ),
			'orderby'        => 'menu_order',
			'order'          => 'asc',
		);
		$query       = new WP_Query( $args );
		$this->items = $query->posts;

		/* -- Register the pagination -- */
		$this->set_pagination_args( array(
			"total_items" => $query->found_posts,
			"per_page"    => $perpage,
		) );
	}

	/**
	 * @author Daniel Sanchez Saez
	 * @since  1.0.1
	 *
	 * @param object $item
	 *
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="turitop_booking_system_service_ids[]" value="%s" />', $item->ID
		);
	}

	/**
	 * return bulk actions
	 * @author Daniel Sanchez Saez
	 * @since  1.0.1
	 * @return array|false|string
	 */
	public function get_bulk_actions() {

		$actions = $this->current_action();

		if ( isset( $_REQUEST[ 'turitop_booking_system_service_ids' ] ) ) {

			$rules = $_REQUEST[ 'turitop_booking_system_service_ids' ];

			if ( $actions == 'delete' ) {
				foreach ( $rules as $rule_id ) {
					wp_delete_post( $rule_id, true );
				}
			}

			$this->prepare_items();
		}

		$current_view = $this->get_current_view();
		if ( $current_view == 'trash' ) {
			$actions = array(
				'delete' => __( 'Delete permanently', 'turitop-booking-system' )
			);
		} else {
			$actions = array(
				'delete' => __( 'Delete', 'turitop-booking-system' )
			);
		}

		return $actions;
	}

	/**
	 * @return array
	 */
	function get_sortable_columns() {

		$sortable_columns = array(
			'post_title' => array( 'Rule name', false ),
			'rule_type'  => array( 'Rule type', false ),
			'priority'   => array( 'Priority', false ),
		);

		return $sortable_columns;
	}

	/**
	 * Function to edit or delete rules
	 * @return array
	 */
	protected function handle_row_actions( $post, $column_name, $primary ) {
		if ( $primary !== $column_name ) {
			return '';
		}

		$post_type_object = get_post_type_object( $post->post_type );
		$can_edit_post    = current_user_can( 'edit_post', $post->ID );
		$title            = _draft_or_post_title();
		$actions          = array();

		if ( $can_edit_post && 'trash' != $post->post_status ) {
			$actions['edit'] = sprintf(
				'<a href="%s" aria-label="%s">%s</a>',
				get_edit_post_link( $post->ID ),
				/* translators: %s: post title */
				esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), $title ) ),
				__( 'Edit' )
			);
		}

		if ( current_user_can( 'delete_post', $post->ID ) ) {
			if ( 'trash' === $post->post_status ) {
				$actions['untrash'] = sprintf(
					'<a href="%s" aria-label="%s">%s</a>',
					wp_nonce_url( admin_url( sprintf( $post_type_object->_edit_link . '&amp;action=untrash', $post->ID ) ), 'untrash-post_' . $post->ID ),
					/* translators: %s: post title */
					esc_attr( sprintf( __( 'Restore &#8220;%s&#8221; from the Trash' ), $title ) ),
					__( 'Restore' )
				);
			} elseif ( EMPTY_TRASH_DAYS ) {
				$actions['trash'] = sprintf(
					'<a href="%s" class="submitdelete" aria-label="%s">%s</a>',
					get_delete_post_link( $post->ID ),
					/* translators: %s: post title */
					esc_attr( sprintf( __( 'Move &#8220;%s&#8221; to the Trash' ), $title ) ),
					_x( 'Trash', 'verb' )
				);
			}
			if ( 'trash' === $post->post_status || ! EMPTY_TRASH_DAYS ) {
				$actions['delete'] = sprintf(
					'<a href="%s" class="submitdelete" aria-label="%s">%s</a>',
					get_delete_post_link( $post->ID, '', true ),
					/* translators: %s: post title */
					esc_attr( sprintf( __( 'Delete &#8220;%s&#8221; permanently' ), $title ) ),
					__( 'Delete Permanently' )
				);
			}
		}

		return $this->row_actions( $actions );
	}

}
