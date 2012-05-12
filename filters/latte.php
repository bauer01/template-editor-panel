<?php

$filters[] = array(
    "LANGUAGE" => "latte",
    "MACROS" => array(
        1 => array(
            "START" => "{",
            "END" => "}",
            "STRICT" => false,
            "KEYWORDS" => array(
                'block', 'form', 'include', 'if', 'else', 'elseif',
                'ifset', 'ifcurrent', 'foreach', 'for', 'while', 'continueif',
                'breakif', 'var', 'debugbreak', 'snippet', '*',
                'first', 'last', 'sep', 'capture', 'cache', '?', 'syntax',
                'use', 'l', 'r', 'contenttype', 'status', 'includeblock', 'extends',
                'layout', 'link', 'plink', 'control', 'label', 'input', 'dump'
            ),
            "PROPERTIES" => array("=>"),
            "QUOTEMARKS" => array("'", '"'),
            "VARIABLES" => array(
                1 => array(
                    "START" => "$",
                    "END" => "[^a-zA-Z0-9_]"
                )
            )
        )
    ),
    "STYLE" => array(
        "QUOTEMARKS" => "color: #007e01;",
        "MACROS" => array(
            1 => "color: #fa3f38; font-weight: bold;"
        ),
        "PROPERTIES" => "color: #fa3f38;",
        "VARIABLES" => array(
            1 => "color: #e3a33b"
        )
    )
);
?>