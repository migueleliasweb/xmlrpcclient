<?php

namespace migueleliasweb\xmlrpcclient;

class XMLRPCClient
{

    private $Host;
    private $CurlResource;

    /**
     * @param string $Host
     */
    public function __construct ($Host) {
        $this->setHost($Host);
        $this->setCurlResource(curl_init());
        $this->setupCurlResource(curl_init());
    }
    
    public function __destruct () {
        $this->close();
    }
    
    private function setupCurlResource () {
        /* Configure the request */
        curl_setopt($this->getCurlResource(), CURLOPT_URL, $this->getHost());
        curl_setopt($this->getCurlResource(), CURLOPT_HEADER, 0);
        curl_setopt($this->getCurlResource(), CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->getCurlResource(), CURLOPT_POST, true);
    }
    
    /**
     * @return string
     */
    public function getHost () {
        return $this->Host;
    }

    /**
     * @param string $Hos
     */
    private function setHost ($Host) {
        $this->Host = $Host;
    }

    /**
     * @return \resource
     */
    public function getCurlResource () {
        return $this->CurlResource;
    }

    /**
     * @param resource $CurlResource
     */
    private function setCurlResource ($CurlResource) {
        $this->CurlResource = $CurlResource;
    }

    public function close () {
        curl_close($this->getCurlResource());
    }
    
    /**
     * @param string $method
     * @param mixed $params
     * @return mixed
     * @throws XMLRPC
     */
    public function request ($method, $params = array()) {
        $encoded_request = xmlrpc_encode_request($method, $params);
        
        curl_setopt($this->getCurlResource(), CURLOPT_POSTFIELDS, $encoded_request);
        
        return xmlrpc_decode_request(curl_exec($this->getCurlResource()), $method);
    }

}