<?php

$cf = dirname(__DIR__) . '/public/assets/layui/css/layui.css';

$originColors = [
    '#16baaa',
    '#16b777',
    '#ff5722',
];

$settings = [
    'blue' => [
        'search' => $originColors,
        'replace' => '#0052D9', // blue
    ],
    'red' => [
        'search' => $originColors,
        'replace' => '#FF2832', // red
    ],
];

if (file_exists($cf)) {
    $content = file_get_contents($cf);

    foreach ($settings as $type => $v) {
        $c = str_replace($v['search'], $v['replace'], $content);
        $f = str_replace('layui.css', $type.'.css', $cf);
        file_put_contents($f, $c);
    }
}
