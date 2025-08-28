<div id="kpi-summary-panel" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    @foreach ($kpis as $key => $kpi)
        @if(in_array($key, ['todays_appointments', 'active_patients']))
            <x-kpi.widget :title="$kpi['title']" :value="$kpiData[$key]" :icon="$kpi['icon']" />
        @endif
    @endforeach
    <x-kpi.widget :title="'Low Stock'" :value="$kpiData['low_stock_items'] ?? 0" :icon="'exclamation-triangle'" />
    <x-kpi.widget :title="'Expiring Soon'" :value="$kpiData['expiring_items'] ?? 0" :icon="'clock'" />
</div>

