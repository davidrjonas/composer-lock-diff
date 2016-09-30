#!/usr/bin/env php
<?php

print "production packages\n===================\n";
print_pkgs(diff('packages'));

print "\ndev packages\n============\n";
print_pkgs(diff('packages-dev'));

function diff($key) {

    $pkgs = array();

    $lines = '';
    exec('git show HEAD:composer.lock', $lines);
    $data = json_decode(implode("\n", $lines));

    foreach($data->$key as $pkg) {
        $pkgs[$pkg->name] = array($pkg->version, 'REMOVED');
    }

    $data = json_decode(file_get_contents('composer.lock'));

    foreach($data->$key as $pkg) {
        if (! array_key_exists($pkg->name, $pkgs)) {
            $pkgs[$pkg->name] = array('NEW', $pkg->version);
            continue;
        }

        if ($pkgs[$pkg->name][0] == $pkg->version) {
            unset($pkgs[$pkg->name]);
        } else {
            $pkgs[$pkg->name][1] = $pkg->version;
        }
    }

    return $pkgs;
}

function print_pkgs($pkgs) {

    $pkg_width = max(array_map('strlen', array_keys($pkgs))) + 1;
    $before_width = max(array_map('strlen', array_map(function($v) { return $v[0]; }, $pkgs)));
    foreach($pkgs as $name => $v) {
        printf("%-{$pkg_width}s %-{$before_width}s => %s\n", $name, $v[0], $v[1]);
    }
}

