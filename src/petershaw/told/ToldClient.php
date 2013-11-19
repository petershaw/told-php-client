<?php

namespace petershaw\told;

class ToldClient {

    public static $VERSION = "0.0.4";
    public static $TRANSPORT = "http";
    private $host;
    private $debug = false;
    private $defaulttags = Array();
    private $tags = Array();
    private $type = null;
    private $msg;

    function __set($name, $value) {
        $this->$name = $value;
    }

    function __construct($data = Array()) {
        $this->msg = json_decode('{"type": "", "tags":[],"message":{}}');
        foreach ($data as $key => $value) {
            $this->__set($key, $value);
        }
    }

    public function getHost() {
        return $this->host;
    }

    public function setHost($host) {
        $this->host = $host;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setDefaulttags($defaulttags) {
        if (is_array($defaulttags)) {
            $this->defaulttags = $defaulttags;
        } else if (is_string($defaulttags)) {
            $this->defaulttags = explode(",", $defaulttags);
        }
    }

    public function setTags($tags) {
        if (is_array($tags)) {
            $this->tags = $tags;
        } else if (is_string($tags)) {
            $this->tags = explode(",", $tags);
        }
    }

    public function setDebug($debug) {
        $this->debug = $debug;
    }

    public function tell($message, $type = null, $tags = null) {

        if (!isset($this->host) or empty($this->host)) {
            if ($this->debug) {
                echo("Host is not set.\n");
                echo implode("\n", debug_backtrace());
            }
            throw new \Exception("Host not set");
        }

        $mObj = clone $this->msg;
        // add default tags
        if (count($this->defaulttags) > 0) {
            $mObj->tags = array_merge($mObj->tags, $this->defaulttags);
        }
        // add tags
        if (is_string($tags)) {
            $mObj->tags = explode(",", $tags);
        } else if (is_array($tags)) {
            $mObj->tags = $tags;
        }
        // add class tags
        foreach ($this->tags as $elm) {
            array_push($mObj->tags, $elm);
        }
        // clear duplettes 
        $mObj->tags = array_unique($mObj->tags);

        if (isset($type)) {
            $mObj->etype = $type;
        } else if (isset($this->type)) {
            $mObj->etype = $this->type;
        } else {
            $mObj->etype = "";
        }
        unset($mObj->type);

        /** only one-field simple textmessages are supported, yet. See: 
         * https://github.com/petershaw/told-nodejs-client/blob/e699eb94db8014b78b7e2296b90c13bb08b39d2e/lib/toldclient.js
         * for an implementation of a full featured messsage imlementation
         */
        //$mObj->message = (object)( array('said' => $message) );
        // $mObj->message = json_decode (json_encode ( array('said' => $message)), FALSE);
        $mObj->message = $message;

        if ($this->debug)
            echo("Sending message: " . var_export($mObj, true));
        $curl = curl_init(rtrim($this->host, '/') . "/log");
        curl_setopt($curl, CURLOPT_VERBOSE, $this->debug);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($mObj));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($mObj))
        ));

        $result = curl_exec($curl);
        if ($this->debug)
            echo($result);

        return $mObj;
    }

}

?>