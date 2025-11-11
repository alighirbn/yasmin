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
            ->addColumn('service_amount', fn($row) => number_format($row->service_amount, 0))

            // ✅ Arabic + colored contract stage badge
            ->addColumn('contract_stage', function ($row) {
                if (!$row->contract || !$row->contract->stage) {
                    return '<span class="text-gray-500">غير محدد</span>';
                }

                $stage = $row->contract->stage;

                // Arabic labels
                $labels = [
                    'temporary'     => 'حجز اولي',
                    'accepted'      => 'مقبول',
                    'authenticated' => 'مصادق',
                    'terminated'    => 'فسخ',
                ];

                // Tailwind color classes
                $colors = [
                    'temporary'     => 'bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold',
                    'accepted'      => 'bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold',
                    'authenticated' => 'bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold',
                    'terminated'    => 'bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold',
                ];

                $label = $labels[$stage] ?? $stage;
                $color = $colors[$stage] ?? 'bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold';

                return "<span class='{$color}'>{$label}</span>";
            })

            // ✅ Filtering rules
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
            ->filterColumn('contract.stage', function ($query, $keyword) {
                $query->whereHas('contract', function ($q) use ($keyword) {
                    $q->where('stage', 'like', "%{$keyword}%");
                });
            })

            // ✅ Keep HTML rendering for badges & actions
            ->setRowId('id')
            ->rawColumns(['action', 'contract_stage']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Service $model): QueryBuilder
    {
        return $model->newQuery()->with([
            'contract.customer',
            'contract.building',
            'contract.payment_method',
            'service_type'
        ]);
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('service-table')
            ->language([
                'sUrl' => url('/') . '/../lang/' . __(LaravelLocalization::getCurrentLocale()) . '/datatable.json'
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
                    [
                        'extend'  => 'export',
                        'className'    => 'btn btn-outline-dark',
                        'buttons' => [
                            'excel',
                        ],
                    ],
                    'colvis'
                ]
            ])
            ->selectStyleSingle();
    }

    /**
     * Get the dataTable columns definition.
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

            Column::make('contract_id')
                ->title(__('word.contract_id'))
                ->data('contract.id')
                ->name('contract.id')
                ->class('text-center'),

            Column::make('contract_date')
                ->title(__('word.contract_date'))
                ->data('contract.contract_date')
                ->name('contract.contract_date')
                ->class('text-center'),

            Column::make('building_number')
                ->title(__('word.building_number'))
                ->data('contract.building.building_number')
                ->name('contract.building.building_number')
                ->class('text-center'),

            Column::make('customer_full_name')
                ->title(__('word.customer_full_name'))
                ->data('contract.customer.customer_full_name')
                ->name('contract.customer.customer_full_name')
                ->class('text-center'),

            Column::make('service_type')
                ->title(__('word.service_type'))
                ->data('service_type.type_name')
                ->name('service_type.type_name')
                ->class('text-center'),

            // ✅ New Arabic + colored contract stage
            Column::computed('contract_stage')
                ->title(__('word.stage'))
                ->class('text-center'),

            Column::make('service_amount')->title(__('word.service_amount'))->class('text-center'),
            Column::make('service_note')->title(__('word.service_note'))->class('text-center'),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'Service_' . date('YmdHis');
    }
}
