<?php

namespace App\DataTables;

use App\Models\Customer\Customer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CustomerDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'customer.action')
            ->rawColumns(['action'])
            ->filterColumn('customer_full_name', function ($query, $keyword) {
                $query->where('customer_full_name', 'like', "%{$keyword}%");
            })
            ->filterColumn('customer_phone', function ($query, $keyword) {
                $query->where('customer_phone', 'like', "%{$keyword}%");
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Customer $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Customer $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('customer-table')
            ->language([
                'sUrl' =>  url('/') . '/../lang/' . __(LaravelLocalization::getCurrentLocale()) . '/datatable.json'
            ])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)


            ->selectStyleSingle();
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->title(__('word.action'))
                ->addClass('text-center'),
            Column::make('customer_full_name')->title(__('word.customer_full_name'))->class('text-center'),
            Column::make('customer_phone')->title(__('word.customer_phone'))->class('text-center'),
            Column::make('customer_email')->title(__('word.customer_email'))->class('text-center'),
            Column::make('customer_card_number')->title(__('word.customer_card_number'))->class('text-center'),
            Column::make('customer_card_issud_auth')->title(__('word.customer_card_issud_auth'))->class('text-center'),
            Column::make('customer_card_issud_date')->title(__('word.customer_card_issud_date'))->class('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Customer_' . date('YmdHis');
    }
}
