<?php

class Api {

    // Note: Only the widely used HTTP status codes are documented

    // Informational

    const HTTP_CONTINUE = 100;
    const HTTP_SWITCHING_PROTOCOLS = 101;
    const HTTP_PROCESSING = 102;            // RFC2518

    // Success

    /**
     * The request has succeeded
     */
    const HTTP_OK = 200;

    /**
     * The server successfully created a new resource
     */
    const HTTP_CREATED = 201;
    const HTTP_ACCEPTED = 202;
    const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;

    /**
     * The server successfully processed the request, though no content is returned
     */
    const HTTP_NO_CONTENT = 204;
    const HTTP_RESET_CONTENT = 205;
    const HTTP_PARTIAL_CONTENT = 206;
    const HTTP_MULTI_STATUS = 207;          // RFC4918
    const HTTP_ALREADY_REPORTED = 208;      // RFC5842
    const HTTP_IM_USED = 226;               // RFC3229

    // Redirection

    const HTTP_MULTIPLE_CHOICES = 300;
    const HTTP_MOVED_PERMANENTLY = 301;
    const HTTP_FOUND = 302;
    const HTTP_SEE_OTHER = 303;

    /**
     * The resource has not been modified since the last request
     */
    const HTTP_NOT_MODIFIED = 304;
    const HTTP_USE_PROXY = 305;
    const HTTP_RESERVED = 306;
    const HTTP_TEMPORARY_REDIRECT = 307;
    const HTTP_PERMANENTLY_REDIRECT = 308;  // RFC7238

    // Client Error

    /**
     * The request cannot be fulfilled due to multiple errors
     */
    const HTTP_BAD_REQUEST = 400;

    /**
     * The user is unauthorized to access the requested resource
     */
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_PAYMENT_REQUIRED = 402;

    /**
     * The requested resource is unavailable at this present time
     */
    const HTTP_FORBIDDEN = 403;

    /**
     * The requested resource could not be found
     *
     * Note: This is sometimes used to mask if there was an UNAUTHORIZED (401) or
     * FORBIDDEN (403) error, for security reasons
     */
    const HTTP_NOT_FOUND = 404;

    /**
     * The request method is not supported by the following resource
     */
    const HTTP_METHOD_NOT_ALLOWED = 405;

    /**
     * The request was not acceptable
     */
    const HTTP_NOT_ACCEPTABLE = 406;
    const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
    const HTTP_REQUEST_TIMEOUT = 408;

    /**
     * The request could not be completed due to a conflict with the current state
     * of the resource
     */
    const HTTP_CONFLICT = 409;
    const HTTP_GONE = 410;
    const HTTP_LENGTH_REQUIRED = 411;
    const HTTP_PRECONDITION_FAILED = 412;
    const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
    const HTTP_REQUEST_URI_TOO_LONG = 414;
    const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
    const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    const HTTP_EXPECTATION_FAILED = 417;
    const HTTP_I_AM_A_TEAPOT = 418;                                               // RFC2324
    const HTTP_UNPROCESSABLE_ENTITY = 422;                                        // RFC4918
    const HTTP_LOCKED = 423;                                                      // RFC4918
    const HTTP_FAILED_DEPENDENCY = 424;                                           // RFC4918
    const HTTP_RESERVED_FOR_WEBDAV_ADVANCED_COLLECTIONS_EXPIRED_PROPOSAL = 425;   // RFC2817
    const HTTP_UPGRADE_REQUIRED = 426;                                            // RFC2817
    const HTTP_PRECONDITION_REQUIRED = 428;                                       // RFC6585
    const HTTP_TOO_MANY_REQUESTS = 429;                                           // RFC6585
    const HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;                             // RFC6585

    // Server Error

