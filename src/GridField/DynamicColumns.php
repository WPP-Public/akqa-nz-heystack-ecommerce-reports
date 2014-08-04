<?php

namespace Heystack\Reports\GridField;

use DataObject;
use GridField;

/**
 * @package Heystack\Reports\GridField
 */
class DynamicColumns implements \GridField_ColumnProvider
{
    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var array
     */
    protected $columnTitles = [];

    /**
     * @var array
     */
    protected $functions = [];

    /**
     * @param string $column
     * @param string $title
     * @param callable $function
     * @return \Heystack\Reports\GridField\DynamicColumns
     */
    public function addColumn($column, $title, callable $function)
    {
        $this->columns[] = $column;
        $this->columnTitles[$column] = $title;
        $this->functions[$column] = $function;
        
        return $this;
    }

    /**
     * Modify the list of columns displayed in the table.
     *
     * @see {@link GridFieldDataColumns->getDisplayFields()}
     * @see {@link GridFieldDataColumns}.
     *
     * @param \GridField $gridField
     * @param array - List reference of all column names.
     * @return void
     */
    public function augmentColumns($gridField, &$columns)
    {
        foreach ($this->columns as $column) {
            $columns[] = $column;
        }
    }

    /**
     * Names of all columns which are affected by this component.
     *
     * @param \GridField $gridField
     * @return array
     */
    public function getColumnsHandled($gridField)
    {
        return $this->columns;
    }

    /**
     * HTML for the column, content of the <td> element.
     *
     * @param  GridField $gridField
     * @param  DataObject $record - Record displayed in this row
     * @param  string $columnName
     * @return string - HTML for the column. Return NULL to skip.
     */
    public function getColumnContent($gridField, $record, $columnName)
    {
        return $this->functions[$columnName]($record, $gridField);
    }

    /**
     * Attributes for the element containing the content returned by {@link getColumnContent()}.
     *
     * @param  GridField $gridField
     * @param  DataObject $record displayed in this row
     * @param  string $columnName
     * @return array
     */
    public function getColumnAttributes($gridField, $record, $columnName)
    {
        return ['class' => 'col-' . preg_replace('/[^\w]/', '-', $columnName)];
    }

    /**
     * Additional metadata about the column which can be used by other components,
     * e.g. to set a title for a search column header.
     *
     * @param \GridField $gridField
     * @param string $columnName
     * @return array - Map of arbitrary metadata identifiers to their values.
     */
    public function getColumnMetadata($gridField, $columnName)
    {
        return [
            'title' => $this->columnTitles[$columnName],
        ];
    }
}