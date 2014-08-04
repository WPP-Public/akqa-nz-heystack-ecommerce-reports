<?php

namespace Heystack\Reports;

use Heystack\Core\Identifier\Identifier;
use Heystack\Reports\Interfaces\ReportModifierInterface;
use FieldList;
use LiteralField;
use FormAction;

/**
 * Class Report
 * @package Heystack\Reports
 */
class Report extends \SS_Report
{
    /**
     * @var \Heystack\Reports\Interfaces\ReportModifierInterface[]
     */
    protected $reportModifiers = [];

    /**
     * @var
     */
    protected $identifier;

    /**
     * @param string $identifier
     * @param string $dataClass
     * @param string $title
     * @param string|void $description
     */
    public function __construct(
        $identifier,
        $dataClass,
        $title,
        $description = null
    )
    {
        $this->identifier = $identifier;
        $this->dataClass = $dataClass;
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * @return \Heystack\Core\Identifier\IdentifierInterface
     */
    public function getIdentifier()
    {
        return new Identifier($this->identifier);
    }

    /**
     * @param null $action
     * @return string
     */
    public function getLink($action = null)
    {
        return \Controller::join_links(
            'admin/ecommerce-reports/',
            $this->getIdentifier()->getFull(),
            '/', // trailing slash needed if $action is null!
            (string)$action
        );
    }

    /**
     * @param \Heystack\Reports\Interfaces\ReportModifierInterface $reportModifier
     * @return void
     */
    public function addReportModifier(ReportModifierInterface $reportModifier)
    {
        $this->reportModifiers[$reportModifier->getIdentifier()->getFull()] = $reportModifier;
    }

    /**
     * @return array
     */
    public function columns()
    {
        $columns = new \ArrayObject();

        foreach ($this->reportModifiers as $reportData) {
            $reportData->modifyColumns($columns);
        }

        return $columns->getArrayCopy();
    }

    /**
     * @return \FieldList
     */
    public function parameterFields()
    {
        /** @var \FieldList $fields */
        $fields = \Injector::inst()->create('FieldList');
        $request = \Controller::curr()->getRequest();
        $filters = $request->requestVar('filters') ?: [];

        foreach ($this->reportModifiers as $reportData) {
            $reportData->modifyParameterFields($fields, $filters, $request);
        }

        return $fields->count() ? $fields : null;
    }

    /**
     * @param array $params
     * @return \DataList
     */
    public function sourceRecords($params)
    {
        /** @var \DataList $dataList */
        $dataList = \Injector::inst()->create('DataList', $this->dataClass);
        
        $request = \Controller::curr()->getRequest();
        $filters = $request->requestVar('filters') ?: [];

        foreach ($this->reportModifiers as $reportData) {
            if ($newDataList = $reportData->modifyDataList($dataList, $filters, $request)) {
                $dataList = $newDataList;
            }
        }

        return $dataList;
    }

    /**
     * @return \FormField
     */
    public function getReportField()
    {
        $compositeField = new \CompositeField();

        foreach ($this->reportModifiers as $reportData) {
            $reportData->modifyReportFieldBefore($compositeField);
        }
        
        if ($this->dataClass) {
            $field = parent::getReportField();

            if ($field instanceof \GridField) {
                $config = $field->getConfig();

                foreach ($this->reportModifiers as $reportData) {
                    $reportData->modifyGridFieldConfig($config, $field);
                }
            }

            $compositeField->push($field);
        }

        foreach ($this->reportModifiers as $reportData) {
            $reportData->modifyReportFieldAfter($compositeField);
        }
        
        return $compositeField;
    }

    /**
     * Copy logic but fix bugs
     * @return \FieldList
     */
    public function getCMSFields()
    {
        $fields = new FieldList();
        
        $fields->push(new LiteralField('ReportTitle', sprintf("<h2>%s</h2>", $this->title)));

        if ($this->description) {
            $fields->push(new LiteralField('ReportDescription', sprintf("<p>%s</p>", $this->description)));
        }

        // Add search fields is available
        if ($tmpFields = $this->parameterFields()) {
            /** @var \FormField $field  */
            foreach ($tmpFields as $field) {
                // Namespace fields for easier handling in form submissions
                $field->setName(sprintf('filters[%s]', $field->getName()));
                $field->addExtraClass('no-change-track'); // ignore in changetracker
                $fields->push($field);
            }

            // Add a search button
            $fields->push(new FormAction('updatereport', _t('GridField.Filter')));
        }

        $fields->push($this->getReportField());

        return $fields;
    }
}