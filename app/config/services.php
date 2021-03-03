<?php
Template::instance()->filter('length', 'Template_Helper::length');
Template::instance()->filter('convertDate', 'Date::convertUtcDateToTimeZone');
Template::instance()->filter('convertDateTime', 'Date::convertUtcTimestampToTimeZone');