    /**
     * The server encountered an unexpected error
     *
     * Note: This is a generic error message when no specific message
     * is suitable
     */
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    /**
     * The server does not recognise the request method
     */
    const HTTP_NOT_IMPLEMENTED = 501;
    const HTTP_BAD_GATEWAY = 502;
    const HTTP_SERVICE_UNAVAILABLE = 503;
    const HTTP_GATEWAY_TIMEOUT = 504;
    const HTTP_VERSION_NOT_SUPPORTED = 505;
    const HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;                        // RFC2295
    const HTTP_INSUFFICIENT_STORAGE = 507;                                        // RFC4918
    const HTTP_LOOP_DETECTED = 508;                                               // RFC5842
    const HTTP_NOT_EXTENDED = 510;                                                // RFC2774
    const HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;

    public function __construct() {
        $this->app = & get_instance();
        header('Access-Control-Allow-Origin: *');
    }

    public function authenticate() {
        $header = $this->app->input->request_headers(TRUE);

        $where['user_domain'] = isset($header['Host']) ? $header['Host'] : '';
        $where['api_key']     = isset($header['Api-Key']) ? $header['Api-Key'] : '';
        $where['secret_key']  = isset($header['Secret-Key']) ? $header['Secret-Key'] : '';

        // $where['api_key'] = $api_key;
        // $where['secret_key'] = $secret_key;
        if (!empty($where['user_domain']) && !empty($where['api_key']) && !empty($where['secret_key'])) {
            $check_api = $this->app->db->get_where('api_users', $where);

            return ($check_api->num_rows() > 0) ? TRUE : FALSE;
        } else {
            return FALSE;
        }
    }

    public function response($data = NULL, $http_code = NULL, $continue = FALSE)
    {
        ob_start();
        // If the HTTP status is not NULL, then cast as an integer
        if ($http_code !== NULL)
        {
            // So as to be safe later on in the process
            $http_code = (int) $http_code;
        }

        // Set the output as NULL by default
        $output = NULL;

        // If data is NULL and no HTTP status code provided, then display, error and exit
        if ($data === NULL && $http_code === NULL)
        {
            $http_code = self::HTTP_NOT_FOUND;
        }

        // If data is not NULL and a HTTP status code provided, then continue
        elseif ($data !== NULL)
        {
            // Set the format header
            $this->app->output->set_content_type('application/json', strtolower('UTF-8'));
            $output = json_encode($data);
        }

        // If not greater than zero, then set the HTTP status code as 200 by default
        // Though perhaps 500 should be set instead, for the developer not passing a
        // correct HTTP status code
        $http_code > 0 || $http_code = self::HTTP_OK;

        $this->app->output->set_status_header($http_code);

        // JC: Log response code only if rest logging enabled
        // if ($this->config->item('rest_enable_logging') === TRUE)
        // {
        //     $this->_log_response_code($http_code);
        // }

        // Output the data
        $this->app->output->set_output($output);

        if ($continue === FALSE)
        {
            // Display the data and exit execution
            $this->app->output->_display();
            exit;
        }
        else
        {
            ob_end_flush();
        }
    }

    public function set_response($data = NULL, $http_code = NULL)
    {
        $this->response($data, $http_code, TRUE);
    }

    public function generate_key()
    {
        $this->app->load->library('security');
        do
        {
            // Generate a random salt
            $salt = base_convert(bin2hex($this->app->security->get_random_bytes(64)), 16, 36);

            // If an error occurred, then fall back to the previous method
            if ($salt === FALSE)
            {
                $salt = hash('sha256', time() . mt_rand());
            }

            $new_key = substr($salt, 0, 40);
        }
        while ($this->_key_exists($new_key));

        $header = $this->app->input->request_headers();
        $data_api_users = array(
            'user_domain'   => $header['Host'],
            'api_key'       => $new_key,
            'secret_key'    => SECRET_KEY,
            'created_at'    => date('Y-m-d H:i:s')
        );
        $this->app->db->insert('api_users', $data_api_users);
        return $new_key;
    }

    private function _key_exists($key)
    {
        return $this->app->db
            ->where('api_key', $key)
            ->count_all_results('api_users') > 0;
    }

}
