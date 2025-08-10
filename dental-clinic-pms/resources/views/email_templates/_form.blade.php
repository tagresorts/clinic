<div>
    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('name', $emailTemplate->name ?? '') }}" required>
    </div>

    <div class="mb-4">
        <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
        <input type="text" name="subject" id="subject" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('subject', $emailTemplate->subject ?? '') }}" required>
    </div>

    <div class="mb-4">
        <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
        <input type="text" name="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('type', $emailTemplate->type ?? '') }}" required>
    </div>

    <div class="mb-2">
        <label for="body-editor" class="block text-sm font-medium text-gray-700">Body</label>
        <div class="flex flex-wrap items-center gap-1 mb-2 text-sm">
            <button type="button" data-cmd="bold" class="px-2 py-1 border rounded">B</button>
            <button type="button" data-cmd="italic" class="px-2 py-1 border rounded italic">I</button>
            <button type="button" data-cmd="underline" class="px-2 py-1 border rounded"><span style="text-decoration:underline;">U</span></button>
            <span class="mx-2 text-gray-400">|</span>
            <button type="button" data-cmd="insertUnorderedList" class="px-2 py-1 border rounded">• List</button>
            <button type="button" data-cmd="insertOrderedList" class="px-2 py-1 border rounded">1. List</button>
            <span class="mx-2 text-gray-400">|</span>
            <button type="button" data-action="link" class="px-2 py-1 border rounded">Link</button>
            <button type="button" data-action="clear" class="px-2 py-1 border rounded">Clear</button>
        </div>
        <div id="body-editor" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-3 min-h-[240px]" contenteditable="true">{!! old('body', $emailTemplate->body ?? '') !!}</div>
        <textarea name="body" id="body" class="hidden">{{ old('body', $emailTemplate->body ?? '') }}</textarea>
        <p class="text-xs text-gray-500 mt-1">Basic editor with bold, italic, underline, lists, and links. HTML is saved.</p>
    </div>

    <div class="mb-4">
        <p class="text-sm text-gray-500">Available Placeholders:</p>
        <ul class="text-sm text-gray-500">
            <li><code>{{'{'.'user_name'.'}'}}</code> - The name of the user</li>
            <li><code>{{'{'.'reset_link'.'}'}}</code> - The password reset link</li>
            <li><code>{{'{'.'appointment_date'.'}'}}</code> - The date of the appointment</li>
            <li><code>{{'{'.'appointment_time'.'}'}}</code> - The time of the appointment</li>
            <li><code>{{'{'.'patient_name'.'}'}}</code> - The name of the patient</li>
            <li><code>{{'{'.'invoice_number'.'}'}}</code> - The invoice number</li>
            <li><code>{{'{'.'invoice_total'.'}'}}</code> - The total amount of the invoice</li>
            <li><code>{{'{'.'invoice_due_date'.'}'}}</code> - The due date of the invoice</li>
            <li><code>{{'{'.'stock_item_name'.'}'}}</code> - The name of the stock item</li>
            <li><code>{{'{'.'stock_item_quantity'.'}'}}</code> - The quantity of the stock item</li>
            <li><code>{{'{'.'low_stock_table'.'}'}}</code> - HTML table of low stock items</li>
            <li><code>{{'{'.'expiring_stock_table'.'}'}}</code> - HTML table of expiring items</li>
            <li><code>{{'{'.'inventory_url'.'}'}}</code> - Link to inventory index</li>
            <li><code>{{'{'.'low_count'.'}'}}</code> - Count of low stock items</li>
            <li><code>{{'{'.'expiring_count'.'}'}}</code> - Count of expiring items</li>
        </ul>
    </div>

    <div class="flex items-center justify-end mt-4">
        <x-primary-button>
            {{ __('Save') }}
        </x-primary-button>
    </div>
</div>

@push('scripts')
    <script>
        (function () {
            const editor = document.getElementById('body-editor');
            const textarea = document.getElementById('body');
            const toolbar = editor.previousElementSibling;

            function exec(cmd, val = null) {
                document.execCommand(cmd, false, val);
                editor.focus();
            }

            if (toolbar) {
                toolbar.addEventListener('click', function (e) {
                    const btn = e.target.closest('button');
                    if (!btn) return;
                    const cmd = btn.getAttribute('data-cmd');
                    const action = btn.getAttribute('data-action');
                    if (cmd) {
                        exec(cmd);
                    } else if (action === 'link') {
                        const url = prompt('Enter URL');
                        if (url) exec('createLink', url);
                    } else if (action === 'clear') {
                        editor.innerHTML = '';
                    }
                });
            }

            // Sync editor -> textarea on form submit
            const form = editor.closest('form');
            if (form) {
                form.addEventListener('submit', function () {
                    textarea.value = editor.innerHTML;
                });
            }
        })();
    </script>
@endpush
