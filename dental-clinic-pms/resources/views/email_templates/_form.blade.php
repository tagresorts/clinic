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
        <label class="block text-sm font-medium text-gray-700">Recipients (optional)</label>
        <div class="mt-2 space-y-3">
            <div class="flex items-center space-x-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="recipient_type" value="" class="mr-2" @checked(old('recipient_type', $emailTemplate->recipient_type ?? '')==='')>
                    <span class="text-sm text-gray-700">Use system default</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="recipient_type" value="manual" class="mr-2" @checked(old('recipient_type', $emailTemplate->recipient_type ?? '')==='manual')>
                    <span class="text-sm text-gray-700">Manual emails</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="recipient_type" value="roles" class="mr-2" @checked(old('recipient_type', $emailTemplate->recipient_type ?? '')==='roles')>
                    <span class="text-sm text-gray-700">By roles</span>
                </label>
            </div>
            <div id="recipient-manual" class="hidden">
                <label for="recipient_emails" class="block text-xs text-gray-600">Emails (comma-separated)</label>
                <input type="text" name="recipient_emails" id="recipient_emails" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('recipient_emails', $emailTemplate->recipient_emails ?? '') }}" placeholder="owner@clinic.com, manager@clinic.com">
            </div>
            <div id="recipient-roles" class="hidden">
                <label for="recipient_roles" class="block text-xs text-gray-600">Roles (comma-separated)</label>
                <input type="text" name="recipient_roles" id="recipient_roles" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('recipient_roles', $emailTemplate->recipient_roles ?? '') }}" placeholder="administrator, inventory_manager">
            </div>
            <p class="text-xs text-gray-500">If left as "Use system default", stock emails use `ALERT_STOCK_RECIPIENTS` env or all administrators.</p>
        </div>
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
            <li class="mt-2 font-semibold">Clinic</li>
            <li><code>{{'{'.'clinic_name'.'}'}}</code> - Clinic name</li>
            <li><code>{{'{'.'clinic_address'.'}'}}</code> - Clinic address</li>
            <li><code>{{'{'.'clinic_phone'.'}'}}</code> - Clinic phone</li>
            <li><code>{{'{'.'clinic_email'.'}'}}</code> - Clinic email</li>
            <li><code>{{'{'.'clinic_fb'.'}'}}</code> - Facebook URL</li>
            <li><code>{{'{'.'clinic_instagram'.'}'}}</code> - Instagram URL</li>
            <li><code>{{'{'.'clinic_website'.'}'}}</code> - Website URL</li>
            <li><code>{{'{'.'operation_hours'.'}'}}</code> - Operation hours</li>
            <li><code>{{'{'.'clinic_logo_url'.'}'}}</code> - Logo URL (for HTML templates)</li>
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
            const radios = document.querySelectorAll('input[name="recipient_type"]');
            const manual = document.getElementById('recipient-manual');
            const roles = document.getElementById('recipient-roles');

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

            function toggleRecipients() {
                const val = document.querySelector('input[name="recipient_type"]:checked')?.value || '';
                manual.classList.toggle('hidden', val !== 'manual');
                roles.classList.toggle('hidden', val !== 'roles');
            }
            radios.forEach(r => r.addEventListener('change', toggleRecipients));
            toggleRecipients();
        })();
    </script>
@endpush
