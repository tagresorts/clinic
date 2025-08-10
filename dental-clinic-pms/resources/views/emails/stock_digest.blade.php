<h2>Stock Alerts</h2>

@if(count($lowStock))
<h3>Low Stock</h3>
<table border="1" cellpadding="6" cellspacing="0" width="100%">
    <tr><th align="left">Item</th><th align="left">Qty</th><th align="left">Reorder</th></tr>
    @foreach($lowStock as $i)
    <tr>
        <td>{{ $i['item_name'] }}</td>
        <td>{{ $i['quantity_in_stock'] }}</td>
        <td>{{ $i['reorder_level'] }}</td>
    </tr>
    @endforeach
</table>
@endif

@if(count($expiring))
<h3>Expiring Soon</h3>
<table border="1" cellpadding="6" cellspacing="0" width="100%">
    <tr><th align="left">Item</th><th align="left">Expiry Date</th></tr>
    @foreach($expiring as $i)
    <tr>
        <td>{{ $i['item_name'] }}</td>
        <td>{{ \Carbon\Carbon::parse($i['expiry_date'])->format('M d, Y') }}</td>
    </tr>
    @endforeach
</table>
@endif

<p>Open Inventory: <a href="{{ route('inventory.index') }}">Inventory</a></p>
