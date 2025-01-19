<?php

namespace KpEsportes\App\Util;

class File {

    public static function save(string $tmp_name, string $save_name, string $save_directory = __DIR__ . "/../Storage/uploads/products/") {
        $save_directory = $save_directory . explode(".", $save_name)[0];

        mkdir($save_directory, 0777, true);
        move_uploaded_file($tmp_name, $save_directory . "/" . $save_name);
    }

}