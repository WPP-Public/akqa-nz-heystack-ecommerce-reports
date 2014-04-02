<?php

namespace Heystack\Reports\Modifiers;

use CompositeField;
use Heystack\Core\Identifier\Identifier;
use Heystack\Reports\Interfaces\ReportModifierInterface;

/**
 * @package Heystack\Reports\Modifiers
 */
class CreatedModifier implements ReportModifierInterface
{
    /**
     * @param \DataList $dataList
     * @param array $filters
     * @param \SS_HTTPRequest $request
     * @return \DataList
     */
    public function modifyDataList(\DataList $dataList, array $filters, \SS_HTTPRequest $request)
    {
        if (isset($filters['Range']) && $filters['Range']) {
            switch ($filters['Range']) {
                case 'Today':
                    $filters['StartDate'] = date('Y-m-d', strtotime('today'));
                    break;
                case 'Yesterday':
                    $filters['StartDate'] = date('Y-m-d', strtotime('yesterday'));
                    $filters['EndDate'] = date('Y-m-d', strtotime('yesterday'));
                    break;
                case 'Current Week':
                    $filters['StartDate'] = date('Y-m-d', strtotime('last Monday'));
                    break;
                case 'Last Week':
                    $week = strtotime('+1 week') - time();
                    $filters['StartDate'] = date('Y-m-d', strtotime('last Monday') - $week);
                    $filters['EndDate'] = date('Y-m-d', strtotime('Sunday') - $week);
                    break;
                case 'Current Month':
                    $filters['StartDate'] = date('Y-m-d', strtotime('first day of this month'));
                    $filters['EndDate'] = date('Y-m-d', strtotime('last day of this month'));
                    break;
                case 'Last Month':
                    $filters['StartDate'] = date('Y-m-d', strtotime('first day of last month'));
                    $filters['EndDate'] = date('Y-m-d', strtotime('last day of last month'));
                    break;
                case 'Current Year':
                    $filters['StartDate'] = date('Y-01-01');
                    $filters['EndDate'] = date('Y-12-31');
                    break;
                case 'All Time':
                    $filters['StartDate'] = date('Y-m-d', 0);
                    $filters['EndDate'] = date('Y-m-d', strtotime('today'));
                    break;
            }
        }

        if (isset($filters['StartDate']) && $filters['StartDate']) {
            $dataList = $dataList->filter(
                'Created:GreaterThanOrEqual',
                $filters['StartDate']
            );
        }

        if (isset($filters['EndDate']) && $filters['EndDate']) {
            $dataList = $dataList->filter(
                'Created:LessThanOrEqual',
                $filters['EndDate'] . ' 23:59:59'
            );
        }

        return $dataList;
    }

    /**
     * @param \ArrayObject $columns
     * @return void
     */
    public function modifyColumns(\ArrayObject $columns)
    {
    }

    /**
     * @param \FieldList $parameterFields
     * @param array $filters
     * @param \SS_HTTPRequest $request
     */
    public function modifyParameterFields(\FieldList $parameterFields, array $filters, \SS_HTTPRequest $request)
    {
        $parameterFields->push($start = new \DateField('StartDate'));
        $start->setConfig('showcalendar', true);
        $start->setConfig('dateformat', 'dd/MM/YYYY');

        $parameterFields->push($end = new \DateField('EndDate'));
        $end->setConfig('showcalendar', true);
        $end->setConfig('dateformat', 'dd/MM/YYYY');

        $parameterFields->push(
            new \DropdownField(
                'Range',
                'Range',
                [
                    '' => '-- Range --',
                    'Today' => 'Today',
                    'Yesterday' => 'Yesterday',
                    'Current Week' => 'Current Week',
                    'Last Week' => 'Last Week',
                    'Current Month' => 'Current Month',
                    'Last Month' => 'Last Month',
                    'Current Year' => 'Current Year',
                    'All Time' => 'All Time',
                ]
            )
        );
    }

    /**
     * @param \GridFieldConfig $gridFieldConfig
     * @param \GridField $gridField
     * @return void
     */
    public function modifyGridFieldConfig(\GridFieldConfig $gridFieldConfig, \GridField $gridField)
    {
    }

    /**
     * @param \CompositeField $reportField
     * @return mixed
     */
    public function modifyReportFieldBefore(CompositeField $reportField)
    {
    }

    /**
     * @param \CompositeField $reportField
     * @return mixed
     */
    public function modifyReportFieldAfter(CompositeField $reportField)
    {
    }

    /**
     * @return \Heystack\Core\Identifier\IdentifierInterface
     */
    public function getIdentifier()
    {
        return new Identifier('created');
    }
}