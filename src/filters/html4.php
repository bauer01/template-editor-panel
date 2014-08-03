<?php

$filters[] = array(
    "LANGUAGE" => "html4",
    "MACROS" => array(
        1 => array(
            "START" => "<!--",
            "END" => "-->",
            "STRICT" => true,
            "KEYWORDS" => array(),
            "PROPERTIES" => array(),
        ),
        2 => array(
            "START" => "<!DOCTYPE",
            "END" => ">",
            "STRICT" => true,
            "KEYWORDS" => array(),
            "PROPERTIES" => array(),
        ),
        3 => array(
            "START" => "<![CDATA[",
            "END" => "]]>",
            "STRICT" => true,
            "KEYWORDS" => array(),
            "PROPERTIES" => array(),
        ),
        4 => array(
            "START" => "<",
            "END" => ">",
            "STRICT" => false,
            "KEYWORDS" => array(
                'a', 'abbr', 'acronym', 'address', 'applet',
                'base', 'basefont', 'bdo', 'big', 'blockquote', 'body', 'br', 'button', 'b',
                'caption', 'center', 'cite', 'code', 'colgroup', 'col',
                'dd', 'del', 'dfn', 'dir', 'div', 'dl', 'dt',
                'em',
                'fieldset', 'font', 'form', 'frame', 'frameset',
                'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'hr', 'html',
                'iframe', 'ilayer', 'img', 'input', 'ins', 'isindex', 'i',
                'kbd',
                'label', 'legend', 'link', 'li',
                'map', 'meta',
                'noframes', 'noscript',
                'object', 'ol', 'optgroup', 'option',
                'param', 'pre', 'p',
                'q',
                'samp', 'script', 'select', 'small', 'span', 'strike', 'strong', 'style', 'sub', 'sup', 's',
                'table', 'tbody', 'td', 'textarea', 'text', 'tfoot', 'thead', 'th', 'title', 'tr', 'tt',
                'ul', 'u',
                'var'
            ),
            "PROPERTIES" => array(
                'abbr=', 'accept-charset=', 'accept=', 'accesskey=', 'action=', 'align=', 'alink=', 'alt=', 'archive=', 'axis=',
                'background=', 'bgcolor=', 'border=',
                'cellpadding=', 'cellspacing=', 'char', 'charoff', 'charset', 'checked', 'cite', 'class=', 'classid', 'clear', 'code', 'codebase', 'codetype', 'color', 'cols=', 'colspan=', 'compact', 'content', 'coords',
                'data=', 'datetime', 'declare', 'defer', 'dir', 'disabled',
                'enctype',
                'face', 'for', 'frame=', 'frameborder',
                'headers', 'height', 'href', 'hreflang', 'hspace', 'http-equiv',
                'id=', 'ismap',
                'label=', 'lang', 'language', 'link=', 'longdesc',
                'marginheight', 'marginwidth', 'maxlength', 'media', 'method', 'multiple',
                'name=', 'nohref', 'noresize', 'noshade', 'nowrap',
                'object', 'onblur=', 'onchange=', 'onclick=', 'ondblclick=', 'onfocus', 'onkeydown=', 'onkeypress=', 'onkeyup=', 'onload=', 'onmousedown=', 'onmousemove=', 'onmouseout=', 'onmouseover=', 'onmouseup=', 'onreset=', 'onselect=', 'onsubmit=', 'onunload=',
                'profile', 'prompt',
                'readonly', 'rel=', 'rev', 'rowspan=', 'rows=', 'rules',
                'scheme', 'scope', 'scrolling', 'selected', 'shape', 'size=', 'span', 'src=', 'standby', 'start', 'style=', 'summary',
                'tabindex', 'target=', 'text=', 'title=', 'type=',
                'usemap',
                'valign=', 'value=', 'valuetype', 'version', 'vlink', 'vspace',
                'width='
            ),
            "QUOTEMARKS" => array("'", '"')
        )
    ),
    "STYLE" => array(
        "QUOTEMARKS" => "color: #007e01;",
        "PROPERTIES" => "color: #1122CC;",
        "MACROS" => array(
            1 => "color: #999999;",
            2 => "color: #8b8ce6;",
            4 => "color: #1122CC; font-weight: bold;"
        )
    )
);
