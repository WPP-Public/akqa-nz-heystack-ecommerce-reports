<?php

define('ECOMMERCE_REPORTS_BASE_PATH', __DIR__);

// ------------------------------- CHARTS ----------------------------------- //

ReportAdmin::require_javascript(THIRDPARTY_DIR . "/jquery-metadata/jquery.metadata.js");
ReportAdmin::require_javascript(FRAMEWORK_DIR . "/javascript/DateField.js");

// TODO: Replace
//ReportAdmin::require_javascript(THIRDPARTY_DIR . '/jquery-livequery/jquery.livequery.js');
//ReportAdmin::require_javascript('ecommerce-reports/code/SilverStripe/themes/ecommerce/js/flot/jquery.flot.js');
//ReportAdmin::require_javascript('ecommerce-reports/code/SilverStripe/themes/ecommerce/js/charts.js');

ReportAdmin::require_css('ecommerce-reports/code/SilverStripe/themes/ecommerce/css/report.css');

