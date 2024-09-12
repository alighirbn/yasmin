<?php

namespace App\DataTables;

use App\Models\Cash\Cash_Account;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CashAccountDataTable extends DataTable
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
            ->addColumn('action', 'expense.action')
            ->addColumn('balance', function ($row) {
                return number_format($row->balance, 0);
            })
            ->rawColumns(['action'])
            ->setRowId('url_address'); // Using url_address as the row ID
    }

    protected $urlAddress;

    // New method to set the url_address
    public function forUrlAddress($urlAddress = null)
    {
        $this->urlAddress = $urlAddress;
        return $this;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Cash\Expense $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Cash_Account $model): QueryBuilder
    {
        // Get the base query with relationships (if any)
        $query = $model->newQuery();

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
            ->setTableId('cashaccount-table')
            ->language([
                'sUrl' => url('/') . '/../lang/' . __(LaravelLocalization::getCurrentLocale()) . '/datatable.json'
            ])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'asc')
            /* ->parameters([
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
            Column::make('id')->title(__('word.cash_account_id'))->class('text-center'),
            Column::make('account_name')->title(__('word.account_name'))->class('text-center'),
            Column::make('balance')->title(__('word.balance'))->class('text-center'),

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'CashAccount_' . date('YmdHis');
    }
}
