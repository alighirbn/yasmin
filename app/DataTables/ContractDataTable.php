<?php

namespace App\DataTables;

use App\Models\Contract\Contract;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ContractDataTable extends DataTable
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
            ->addColumn('contract_amount', function ($row) {
                return number_format($row->contract_amount, 0);
            })
            ->addColumn('stage', function ($row) {
                return __('word.' . $row->stage);
            })
            ->addColumn('last_payment', function ($row) {
                // Get the last approved payment
                $lastApprovedPayment = $row->payments->where('approved', true)->last();
                return $lastApprovedPayment
                    ? number_format($lastApprovedPayment->payment_amount, 0) . ' في ' . $lastApprovedPayment->payment_date
                    : __('لا توجد دفعة معتمدة');
            })
            ->addColumn('action', 'contract.contract.action')
            ->rawColumns(['action'])
            ->setRowId('id');
    }


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Contract $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Contract $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['building', 'customer', 'payment_method', 'payments.contract_installment.installment']);

        // Check if a contract ID filter is applied
        if ($contractId = request('contract_id')) {
            $query->where('id', $contractId);
        }

        return $query;
    }


    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('contract-table')
            ->language([
                'sUrl' =>  url('/') . '/../lang/' . __(LaravelLocalization::getCurrentLocale()) . '/datatable.json'
            ])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'asc')
            ->parameters([
                'dom' => 'B<"clear">lfrtip',
                'scrollX' => false,
                'buttons' => [
                    [
                        'extend'  => 'print',
                        'className'    => 'btn btn-outline-dark'
                    ],
                    /* [
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
                    ],*/
                    'colvis'
                ]
            ])
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
            Column::make('id')->title(__('word.id'))->class('text-center'),
            Column::make('contract_date')->title(__('word.contract_date'))->class('text-center'),
            Column::make('building')->title(__('word.building_number'))->data('building.building_number')->name('building.building_number')->class('text-center'),
            Column::make('customer')->title(__('word.customer_full_name'))->data('customer.customer_full_name')->name('customer.customer_full_name')->class('text-center'),
            Column::make('contract_amount')->title(__('word.contract_amount'))->class('text-center'),
            Column::make('payment_method')->title(__('word.method_name'))->data('payment_method.method_name')->name('payment_method.method_name')->class('text-center'),
            Column::make('last_payment')->title(__('word.last_payment'))->class('text-center'), // New Column
            Column::make('contract_note')->title(__('word.contract_note'))->class('text-center'),
            Column::make('stage')->title(__('word.stage'))->class('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Contract_' . date('YmdHis');
    }
}
