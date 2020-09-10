<?php
    /**
    * Escapes a string so it can be safely echo'ed out as Javascript
    *
    * @param  string $str String to escape
    * @return string      JS Safe string
    */
    function EscapeString($str)
    {
        $str = str_replace(array('\\', "'"), array("\\\\", "\\'"), $str);
        $str = preg_replace('#([\x00-\x1F])#e', '"\x" . sprintf("%02x", ord("\1"))', $str);

        return $str;
    }

    function multi_array_to_string($inArray) {
        ob_start();
        echo "<pre>";
        print_r($inArray);
        echo "</pre>";
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
?>