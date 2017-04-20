<?php

/**
 * MTOM compatible SoapClient
 *
 * @package EMV
 */
class EMV_MTOMSoapClient extends SoapClient {

    /**
     * Filters the response and keeps only the XML data, ignores multipart
     *
     * @param string $request
     * @param string $location
     * @param string $action
     * @param int $version
     * @param int $one_way
     * @return string
     */
    public function __doRequest($request, $location, $action, $version, $one_way = 0) {
        $response = parent::__doRequest($request, $location, $action, $version, $one_way);
        $start = strpos($response, '<soap:');
        $end = strrpos($response, '>');
        $response_string = substr($response, $start, $end - $start + 1);
        return($response_string);
    }

}
