<?php

namespace Heystack\Reports;

use Heystack\Core\Identifier\Identifier;

/**
 * @package Heystack\Reports
 */
class EcommerceReportAdmin extends \ReportAdmin
{
    /**
     * @var string
     */
    private static $url_segment = 'ecommerce-reports';

    /**
     * @var string
     */
    private static $menu_title = 'Ecommerce Reports';

    /**
     * @var \Heystack\Reports\ReportService
     */
    protected $reportService;

    /**
     * @param \Heystack\Reports\ReportService|void $reportService
     */
    public function __construct(ReportService $reportService = null)
    {
        $this->reportService = $reportService;
        parent::__construct();
    }

    /**
     *
     */
    public function init()
    {
        parent::init();
        
        if ($this->reportService) {
            $identifier = new Identifier($this->reportClass);
            if ($this->reportService->hasReport($identifier)) {
                $this->reportObject = $this->reportService->getReport($identifier);
            }
        }
    }

    /**
     * @return \ArrayList
     */
    public function Reports()
    {
        if ($this->reportService) {
            return new \ArrayList($this->reportService->getReports());
        } else {
            return new \ArrayList();
        }
    }

    /**
     * @param bool $unlinked
     * @return \ArrayList
     */
    public function Breadcrumbs($unlinked = false)
    {
        $items = parent::Breadcrumbs($unlinked);
        
        $items[0]->Link = '/admin/' . self::$url_segment . '/';

        return $items;
    }
}