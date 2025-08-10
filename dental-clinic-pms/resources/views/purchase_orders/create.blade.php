<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('New Purchase Order') }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('purchase-orders.store') }}" id="po-form">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Supplier</label>
                            <select name="supplier_id" class="mt-1 block w-full border-gray-300 rounded" required>
                                <option value="">Select supplier...</option>
                                @foreach($suppliers as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Expected Date</label>
                                <input type="date" name="expected_date" class="mt-1 block w-full border-gray-300 rounded" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Notes</label>
                                <input type="text" name="notes" class="mt-1 block w-full border-gray-300 rounded" />
                            </div>
                        </div>

                        <h3 class="text-sm font-semibold mb-2">Line Items</h3>
                        <div id="po-lines" class="space-y-3"></div>
                        <button type="button" id="add-line" class="mt-2 inline-flex items-center px-3 py-2 bg-gray-100 text-sm rounded">Add Line</button>

                        <div class="mt-6 flex justify-end">
                            <x-primary-button>Save</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    (function(){
      const lines = document.getElementById('po-lines');
      const addBtn = document.getElementById('add-line');
      function addLine() {
        const wrapper = document.createElement('div');
        wrapper.className = 'grid grid-cols-1 md:grid-cols-12 gap-2 items-end';
        wrapper.innerHTML = `
          <div class="md:col-span-5">
            <label class="block text-xs text-gray-600">Item (optional)</label>
            <select name="lines[][inventory_item_id]" class="mt-1 block w-full border-gray-300 rounded">
              <option value="">Custom...</option>
              @foreach($items as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
              @endforeach
            </select>
          </div>
          <div class="md:col-span-4">
            <label class="block text-xs text-gray-600">Description</label>
            <input name="lines[][description]" class="mt-1 block w-full border-gray-300 rounded" placeholder="Item description" />
          </div>
          <div class="md:col-span-1">
            <label class="block text-xs text-gray-600">Qty</label>
            <input type="number" min="1" name="lines[][quantity_ordered]" class="mt-1 block w-full border-gray-300 rounded" value="1" />
          </div>
          <div class="md:col-span-1">
            <label class="block text-xs text-gray-600">Cost</label>
            <input type="number" step="0.01" min="0" name="lines[][unit_cost]" class="mt-1 block w-full border-gray-300 rounded" value="0" />
          </div>
          <div class="md:col-span-1 flex justify-end">
            <button type="button" class="text-red-600 remove-line">Remove</button>
          </div>
        `;
        wrapper.querySelector('.remove-line').addEventListener('click', () => wrapper.remove());
        lines.appendChild(wrapper);
      }
      addBtn.addEventListener('click', addLine);
      // add first line by default
      addLine();
    })();
    </script>
    @endpush
</x-app-layout>
