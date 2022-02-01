<?php
namespace Rubu\Parduotuve;


class Messages {

    // type success danger warning info
    public static function add(string $type, string $msg) : void
    {
        if (!isset($_SESSION['msg'])) {
            $_SESSION['msg'] = [];
        }
        $_SESSION['msg'][] = ['type' => $type, 'msg' => $msg];
    }

    public static function get() : array
    {
        $msg = $_SESSION['msg'] ?? [];
        unset($_SESSION['msg']);
        return $msg;
    }


}