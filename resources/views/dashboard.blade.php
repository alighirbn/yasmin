<x-app-layout>
    <x-slot name="header">
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        <div class="dashboard-header">
            <div class="header-content">
                <div class="header-title">
                    <i class='bx bxs-dashboard'></i>
                    <div>
                        <h1> {{ auth()->user()->name }}</h1>
                        {{ __('word.dashboard_msg') }}
                    </div>
                </div>
                <div class="header-date">
                    <i class='bx bx-calendar'></i>
                    <div>
                        <div>{{ Carbon\Carbon::now()->translatedFormat('l') }}</div>
                        <div>{{ Carbon\Carbon::now()->translatedFormat('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            :root {
                --primary: #4361ee;
                --secondary: #3f37c9;
                --success: #06d6a0;
                --info: #4cc9f0;
                --warning: #ffd60a;
                --danger: #ef476f;
                --dark: #212529;
                --gray: #6c757d;
                --light-bg: #f8f9fa;
                --border-radius: 16px;
                --shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                --shadow-hover: 0 4px 16px rgba(0, 0, 0, 0.12);
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Cairo', sans-serif;
                background: var(--light-bg);
                color: var(--dark);
                direction: rtl;
            }

            .dashboard-header {}

            .header-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 1rem;
            }

            .header-title {
                display: flex;
                align-items: center;
                gap: 1rem;
                color: white;
            }

            .header-title i {
                font-size: 2.5rem;
            }

            .header-title h1 {
                font-size: 1.5rem;
                font-weight: 700;
                margin-bottom: 0.25rem;
            }

            .header-title p {
                font-size: 0.9rem;
                opacity: 0.9;
            }

            .header-date {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                background: rgba(255, 255, 255, 0.2);
                backdrop-filter: blur(10px);
                padding: 0.75rem 1rem;
                border-radius: 12px;
                color: white;
            }

            .header-date i {
                font-size: 1.5rem;
            }

            .header-date>div {
                font-size: 0.85rem;
                line-height: 1.4;
            }

            .dashboard-container {
                padding: 0 1rem 1rem;
                max-width: 1400px;
                margin: 0 auto;
            }

            /* Stats Cards */
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 1.25rem;
                margin-bottom: 1.5rem;
            }

            .stat-card {
                background: white;
                border-radius: var(--border-radius);
                padding: 1.5rem;
                box-shadow: var(--shadow);
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .stat-card::before {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                width: 100%;
                height: 4px;
                transition: height 0.3s ease;
            }

            .stat-card:hover {
                transform: translateY(-4px);
                box-shadow: var(--shadow-hover);
            }

            .stat-card:hover::before {
                height: 6px;
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
                background: var(--danger);
            }

            .stat-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .stat-info h3 {
                color: var(--gray);
                font-size: 0.9rem;
                font-weight: 500;
                margin-bottom: 0.5rem;
            }

            .stat-value {
                font-size: 2rem;
                font-weight: 700;
                color: var(--dark);
                margin-bottom: 0.25rem;
            }

            .stat-detail {
                color: var(--gray);
                font-size: 0.85rem;
            }

            .stat-icon {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.75rem;
                flex-shrink: 0;
            }

            .stat-card.contracts .stat-icon {
                background: rgba(67, 97, 238, 0.1);
                color: var(--primary);
            }

            .stat-card.payments .stat-icon {
                background: rgba(6, 214, 160, 0.1);
                color: var(--success);
            }

            .stat-card.services .stat-icon {
                background: rgba(76, 201, 240, 0.1);
                color: var(--info);
            }

            .stat-card.installments .stat-icon {
                background: rgba(239, 71, 111, 0.1);
                color: var(--danger);
            }

            /* Charts Section */
            .charts-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 1.25rem;
                margin-bottom: 1.5rem;
            }

            .chart-card {
                background: white;
                border-radius: var(--border-radius);
                padding: 1.5rem;
                box-shadow: var(--shadow);
            }

            .chart-header {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                margin-bottom: 1rem;
                font-weight: 600;
                color: var(--dark);
            }

            .chart-header i {
                font-size: 1.25rem;
            }

            .chart-container {
                height: 220px;
                position: relative;
            }

            /* Summary Cards */
            .summary-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 1.25rem;
                margin-bottom: 1.5rem;
            }

            .summary-card {
                border-radius: var(--border-radius);
                padding: 1.5rem;
                color: white;
                box-shadow: var(--shadow);
                position: relative;
                overflow: hidden;
            }

            .summary-card::after {
                content: '';
                position: absolute;
                top: -50%;
                right: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
                pointer-events: none;
            }

            .summary-card.weekly {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }

            .summary-card.monthly {
                background: linear-gradient(135deg, #06d6a0 0%, #0891b2 100%);
            }

            .summary-card.accounts {
                background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%);
            }

            .summary-card.profit {
                background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
            }

            .summary-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1rem;
            }

            .summary-title {
                font-weight: 600;
                font-size: 1rem;
            }

            .summary-icon {
                font-size: 1.5rem;
                opacity: 0.8;
            }

            .summary-stats {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
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
                font-size: 1.75rem;
                font-weight: 700;
                margin: 0.5rem 0;
            }

            .summary-label {
                font-size: 0.85rem;
                opacity: 0.9;
            }

            /* Activities Section */
            .activities-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 1.25rem;
                margin-bottom: 1.5rem;
            }

            .activity-card {
                background: white;
                border-radius: var(--border-radius);
                padding: 1.5rem;
                box-shadow: var(--shadow);
            }

            .activity-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1rem;
            }

            .activity-title {
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: var(--dark);
            }

            .activity-title i {
                font-size: 1.25rem;
            }

            .activity-badge {
                background: var(--light-bg);
                padding: 0.25rem 0.75rem;
                border-radius: 20px;
                font-size: 0.8rem;
                color: var(--gray);
            }

            .activity-list {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }

            .activity-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1rem;
                background: var(--light-bg);
                border-radius: 12px;
                transition: all 0.2s ease;
            }

            .activity-item:hover {
                background: #e9ecef;
                transform: translateX(-2px);
            }

            .activity-info {
                flex: 1;
                min-width: 0;
                margin-left: 0.75rem;
            }

            .activity-name {
                font-weight: 600;
                font-size: 0.9rem;
                margin-bottom: 0.25rem;
                color: var(--dark);
                line-height: 1.4;
            }

            .activity-details {
                font-size: 0.8rem;
                color: var(--gray);
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .stage-badge {
                padding: 0.15rem 0.5rem;
                border-radius: 12px;
                font-size: 0.7rem;
                font-weight: 500;
            }

            .badge-temporary {
                background: rgba(255, 214, 10, 0.2);
                color: #ca8a04;
            }

            .badge-accepted {
                background: rgba(67, 97, 238, 0.2);
                color: var(--primary);
            }

            .badge-authenticated {
                background: rgba(6, 214, 160, 0.2);
                color: var(--success);
            }

            .activity-amount {
                font-weight: 700;
                font-size: 0.95rem;
                margin-right: 1rem;
                flex-shrink: 0;
            }

            .payment-amount {
                color: var(--success);
            }

            .contract-amount {
                color: var(--primary);
            }

            /* Footer Stats */
            .footer-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                gap: 1rem;
            }

            .footer-stat {
                background: white;
                border-radius: var(--border-radius);
                padding: 1.25rem;
                box-shadow: var(--shadow);
                text-align: center;
                transition: all 0.3s ease;
            }

            .footer-stat:hover {
                transform: translateY(-3px);
                box-shadow: var(--shadow-hover);
            }

            .footer-icon {
                font-size: 2.25rem;
                margin-bottom: 0.5rem;
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
                font-size: 0.85rem;
                color: var(--gray);
                margin-bottom: 0.25rem;
            }

            .footer-value {
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--dark);
            }

            /* Responsive */
            @media (max-width: 768px) {
                .dashboard-container {
                    padding: 0 0.75rem 0.75rem;
                }

                .header-content {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .stats-grid,
                .charts-grid,
                .summary-grid,
                .activities-grid {
                    grid-template-columns: 1fr;
                }

                .footer-grid {
                    grid-template-columns: repeat(3, 1fr);
                }
            }
        </style>
    </x-slot>
    <br>

    <div class="dashboard-container">
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card contracts">
                <div class="stat-content">
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
                <div class="stat-content">
                    <div class="stat-info">
                        <h3>مدفوعات اليوم</h3>
                        <div class="stat-value">{{ $paymentsToday }}</div>
                        <div class="stat-detail">{{ number_format($paymentsTodayAmount / 1000000, 1) }}M د.ع</div>
                    </div>
                    <div class="stat-icon">
                        <i class='bx bx-money'></i>
                    </div>
                </div>
            </div>

            <div class="stat-card services">
                <div class="stat-content">
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
                <div class="stat-content">
                    <div class="stat-info">
                        <h3>أقساط مستحقة</h3>
                        <div class="stat-value">{{ $dueInstallmentsToday }}</div>
                        <div class="stat-detail">{{ number_format($dueInstallmentsTodayAmount / 1000000, 1) }}M د.ع
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class='bx bx-time'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <i class='bx bx-line-chart'></i>
                    <span>المدفوعات - آخر 7 أيام</span>
                </div>
                <div class="chart-container">
                    <canvas id="paymentsChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <i class='bx bx-bar-chart'></i>
                    <span>العقود - آخر 7 أيام</span>
                </div>
                <div class="chart-container">
                    <canvas id="contractsChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <i class='bx bx-pie-chart-alt-2'></i>
                    <span>العقود حسب المرحلة</span>
                </div>
                <div class="chart-container">
                    <canvas id="stagesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-grid">
            <div class="summary-card weekly">
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
                        <span class="summary-value">{{ number_format($paymentsWeekAmount / 1000000, 1) }}M</span>
                    </div>
                    <div class="summary-stat">
                        <span>خدمات</span>
                        <span class="summary-value">{{ $servicesWeek }}</span>
                    </div>
                </div>
            </div>

            <div class="summary-card monthly">
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
                        <span class="summary-value">{{ number_format($paymentsMonthAmount / 1000000, 1) }}M</span>
                    </div>
                    <div class="summary-stat">
                        <span>خدمات</span>
                        <span class="summary-value">{{ number_format($servicesAmountMonth / 1000000, 1) }}M</span>
                    </div>
                </div>
            </div>

            <div class="summary-card accounts">
                <div class="summary-header">
                    <div class="summary-title">إجمالي الأرصدة</div>
                    <div class="summary-icon">
                        <i class='bx bx-wallet'></i>
                    </div>
                </div>
                <div class="summary-total">{{ number_format($totalCashBalance / 1000000, 1) }}M</div>
                <div class="summary-label">دينار عراقي</div>
                <div class="summary-stats">
                    @foreach ($cashAccounts->take(2) as $account)
                        <div class="summary-stat">
                            <span>{{ Str::limit($account->account_name, 12) }}</span>
                            <span class="summary-value">{{ number_format($account->balance / 1000000, 1) }}M</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="summary-card profit">
                <div class="summary-header">
                    <div class="summary-title">صافي الربح</div>
                    <div class="summary-icon">
                        <i class='bx bx-trending-up'></i>
                    </div>
                </div>
                <div class="summary-total">{{ number_format(($paymentsMonthAmount - $expensesMonth) / 1000000, 1) }}M
                </div>
                <div class="summary-label">دينار عراقي</div>
                <div class="summary-stats">
                    <div class="summary-stat">
                        <span>مصروفات</span>
                        <span class="summary-value">{{ number_format($expensesMonth / 1000000, 1) }}M</span>
                    </div>
                    <div class="summary-stat">
                        <span>عقود نشطة</span>
                        <span class="summary-value">{{ $totalActiveContracts }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities Section -->
        <div class="activities-grid">
            <div class="activity-card">
                <div class="activity-header">
                    <div class="activity-title">
                        <i class='bx bx-money'></i>
                        <span>آخر المدفوعات</span>
                    </div>
                    <div class="activity-badge">{{ $paymentsToday }} اليوم</div>
                </div>
                <div class="activity-list">
                    @forelse($recentPayments->take(5) as $payment)
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
                                {{ number_format($payment->payment_amount / 1000000, 2) }}M
                            </div>
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
                    <div class="activity-badge">{{ $contractsToday }} اليوم</div>
                </div>
                <div class="activity-list">
                    @forelse($recentContracts->take(5) as $contract)
                        <div class="activity-item">
                            <div class="activity-info">
                                <div class="activity-name">{{ $contract->customer->customer_full_name ?? 'N/A' }}
                                </div>
                                <div class="activity-details">
                                    <span>{{ $contract->building->building_number ?? 'N/A' }}</span>
                                    <span>•</span>
                                    <span
                                        class="stage-badge {{ $contract->stage == 'temporary' ? 'badge-temporary' : ($contract->stage == 'accepted' ? 'badge-accepted' : 'badge-authenticated') }}">
                                        {{ $contract->stage == 'temporary' ? 'مؤقت' : ($contract->stage == 'accepted' ? 'مقبول' : 'موثق') }}
                                    </span>
                                </div>
                            </div>
                            <div class="activity-amount contract-amount">
                                {{ number_format($contract->contract_amount / 1000000, 2) }}M
                            </div>
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
        <div class="footer-grid">
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        Chart.defaults.font.family = "'Cairo', sans-serif";
        Chart.defaults.font.size = 11;

        const paymentsData = @json($last7DaysPayments);
        const contractsData = @json($last7DaysContracts);
        const stagesData = @json($contractsByStage);

        // Payments Chart
        new Chart(document.getElementById('paymentsChart'), {
            type: 'line',
            data: {
                labels: paymentsData.map(d => d.date),
                datasets: [{
                    label: 'المدفوعات (مليون د.ع)',
                    data: paymentsData.map(d => d.amount / 1000000),
                    borderColor: '#06d6a0',
                    backgroundColor: 'rgba(6, 214, 160, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#06d6a0',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
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
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toFixed(2) + ' مليون د.ع';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toFixed(1) + 'M';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Contracts Chart
        new Chart(document.getElementById('contractsChart'), {
            type: 'bar',
            data: {
                labels: contractsData.map(d => d.date),
                datasets: [{
                    label: 'العقود',
                    data: contractsData.map(d => d.count),
                    backgroundColor: 'rgba(67, 97, 238, 0.8)',
                    borderRadius: 8,
                    borderSkipped: false
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
                        padding: 12,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Stages Chart
        const stageLabels = {
            'temporary': 'مؤقت',
            'accepted': 'مقبول',
            'authenticated': 'موثق'
        };

        new Chart(document.getElementById('stagesChart'), {
            type: 'doughnut',
            data: {
                labels: stagesData.map(s => stageLabels[s.stage] || s.stage),
                datasets: [{
                    data: stagesData.map(s => s.total),
                    backgroundColor: [
                        'rgba(255, 214, 10, 0.8)',
                        'rgba(67, 97, 238, 0.8)',
                        'rgba(6, 214, 160, 0.8)'
                    ],
                    borderWidth: 3,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8
                    }
                }
            }
        });
    </script>
</x-app-layout>
