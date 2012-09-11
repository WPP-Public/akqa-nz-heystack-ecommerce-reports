<?php

define('ECOMMERCE_REPORTS_BASE_PATH', __DIR__);



// ------------------------------- REPORTS ---------------------------------- //
SS_Report::register("ReportAdmin", "SalesReport");
SS_Report::register("ReportAdmin", "TransactionReport");

// ------------------------------- CHARTS ----------------------------------- //

ReportAdmin::require_javascript(THIRDPARTY_DIR . "/jquery-metadata/jquery.metadata.js");
ReportAdmin::require_javascript(SAPPHIRE_DIR . "/javascript/DateField.js");
ReportAdmin::require_javascript(THIRDPARTY_DIR . '/jquery-livequery/jquery.livequery.js');
ReportAdmin::require_javascript('ecommerce-reports/code/SilverStripe/themes/ecommerce/js/flot/jquery.flot.js');
ReportAdmin::require_javascript('ecommerce-reports/code/SilverStripe/themes/ecommerce/js/charts.js');

ReportAdmin::require_css('ecommerce-reports/code/SilverStripe/themes/ecommerce/css/report.css');