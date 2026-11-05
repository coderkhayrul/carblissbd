(() => {
    'use strict';
    const data = window.dashboardData;
    if (!data) return;
    const chartIds = ['dashboard-daily-chart', 'dashboard-monthly-chart', 'dashboard-monthly-pie'];
    const metric = document.getElementById('dashboard-chart-metric');
    let charts = [];
    const date = value => { const parts = value.split('-').map(Number); return new Date(parts[0], parts[1] - 1, parts[2] || 1); };
    const money = value => 'BDT ' + Number(value).toLocaleString('en-BD', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    function render() {
        charts.forEach(chart => chart.destroy()); charts = [];
        if (!window.CanvasJS) {
            chartIds.forEach(id => { const element = document.getElementById(id); element.textContent = 'Unable to load charts. Refresh the page or view the monthly data table below.'; element.classList.add('dashboard-chart-empty'); });
            return;
        }
        const amount = metric.value === 'amount';
        const tooltip = event => event.entries.map(entry => {
            const point = entry.dataPoint;
            return `<strong>${CanvasJS.formatDate(point.x, entry.dataSeries.type === 'area' ? 'DD MMM YYYY' : 'MMM YYYY')}</strong><br>Sales: ${money(point.amount)}<br>Orders: ${point.quantity.toLocaleString('en-BD')}`;
        }).join('<br>');
        const base = {theme: 'light2', animationEnabled: true, exportEnabled: true, backgroundColor: 'transparent', toolTip: {shared: true, contentFormatter: tooltip}};
        const axisY = list => ({title: amount ? 'AMOUNT (BDT)' : 'ORDERS', minimum: 0, includeZero: true, valueFormatString: amount ? '#,##0.##' : '#,##0', ...(amount ? {} : {interval: Math.max(1, Math.ceil(Math.max(0, ...list.map(item => item.quantity)) / 5))})});
        const points = list => list.map(item => ({x: date(item.date), y: item[metric.value], amount: item.amount, quantity: item.quantity}));
        const daily = new CanvasJS.Chart(chartIds[0], {...base, axisY: axisY(data.daily), zoomEnabled: true, axisX: {valueFormatString: 'DD MMM YY', labelAngle: -25}, data: [{type: 'area', color: '#568ac0', fillOpacity: .5, markerSize: data.daily.length < 2 ? 8 : 0, xValueType: 'dateTime', dataPoints: points(data.daily)}]});
        const monthly = new CanvasJS.Chart(chartIds[1], {...base, axisY: axisY(data.monthly), axisX: {valueFormatString: 'MMM YY', intervalType: 'month', interval: Math.max(1, Math.ceil(data.monthly.length / 12)), labelAngle: -35}, data: [{type: 'column', color: '#568ac0', xValueType: 'dateTime', dataPoints: points(data.monthly)}]});
        const piePoints = data.monthly.filter(item => item[metric.value] > 0).map(item => ({label: CanvasJS.formatDate(date(item.date), 'MMM YYYY'), y: item[metric.value], amount: item.amount, quantity: item.quantity}));
        const pie = new CanvasJS.Chart(chartIds[2], {theme: 'light2', animationEnabled: true, exportEnabled: true, title: {text: piePoints.length ? '' : 'No sales for this period', fontSize: 16}, legend: {fontSize: 11}, toolTip: {contentFormatter: event => { const point = event.entries[0].dataPoint; return `${point.label}<br>Sales: ${money(point.amount)}<br>Orders: ${point.quantity.toLocaleString('en-BD')}`; }}, data: [{type: 'pie', showInLegend: piePoints.length <= 24, legendText: '{label}', indexLabel: piePoints.length <= 12 ? '{label}' : '', indexLabelFontSize: 11, dataPoints: piePoints}]});
        charts = [daily, monthly, pie];
        charts.forEach(chart => chart.render());
    }
    metric.addEventListener('change', render);
    document.querySelectorAll('.dashboard-chart-toggle').forEach(button => button.addEventListener('click', () => {
        const panel = document.getElementById(button.dataset.target);
        panel.hidden = !panel.hidden; button.textContent = panel.hidden ? 'Show' : 'Hide';
        button.setAttribute('aria-expanded', String(!panel.hidden));
        if (!panel.hidden) charts.forEach(chart => chart.render());
    }));
    const from = document.getElementById('dashboard-from');
    const to = document.getElementById('dashboard-to');
    const datesChanged = () => { from.required = !!to.value; to.required = !!from.value; to.min = from.value || '1900-01-01'; };
    from.addEventListener('change', datesChanged); to.addEventListener('change', datesChanged); datesChanged();
    render();
})();
