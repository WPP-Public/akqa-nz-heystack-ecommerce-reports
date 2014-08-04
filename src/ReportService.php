<?php

namespace Heystack\Reports;

use Heystack\Core\Identifier\IdentifierInterface;

/**
 * @package Heystack\Reports
 */
class ReportService
{
    /**
     * @var \Heystack\Reports\Report[]
     */
    protected $reports = [];

    /**
     * @param \Heystack\Reports\Report $report
     * @return void
     */
    public function addReport(Report $report)
    {
        $this->reports[$report->getIdentifier()->getFull()] = $report;
    }

    /**
     * @return \Heystack\Reports\Report[]
     */
    public function getReports()
    {
        return $this->reports;
    }

    /**
     * @param \Heystack\Core\Identifier\IdentifierInterface $identifier
     * @return \Heystack\Reports\Report
     * @throws \InvalidArgumentException
     */
    public function getReport(IdentifierInterface $identifier)
    {
        if ($this->hasReport($identifier)) {
            return $this->reports[$identifier->getFull()];
        } else {
            throw new \InvalidArgumentException(sprintf("Report named '%s' doesn't exist", $identifier->getFull()));
        }
    }

    /**
     * @param \Heystack\Core\Identifier\IdentifierInterface $identifier
     * @return bool
     */
    public function hasReport(IdentifierInterface $identifier)
    {
        return isset($this->reports[$identifier->getFull()]);
    }
}