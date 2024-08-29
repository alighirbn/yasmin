<?php


namespace App\DataTables;

use App\Models\Payment\Service;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ServiceDataTable extends DataTable
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
            ->addColumn('action', 'service.action')
            ->filterColumn('contract.customer.customer_full_name', function ($query, $keyword) {
                $query->whereHas('contract.customer', function ($query) use ($keyword) {
                    $query->where('customer_full_name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('contract.building.building_number', function ($query, $keyword) {
                $query->whereHas('contract.building', function ($query) use ($keyword) {
                    $query->where('building_number', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('service_date', function ($query, $keyword) {
                $query->where('service_date', 'like', "%{$keyword}%");
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Payment $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Service $model): QueryBuilder
    {
        return $model->newQuery()->with(['contract.customer', 'contract.building']);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('service-table')
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
            Column::make('id')->title(__('word.service_id'))->class('text-center'),
            Column::make('service_date')->title(__('word.service_date'))->class('text-center'),

            Column::make('contract_id')->title(__('word.contract_id'))->data('contract.id')->name('contract.id')->class('text-center'),
            Column::make('contract_date')->title(__('word.contract_date'))->data('contract.contract_date')->name('contract.contract_date')->class('text-center'),
            Column::make('building_number')->title(__('word.building_number'))->data('contract.building.building_number')->name('contract.building.building_number')->class('text-center'),

            Column::make('customer_full_name')->title(__('word.customer_full_name'))->data('contract.customer.customer_full_name')->name('contract.customer.customer_full_name')->class('text-center'),
            Column::make('service_amount')->title(__('word.service_amount'))->class('text-center'),
            Column::make('service_note')->title(__('word.service_note'))->class('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Service' . date('YmdHis');
    }
}
