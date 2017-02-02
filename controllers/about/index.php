<?php

$css_link_tags = '<link href="/css/general.css" rel="stylesheet" />';
$header = ViewParser::parse('../views/common/header.php',array('link_tags_css'=>$css_link_tags));
$footer = ViewParser::parse('../views/common/footer.php',array());

include '../views/about.php';