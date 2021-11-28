<?php

class ErrorMaker {
    public static function ErrorHeandler($msg, $code, $data = []) {
        http_response_code($code);
        echo json_encode( [
            'status' => false , 
            "error" => $msg,
            "code" => $code,
            "data" => $data
        ] );
        exit();
        
    }

}