<?php

namespace Heystack\Reports\Interfaces;

use SS_HTTPRequest;
use DataList;
use ArrayObject;
use FieldList;
use GridField;
use GridFieldConfig;
use CompositeField;

/**
 * Interface ReportModifierInterface
 * @package Heystack\Reports\Interfaces
 */
interface ReportModifierInterface
{
    /**
     * @param \DataList $dataList
     * @param array $filters
     * @param \SS_HTTPRequest $request
     * @return \DataList
     */
    public function modifyDataList(DataList $dataList, array $filters, SS_HTTPRequest $request);

    /**
     * @param \ArrayObject $columns
     * @return void
     */
    public function modifyColumns(ArrayObject $columns);

    /**
     * @param \FieldList $parameterFields
     * @param array $filters
     * @param \SS_HTTPRequest $request
     * @return void
     */
    public function modifyParameterFields(FieldList $parameterFields, array $filters, \SS_HTTPRequest $request);

    /**
     * @param \GridFieldConfig $gridFieldConfig
     * @param \GridField $gridField
     * @return void
     */
    public function modifyGridFieldConfig(GridFieldConfig $gridFieldConfig, GridField $gridField);

    /**
     * @param CompositeField $reportField
     * @return mixed
     */
    public function modifyReportFieldBefore(CompositeField $reportField);

    /**
     * @param CompositeField $reportField
     * @return mixed
     */
    public function modifyReportFieldAfter(CompositeField $reportField);

    /**
     * @return \Heystack\Core\Identifier\IdentifierInterface
     */
    public function getIdentifier();
}