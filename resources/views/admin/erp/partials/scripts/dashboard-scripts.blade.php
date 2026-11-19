{{-- =====================================================
     DASHBOARD STYLES & APEXCHARTS JAVASCRIPT
     ===================================================== --}}
<style>
    .dashboard-analytics { --analytics-green: #19775f; --analytics-red: #bd4b42; --analytics-ink: #293b49; --analytics-muted: #71808a; --analytics-line: #e7ecef; }
    .stat-card { min-height: 174px; border: 0; border-radius: 8px; transition: transform 0.25s ease, box-shadow 0.25s ease; overflow: hidden; position: relative; }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 16px 34px rgba(27, 45, 57, 0.18) !important; }
    .stat-card .card-body { min-height: 174px; padding: 1.25rem; }
    .stat-copy { min-width: 0; }
    .stat-label { color: rgba(255, 255, 255, 0.9); font-size: 0.875rem; font-weight: 600; margin: 0 0 0.55rem; }
    .stat-value { color: #fff; font-size: 1.55rem; font-weight: 700; line-height: 1.15; margin: 0; white-space: nowrap; }
    .stat-detail { display: block; min-height: 1.1rem; margin-top: 0.55rem; color: rgba(255, 255, 255, 0.72); font-size: 0.75rem; }
    .stat-icon { display: grid; width: 46px; height: 46px; flex: 0 0 46px; place-items: center; border: 1px solid rgba(255, 255, 255, 0.25); border-radius: 13px; background: rgba(255, 255, 255, 0.14); color: #fff; font-size: 1.45rem; }
    .theme-revenue { background: linear-gradient(135deg, #176b59, #238c71); }
    .theme-expenses { background: linear-gradient(135deg, #9b552d, #c8753a); }
    .theme-orders { background: linear-gradient(135deg, #355d79, #497f9b); }
    .theme-profit { background: linear-gradient(135deg, #19775f, #2b9b78); }
    .theme-loss { background: linear-gradient(135deg, #a93f3a, #ca5b50); }
    .bg-soft-success { background-color: rgba(52, 195, 143, 0.1); }
    .bg-soft-danger { background-color: rgba(244, 106, 106, 0.1); }
    .text-success { color: #1f9d55 !important; }
    .text-danger { color: #d9534f !important; }
    .insight-strip { border: 1px solid var(--analytics-line); border-left: 4px solid var(--analytics-green); border-radius: 8px; }
    .analytics-panel { border: 1px solid var(--analytics-line); border-radius: 8px; }
    .chart-panel .card-body { min-height: 442px; }
    .bottom-panel { min-height: 370px; border: 1px solid var(--analytics-line); border-radius: 8px; }
    .best-sellers-panel { border-top: 3px solid #4776a8; }
    .inventory-panel { border-top: 3px solid #d97706; }
    .section-eyebrow { display: block; margin-bottom: 0.25rem; color: var(--analytics-muted); font-size: 0.68rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; }
    .section-icon { display: grid; width: 42px; height: 42px; flex: 0 0 42px; place-items: center; border-radius: 12px; font-size: 1.25rem; }
    .sales-icon { background: #eaf1f7; color: #4776a8; }
    .stock-summary { display: flex; min-width: 58px; flex-direction: column; align-items: center; justify-content: center; padding: 0.35rem 0.55rem; border-radius: 9px; background: #eef3f5; color: #48606b; line-height: 1.1; }
    .stock-summary strong { font-size: 1.05rem; }
    .stock-summary small { font-size: 0.62rem; text-transform: uppercase; }
    .stock-summary-alert { background: #fff0e9; color: #b04b32; }
    .insight-table { border-collapse: separate; border-spacing: 0; }
    .insight-table thead th { padding: 0.7rem 0.55rem; border-bottom: 1px solid var(--analytics-line); color: #788791; font-size: 0.67rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; white-space: nowrap; }
    .insight-table tbody td { padding: 0.75rem 0.55rem; border-bottom: 1px solid #f0f3f5; color: var(--analytics-ink); font-size: 0.84rem; }
    .insight-table tbody tr:last-child td { border-bottom: 0; }
    .rank-column { width: 42px; }
    .product-rank { display: inline-grid; width: 28px; height: 28px; place-items: center; border-radius: 9px; background: #eef2f4; color: #65747e; font-size: 0.68rem; font-weight: 700; }
    .rank-1 { background: #fff4dc; color: #a96b00; }
    .rank-2 { background: #edf2f5; color: #526572; }
    .rank-3 { background: #f9eee8; color: #9b6045; }
    .product-name { max-width: 210px; font-weight: 600; }
    .sales-meter, .stock-meter { display: block; width: 66px; height: 5px; overflow: hidden; border-radius: 99px; background: #edf1f3; }
    .sales-meter span, .stock-meter span { display: block; height: 100%; border-radius: inherit; background: linear-gradient(90deg, #4776a8, #4f9a9a); }
    .stock-meter { width: 76px; }
    .stock-meter .meter-low { background: #d97706; }
    .stock-meter .meter-critical { background: #c74747; }
    .stock-badge { display: inline-block; padding: 0.32rem 0.48rem; border-radius: 6px; font-size: 0.66rem; font-weight: 700; }
    .stock-badge.low { background: #fff3df; color: #9d6200; }
    .stock-badge.critical { background: #ffebe8; color: #b13d35; }
    .empty-insight { display: flex; min-height: 210px; flex-direction: column; align-items: center; justify-content: center; gap: 0.35rem; color: #526573; text-align: center; }
    .empty-insight i { margin-bottom: 0.4rem; color: #6e9a8d; font-size: 2rem; }
    .empty-insight span { font-weight: 700; }
    .empty-insight small { max-width: 270px; color: var(--analytics-muted); }
    .metric-help { color: inherit; }
    .metric-help { display: inline-flex; align-items: center; justify-content: center; padding: 0; border: 0; background: transparent; font-size: 1rem; line-height: 1; vertical-align: middle; cursor: help; }
    .metric-help:focus-visible { outline: 2px solid currentColor; outline-offset: 2px; }
    [data-reveal] { animation: dashboard-reveal 520ms ease-out both; }
    [data-reveal]:nth-child(1) { animation-delay: 40ms; }
    [data-reveal]:nth-child(2) { animation-delay: 100ms; }
    [data-reveal]:nth-child(3) { animation-delay: 160ms; }
    [data-reveal]:nth-child(4) { animation-delay: 220ms; }
    @keyframes dashboard-reveal { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
    @media (prefers-reduced-motion: reduce) { [data-reveal] { animation: none; } }
    @media (max-width: 575.98px) {
        .stat-card, .stat-card .card-body { min-height: 158px; }
        .stat-card .card-body { padding: 1rem 0.85rem; }
        .stat-value { font-size: 1.15rem; }
        .stat-icon { width: 38px; height: 38px; flex-basis: 38px; font-size: 1.2rem; }
        .chart-panel .card-body { min-height: 0; }
        .bottom-panel { min-height: 0; }
        .sales-meter { width: 40px; }
        .stock-meter { width: 54px; }
        .insight-table tbody td { padding: 0.65rem 0.35rem; font-size: 0.76rem; }
    }
    .domain-card { border: 1px solid var(--analytics-line) !important; border-radius: 8px; transition: transform 0.2s ease, box-shadow 0.2s ease; background: #fff; }
    .domain-card:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(27, 45, 57, 0.08) !important; }
    .domain-tag { display: inline-flex; align-items: center; padding: 0.2rem 0.55rem; border-radius: 6px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; }
    .tag-commerce { background: #e0f2fe; color: #0284c7; }
    .tag-fulfillment { background: #fef3c7; color: #b45309; }
    .tag-kitchen { background: #f3e8ff; color: #7e22ce; }
    .tag-valuation { background: #dcfce7; color: #15803d; }
    .channel-split-bar { display: flex; height: 6px; border-radius: 99px; overflow: hidden; background: #f1f5f9; gap: 2px; }
    .split-pos { background: #4f46e5; border-radius: 99px 0 0 99px; transition: width 0.4s ease; }
    .split-online { background: #06b6d4; border-radius: 0 99px 99px 0; transition: width 0.4s ease; }
    .channel-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; }
    .ingredients-panel { border-top: 3px solid #059669 !important; }
    .storefront-panel { border-top: 3px solid #6366f1 !important; }
    .ingredient-dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
    .customer-avatar-sm { display: inline-grid; width: 30px; height: 30px; place-items: center; border-radius: 50%; background: #e0e7ff; color: #4338ca; font-size: 0.72rem; font-weight: 700; flex-shrink: 0; }
</style>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.bootstrap && bootstrap.Tooltip) {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(element => {
                new bootstrap.Tooltip(element);
                element.addEventListener('click', event => event.stopPropagation());
            });
        }

        document.querySelectorAll('.stat-card[role="button"]').forEach(card => {
            card.addEventListener('keydown', event => {
                if (event.target !== card) return;
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    bootstrap.Modal.getOrCreateInstance(document.querySelector('#expenseBreakdownModal')).show();
                }
            });
        });
    });

    document.querySelectorAll('.counter').forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText.replace(/,/g, '');
            const increment = Math.max(target / 100, 1);
            if (count < target) {
                counter.innerText = Math.ceil(count + increment).toLocaleString();
                setTimeout(updateCount, 20);
            } else {
                counter.innerText = target.toLocaleString();
            }
        };
        updateCount();
    });

    new ApexCharts(document.querySelector("#revenue_chart"), {
        series: [
            { name: 'This 7 days', data: @json($chartRevenues) },
            { name: 'Previous 7 days', data: @json($previousWeekRevenues) }
        ],
        chart: { type: 'area', height: 350, toolbar: { show: false }, animations: { enabled: !window.matchMedia('(prefers-reduced-motion: reduce)').matches, easing: 'easeinout', speed: 700 } },
        colors: ['#19775f', '#4776a8'],
        stroke: { curve: 'smooth', width: [3, 2], dashArray: [0, 5] },
        fill: { opacity: [0.22, 0.04] },
        dataLabels: { enabled: false },
        yaxis: { labels: { formatter: value => '₦' + Number(value).toLocaleString() } },
        tooltip: { y: { formatter: value => '₦' + Number(value).toLocaleString() } },
        legend: { position: 'top', horizontalAlign: 'right' },
        xaxis: { categories: @json($chartDays) },
    }).render();

    new ApexCharts(document.querySelector("#payment_chart"), {
        series: @json($paymentCounts),
        labels: @json($paymentLabels),
        chart: { type: 'donut', height: 330 },
        colors: ['#18864b', '#d97706', '#386cb0', '#c74747', '#65727e'],
        plotOptions: { pie: { donut: { size: '70%', labels: { show: true } } } }
    }).render();
</script>
