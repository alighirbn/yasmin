<?php

namespace App\DataTables;

use App\Models\Payment\Payment;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PaymentDataTable extends DataTable
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
            ->addColumn('action', 'payment.action')
            ->addColumn('payment_amount', function ($row) {
                return number_format($row->payment_amount, 0);
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
    public function query(Payment $model): QueryBuilder
    {
        return $model->newQuery()->with(['contract.customer', 'contract.building', 'contract_installment.installment']);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('payment-table')
            ->language([
                'sUrl' =>  url('/') . '/../lang/' . __(LaravelLocalization::getCurrentLocale()) . '/datatable.json'
            ])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            /*   ->parameters([
            'dom' => 'B<"clear">lfrtip',
            'scrollX' => false,
            'buttons' => [
                [
                    'extend'  => 'print',
                    'className'    => 'btn btn-outline-dark'
               ],
               [
                    'extend'  => 'reset',
                    'className'    => 'btn btn-outline-dark'
               ],
               [
                    'extend'  => 'reload',
                    'className'    => 'btn btn-outline-dark'
               ],
                [
                     'extend'  => 'export',
                     'className'    => 'btn btn-outline-dark',
                     'buttons' => [
                                       'csv',
                                       'excel',
                                       'pdf',
                                  ],
                ],
                'colvis'
            ]
        ]) */
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
            Column::make('id')->title(__('word.payment_id'))->class('text-center'),
            Column::make('payment_date')->title(__('word.payment_date'))->class('text-center'),

            Column::make('contract_id')->title(__('word.contract_id'))->data('contract.id')->name('contract.id')->class('text-center'),
            Column::make('contract_date')->title(__('word.contract_date'))->data('contract.contract_date')->name('contract.contract_date')->class('text-center'),
            Column::make('building_number')->title(__('word.building_number'))->data('contract.building.building_number')->name('contract.building.building_number')->class('text-center'),

            Column::make('customer_full_name')->title(__('word.customer_full_name'))->data('contract.customer.customer_full_name')->name('contract.customer.customer_full_name')->class('text-center'),
            Column::make('installment_name')->title(__('word.installment_name'))->data('contract_installment.installment.installment_name')->name('contract_installment.installment.installment_name')->class('text-center'),

            Column::make('payment_amount')->title(__('word.payment_amount'))->class('text-center'),

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Payment_' . date('YmdHis');
    }
}
