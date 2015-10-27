<?php

function dateToDbFormat($date) {
    if (!empty($date)) {
        list($dd, $mm, $yy) = explode("/", $date);
        return $yy . "-" . $mm . "-" . $dd;
    }
}

function dateToUserFormat($date) {
    if (!empty($date)) {
        list($yy, $mm, $dd) = explode("-", $date);
        return $dd . "/" . $mm . "/" . $yy;
    }
}

function yearFromDate($date) {
    if (!empty($date)) {
        list($dd, $mm, $yy) = explode("/", $date);
        return $yy;
    }
}

function monthFromDate($date) {
    if (!empty($date)) {
        list($dd, $mm, $yy) = explode("/", $date);
        return $mm;
    }
}

function dateFormat($date, $string, $format) {
    $d = new DateTime($date);
    $d->modify($string);
    return $d->format($format);
}

function pr($data, $exit = true) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";

    if ($exit == true) {
        exit;
    }
}

// $url should be an absolute url
function redirect($url) {
    if (headers_sent()) {
        die('<script type="text/javascript">window.location.href="' . $url . '";</script>');
    } else {
        header('Location: ' . $url);
        die();
    }
}

?>