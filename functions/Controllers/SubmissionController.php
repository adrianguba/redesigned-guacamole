<?php
namespace OsomRecrutation\Controllers\SubmissionController;

use WP_REST_Controller;
use WP_REST_Server;
use WP_REST_Response;
use WP_Error;
use OsomRecrutation\Models\Submission\Submission;

class SubmissionController extends WP_REST_Controller
{
    public $submission;

    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
        $this->submission = new Submission;
    }

    public function register_routes()
    {
        $version = '1';
        $namespace = 'osom-recrutation/v' . $version;
        $base = 'submissions';
        register_rest_route( $namespace, '/' . $base, array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( $this, 'get_items' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
                'args'                => array(

                ),
            ),
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( $this, 'create_item' ),
                'args'                => $this->get_endpoint_args_for_item_schema( true ),
            ),
        ) );
    }
    public function hook_rest_server(){
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

    public function get_items( $request ) {
        $request_params = $request->get_query_params();

        $items_collection = get_transient('osom_recrutation_submissions');

        if(empty($items_collection)) {
            $items_collection = $this->submission->getAll();

            set_transient('osom_recrutation_submissions',$items_collection,30*60);
        }


        if(isset($request_params['search'])) {
            $results = $this->searchForItem($items_collection,$request_params['search']);
        } else {
            $results = $items_collection;
        }


        $response = new WP_REST_Response($results , 200 );

        $response->set_headers(array('Cache-Control' => 'max-age=1800'));

        return $response;
    }

    public function create_item( $request ) {

        if(!$this->validate_create_item($request)) {
             return new WP_Error( 'cant-create', array( 'status' => 500 ) );
        }

        $data = $this->prepare_item_for_save($request);

        $this->submission->save($data);

        return new WP_REST_Response( array('success' => true, 'message' => 'Submission saved'), 200 );

    }

    public function get_items_permissions_check( $request ) {
       return true;
    }

    public function validate_create_item( $request ) {

        $required_fields = array('first_name','last_name','login','email','city');

        $available_cities = array('katowice','lodz','warszawa');

        foreach($required_fields as $field) {
            if(empty($request->get_param($field))) {
                return false;
            }
        }

        if(!filter_var($request->get_param('email'), FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if(!in_array($request->get_param('city'),$available_cities)) {
            return false;
        }

        return true;

    }

    public function prepare_item_for_save($request) {

        $data = $request->get_body_params();

        foreach($data as $key => $single_data) {

            $single_data_stripped = strip_tags($single_data);

            $data[$key] = $single_data_stripped;
        }

        return $data;

    }

    public function searchForItem($collection,$search) {
        $results = [];
        foreach($collection as $item) {

            $index = array_search($search,$item);

            if($index) {
                $results[] = $item;
            }

        }

        return $results;
    }
}