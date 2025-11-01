<x-app-layout>
    <x-slot name="header">

        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <style>
            :root {
                --primary: #4361ee;
                --secondary: #3f37c9;
                --success: #4cc9f0;
                --info: #4895ef;
                --warning: #f72585;
                --danger: #e63946;
                --light: #f8f9fa;
                --dark: #212529;
                --gray: #6c757d;
                --gray-light: #e9ecef;
                --border-radius: 12px;
                --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                --transition: all 0.3s ease;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Cairo', sans-serif;
            }

            body {
                background-color: #f5f7fb;
                color: #333;
                direction: rtl;
            }

            .dashboard {
                padding: 20px;
                max-width: 1400px;
                margin: 0 auto;
            }

            .dashboard-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 24px;
                padding: 16px 24px;
                background: linear-gradient(135deg, var(--primary), var(--secondary));
                border-radius: var(--border-radius);
                color: white;
                box-shadow: var(--shadow);
            }

            .dashboard-header h1 {
                font-size: 1.5rem;
                font-weight: 700;
            }

            .date-display {
                background: rgba(255, 255, 255, 0.2);
                padding: 8px 16px;
                border-radius: 30px;
                font-size: 0.9rem;
                font-weight: 500;
            }

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 20px;
                margin-bottom: 24px;
            }

            .stat-card {
                background: white;
                border-radius: var(--border-radius);
                padding: 20px;
                box-shadow: var(--shadow);
                transition: var(--transition);
                position: relative;
                overflow: hidden;
            }

            .stat-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
            }

            .stat-card::before {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                width: 100%;
                height: 4px;
                background: var(--primary);
            }

            .stat-card.contracts::before {
                background: var(--primary);
            }

            .stat-card.payments::before {
                background: var(--success);
            }

            .stat-card.services::before {
                background: var(--info);
            }

            .stat-card.installments::before {
                background: var(--warning);
            }

            .stat-card-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .stat-info h3 {
                font-size: 0.9rem;
                color: var(--gray);
                margin-bottom: 8px;
                font-weight: 500;
            }

            .stat-value {
                font-size: 1.8rem;
                font-weight: 700;
                margin-bottom: 4px;
            }

            .stat-detail {
                font-size: 0.8rem;
                color: var(--gray);
            }

            .stat-icon {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.8rem;
            }

            .stat-card.contracts .stat-icon {
                background: rgba(67, 97, 238, 0.1);
                color: var(--primary);
            }

            .stat-card.payments .stat-icon {
                background: rgba(76, 201, 240, 0.1);
                color: var(--success);
            }

            .stat-card.services .stat-icon {
                background: rgba(72, 149, 239, 0.1);
                color: var(--info);
            }

            .stat-card.installments .stat-icon {
                background: rgba(247, 37, 133, 0.1);
                color: var(--warning);
            }

            .charts-section {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
                margin-bottom: 24px;
            }

            .chart-card {
                background: white;
                border-radius: var(--border-radius);
                padding: 20px;
                box-shadow: var(--shadow);
            }

            .chart-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 16px;
            }

            .chart-title {
                font-size: 1rem;
                font-weight: 600;
                color: var(--dark);
                display: flex;
                align-items: center;
            }

            .chart-title i {
                margin-left: 8px;
                font-size: 1.2rem;
            }

            .chart-container {
                height: 200px;
                position: relative;
            }

            .summary-cards {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 20px;
                margin-bottom: 24px;
            }

            .summary-card {
                border-radius: var(--border-radius);
                padding: 20px;
                color: white;
                box-shadow: var(--shadow);
                position: relative;
                overflow: hidden;
            }

            .summary-card::before {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.1);
                z-index: 0;
            }

            .summary-card.weekly {
                background: linear-gradient(135deg, var(--primary), var(--secondary));
            }

            .summary-card.monthly {
                background: linear-gradient(135deg, var(--success), #4361ee);
            }

            .summary-card.accounts {
                background: linear-gradient(135deg, #7209b7, #3a0ca3);
            }

            .summary-card.profit {
                background: linear-gradient(135deg, #f72585, #b5179e);
            }

            .summary-card-content {
                position: relative;
                z-index: 1;
            }

            .summary-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 16px;
            }

            .summary-title {
                font-size: 1rem;
                font-weight: 600;
            }

            .summary-icon {
                font-size: 1.5rem;
                opacity: 0.8;
            }

            .summary-stats {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .summary-stat {
                display: flex;
                justify-content: space-between;
                font-size: 0.9rem;
            }

            .summary-value {
                font-weight: 600;
            }

            .summary-total {
                font-size: 1.5rem;
                font-weight: 700;
                margin: 8px 0;
            }

            .summary-label {
                font-size: 0.8rem;
                opacity: 0.9;
            }

            .activities-section {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
                margin-bottom: 24px;
            }

            .activity-card {
                background: white;
                border-radius: var(--border-radius);
                padding: 20px;
                box-shadow: var(--shadow);
            }

            .activity-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 16px;
            }

            .activity-title {
                font-size: 1rem;
                font-weight: 600;
                color: var(--dark);
                display: flex;
                align-items: center;
            }

            .activity-title i {
                margin-left: 8px;
                font-size: 1.2rem;
            }

            .activity-count {
                background: var(--gray-light);
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 0.8rem;
                color: var(--gray);
            }

            .activity-list {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .activity-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px;
                background: var(--light);
                border-radius: 8px;
                transition: var(--transition);
            }

            .activity-item:hover {
                background: var(--gray-light);
            }

            .activity-info {
                flex: 1;
                margin-left: 12px;
            }

            .activity-name {
                font-weight: 600;
                font-size: 0.9rem;
                margin-bottom: 4px;
            }

            .activity-details {
                font-size: 0.8rem;
                color: var(--gray);
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .activity-badge {
                padding: 2px 8px;
                border-radius: 12px;
                font-size: 0.7rem;
                font-weight: 500;
            }

            .badge-temporary {
                background: rgba(234, 179, 8, 0.1);
                color: #ca8a04;
            }

            .badge-accepted {
                background: rgba(59, 130, 246, 0.1);
                color: #1d4ed8;
            }

            .badge-authenticated {
                background: rgba(34, 197, 94, 0.1);
                color: #15803d;
            }

            .activity-amount {
                font-weight: 700;
                font-size: 0.9rem;
            }

            .payment-amount {
                color: var(--success);
            }

            .contract-amount {
                color: var(--primary);
            }

            .footer-stats {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 16px;
            }

            .footer-stat {
                background: white;
                border-radius: var(--border-radius);
                padding: 16px;
                box-shadow: var(--shadow);
                text-align: center;
                transition: var(--transition);
            }

            .footer-stat:hover {
                transform: translateY(-3px);
            }

            .footer-icon {
                font-size: 2rem;
                margin-bottom: 8px;
            }

            .footer-stat.contracts .footer-icon {
                color: var(--primary);
            }

            .footer-stat.employees .footer-icon {
                color: var(--info);
            }

            .footer-stat.pending .footer-icon {
                color: var(--warning);
            }

            .footer-label {
                font-size: 0.8rem;
                color: var(--gray);
                margin-bottom: 4px;
            }

            .footer-value {
                font-size: 1.2rem;
                font-weight: 700;
                color: var(--dark);
            }

            @media (max-width: 768px) {
                .dashboard {
                    padding: 12px;
                }

                .dashboard-header {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 12px;
                }

                .stats-grid,
                .charts-section,
                .summary-cards,
                .activities-section {
                    grid-template-columns: 1fr;
                }

                .footer-stats {
                    grid-template-columns: repeat(3, 1fr);
                }
            }
        </style>

        <h2 class="font-bold text-2xl md:text-3xl mb-2 flex items-center gap-2">
            <i class='bx bxs-dashboard text-3xl'></i>
            مرحباً، {{ auth()->user()->name }}
        </h2>

    </x-slot>

    </head>

    <div class="dashboard">

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card contracts">
                <div class="stat-card-content">
                    <div class="stat-info">
                        <h3>عقود جديدة</h3>
                        <div class="stat-value">{{ $contractsToday }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class='bx bx-file'></i>
                    </div>
                </div>
            </div>

            <div class="stat-card payments">
                <div class="stat-card-content">
                    <div class="stat-info">
                        <h3>مدفوعات اليوم</h3>
                        <div class="stat-value">{{ $paymentsToday }}</div>
                        <div class="stat-detail">{{ number_format($paymentsTodayAmount / 1000, 0) }}K د.ع</div>
                    </div>
                    <div class="stat-icon">
                        <i class='bx bx-money'></i>
                    </div>
                </div>
            </div>

            <div class="stat-card services">
                <div class="stat-card-content">
                    <div class="stat-info">
                        <h3>خدمات اليوم</h3>
                        <div class="stat-value">{{ $servicesToday }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class='bx bx-cog'></i>
                    </div>
                </div>
            </div>

            <div class="stat-card installments">
                <div class="stat-card-content">
                    <div class="stat-info">
                        <h3>أقساط مستحقة</h3>
                        <div class="stat-value">{{ $dueInstallmentsToday }}</div>
                        <div class="stat-detail">{{ number_format($dueInstallmentsTodayAmount / 1000, 0) }}K د.ع</div>
                    </div>
                    <div class="stat-icon">
                        <i class='bx bx-time'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class='bx bx-line-chart'></i>
                        <span>المدفوعات - آخر 7 أيام</span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="paymentsChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class='bx bx-bar-chart'></i>
                        <span>العقود - آخر 7 أيام</span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="contractsChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class='bx bx-pie-chart-alt-2'></i>
                        <span>العقود حسب المرحلة</span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="stagesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="summary-card weekly">
                <div class="summary-card-content">
                    <div class="summary-header">
                        <div class="summary-title">إحصائيات الأسبوع</div>
                        <div class="summary-icon">
                            <i class='bx bx-calendar-week'></i>
                        </div>
                    </div>
                    <div class="summary-stats">
                        <div class="summary-stat">
                            <span>عقود</span>
                            <span class="summary-value">{{ $contractsWeek }}</span>
                        </div>
                        <div class="summary-stat">
                            <span>مدفوعات</span>
                            <span class="summary-value">{{ number_format($paymentsWeekAmount / 1000, 0) }}K</span>
                        </div>
                        <div class="summary-stat">
                            <span>خدمات</span>
                            <span class="summary-value">{{ $servicesWeek }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="summary-card monthly">
                <div class="summary-card-content">
                    <div class="summary-header">
                        <div class="summary-title">إحصائيات الشهر</div>
                        <div class="summary-icon">
                            <i class='bx bx-calendar'></i>
                        </div>
                    </div>
                    <div class="summary-stats">
                        <div class="summary-stat">
                            <span>عقود</span>
                            <span class="summary-value">{{ $contractsMonth }}</span>
                        </div>
                        <div class="summary-stat">
                            <span>مدفوعات</span>
                            <span class="summary-value">{{ number_format($paymentsMonthAmount / 1000, 0) }}K</span>
                        </div>
                        <div class="summary-stat">
                            <span>خدمات</span>
                            <span class="summary-value">{{ number_format($servicesAmountMonth / 1000, 0) }}K</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="summary-card accounts">
                <div class="summary-card-content">
                    <div class="summary-header">
                        <div class="summary-title">إجمالي الأرصدة</div>
                        <div class="summary-icon">
                            <i class='bx bx-wallet'></i>
                        </div>
                    </div>
                    <div class="summary-total">{{ number_format($totalCashBalance / 1000, 1) }}K</div>
                    <div class="summary-label">د.ع</div>
                    <div class="summary-stats">
                        @foreach ($cashAccounts->take(2) as $account)
                            <div class="summary-stat">
                                <span>{{ Str::limit($account->account_name, 10) }}</span>
                                <span class="summary-value">{{ number_format($account->balance / 1000, 0) }}K</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="summary-card profit">
                <div class="summary-card-content">
                    <div class="summary-header">
                        <div class="summary-title">صافي الربح (الشهر)</div>
                        <div class="summary-icon">
                            <i class='bx bx-trending-up'></i>
                        </div>
                    </div>
                    <div class="summary-total">{{ number_format(($paymentsMonthAmount - $expensesMonth) / 1000, 1) }}K
                    </div>
                    <div class="summary-label">د.ع</div>
                    <div class="summary-stats">
                        <div class="summary-stat">
                            <span>مصروفات</span>
                            <span class="summary-value">{{ number_format($expensesMonth / 1000, 0) }}K</span>
                        </div>
                        <div class="summary-stat">
                            <span>عقود نشطة</span>
                            <span class="summary-value">{{ $totalActiveContracts }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities Section -->
        <div class="activities-section">
            <div class="activity-card">
                <div class="activity-header">
                    <div class="activity-title">
                        <i class='bx bx-money'></i>
                        <span>آخر المدفوعات</span>
                    </div>
                    <div class="activity-count">{{ $paymentsToday }} اليوم</div>
                </div>
                <div class="activity-list">
                    @forelse($recentPayments->take(4) as $payment)
                        <div class="activity-item">
                            <div class="activity-info">
                                <div class="activity-name">
                                    {{ $payment->contract->customer->customer_full_name ?? 'N/A' }}</div>
                                <div class="activity-details">
                                    <span>{{ $payment->contract->building->building_number ?? 'N/A' }}</span>
                                    <span>•</span>
                                    <span>{{ Carbon\Carbon::parse($payment->payment_date)->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="activity-amount payment-amount">
                                {{ number_format($payment->payment_amount / 1000, 0) }}K</div>
                        </div>
                    @empty
                        <div class="activity-item">
                            <div class="activity-info">
                                <div class="activity-name">لا توجد مدفوعات حديثة</div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="activity-card">
                <div class="activity-header">
                    <div class="activity-title">
                        <i class='bx bx-file'></i>
                        <span>آخر العقود</span>
                    </div>
                    <div class="activity-count">{{ $contractsToday }} اليوم</div>
                </div>
                <div class="activity-list">
                    @forelse($recentContracts->take(4) as $contract)
                        <div class="activity-item">
                            <div class="activity-info">
                                <div class="activity-name">{{ $contract->customer->customer_full_name ?? 'N/A' }}
                                </div>
                                <div class="activity-details">
                                    <span>{{ $contract->building->building_number ?? 'N/A' }}</span>
                                    <span>•</span>
                                    <span
                                        class="activity-badge {{ $contract->stage == 'temporary'
                                            ? 'badge-temporary'
                                            : ($contract->stage == 'accepted'
                                                ? 'badge-accepted'
                                                : 'badge-authenticated') }}">{{ $contract->stage }}</span>
                                </div>
                            </div>
                            <div class="activity-amount contract-amount">
                                {{ number_format($contract->contract_amount / 1000, 0) }}K</div>
                        </div>
                    @empty
                        <div class="activity-item">
                            <div class="activity-info">
                                <div class="activity-name">لا توجد عقود حديثة</div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Footer Stats -->
        <div class="footer-stats">
            <div class="footer-stat contracts">
                <div class="footer-icon">
                    <i class='bx bx-check-circle'></i>
                </div>
                <div class="footer-label">عقود نشطة</div>
                <div class="footer-value">{{ $totalActiveContracts }}</div>
            </div>

            <div class="footer-stat employees">
                <div class="footer-icon">
                    <i class='bx bx-user'></i>
                </div>
                <div class="footer-label">موظفون</div>
                <div class="footer-value">{{ $totalEmployees }}</div>
            </div>

            <div class="footer-stat pending">
                <div class="footer-icon">
                    <i class='bx bx-hourglass'></i>
                </div>
                <div class="footer-label">معلقة</div>
                <div class="footer-value">{{ $pendingPayments }}</div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Default Chart.js settings
            Chart.defaults.font.family = "'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
            Chart.defaults.font.size = window.innerWidth < 640 ? 9 : 11;

            // Parse data safely
            const paymentsData = {!! json_encode($last7DaysPayments) !!};
            const contractsData = {!! json_encode($last7DaysContracts) !!};
            const stagesData = {!! json_encode($contractsByStage) !!};

            // Payments Chart - Area Chart
            const paymentsCtx = document.getElementById('paymentsChart');
            if (paymentsCtx) {
                new Chart(paymentsCtx, {
                    type: 'line',
                    data: {
                        labels: paymentsData.map(d => d.date),
                        datasets: [{
                            label: 'المدفوعات (ألف د.ع)',
                            data: paymentsData.map(d => (d.amount / 1000).toFixed(1)),
                            borderColor: 'rgb(76, 201, 240)',
                            backgroundColor: 'rgba(76, 201, 240, 0.2)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            pointBackgroundColor: 'rgb(76, 201, 240)',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 8,
                                titleFont: {
                                    size: 11
                                },
                                bodyFont: {
                                    size: 10
                                },
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.y + ' ألف د.ع';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    font: {
                                        size: window.innerWidth < 640 ? 8 : 10
                                    },
                                    callback: function(value) {
                                        return value + 'K';
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        size: window.innerWidth < 640 ? 8 : 10
                                    },
                                    maxRotation: 45,
                                    minRotation: 45
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // Contracts Chart - Bar Chart
            const contractsCtx = document.getElementById('contractsChart');
            if (contractsCtx) {
                new Chart(contractsCtx, {
                    type: 'bar',
                    data: {
                        labels: contractsData.map(d => d.date),
                        datasets: [{
                            label: 'العقود',
                            data: contractsData.map(d => d.count),
                            backgroundColor: 'rgba(67, 97, 238, 0.7)',
                            borderColor: 'rgb(67, 97, 238)',
                            borderWidth: 1,
                            borderRadius: 4,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 8,
                                titleFont: {
                                    size: 11
                                },
                                bodyFont: {
                                    size: 10
                                },
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.y + ' عقد';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: {
                                        size: window.innerWidth < 640 ? 8 : 10
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        size: window.innerWidth < 640 ? 8 : 10
                                    },
                                    maxRotation: 45,
                                    minRotation: 45
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // Contract Stages - Doughnut Chart
            const stagesCtx = document.getElementById('stagesChart');
            if (stagesCtx && stagesData.length > 0) {
                // Translate stage names
                const stageLabels = {
                    'temporary': 'مؤقت',
                    'accepted': 'مقبول',
                    'authenticated': 'موثق',
                    'terminated': 'منتهي'
                };

                new Chart(stagesCtx, {
                    type: 'doughnut',
                    data: {
                        labels: stagesData.map(s => stageLabels[s.stage] || s.stage),
                        datasets: [{
                            data: stagesData.map(s => s.total),
                            backgroundColor: [
                                'rgba(234, 179, 8, 0.8)',
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(34, 197, 94, 0.8)',
                                'rgba(239, 68, 68, 0.8)',
                            ],
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    boxWidth: 10,
                                    font: {
                                        size: window.innerWidth < 640 ? 9 : 10
                                    },
                                    padding: 6
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 8,
                                titleFont: {
                                    size: 11
                                },
                                bodyFont: {
                                    size: 10
                                },
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            } else if (stagesCtx) {
                stagesCtx.parentElement.innerHTML =
                    '<p class="text-gray-400 text-center py-8 text-xs sm:text-sm">لا توجد بيانات</p>';
            }
        });
    </script>

</x-app-layout>
