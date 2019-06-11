<?php
namespace TGI\UserFrontend\Includes;

class TGI_User_Frontend_Helper {
    /**
     * TGI_User_Frontend_Helper constructor.
     */
    public function __construct() {
        // dummy constructor
    }

    /**
     * Append query parameter into URL
     * If the query parameter exist, the value will be overwritten.
     * @param string $url
     * @param string $key
     * @param $value
     * @return string
     */
    public function add_query_param( $url, $key, $value ) {
        if ( ! $value ) {
            return $url;
        }

        $url = preg_replace('/(.*)(\?|&)'. $key .'=[^&]+?(&)(.*)/i', '$1$2$4', $url .'&');
        $url = substr($url, 0, -1);

        if ( strpos($url, '?') === false ) {
            return ($url .'?'. $key .'='. $value);
        } else {
            return ($url .'&'. $key .'='. $value);
        }
    }

    /**
     * Append query parameters into URL
     * @param string $url
     * @param mixed[] $params
     * @return string
     */
    public function add_query_params($url, $params) {
        foreach ( $params as $key => $value ) {
            $url = $this->add_query_param($url, $key, $value);
        }
        return $url;
    }
}