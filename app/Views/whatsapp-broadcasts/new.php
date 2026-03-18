<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>

<h2>WhatsApp Broadcast</h2>

<form id="broadcastForm" action="<?= base_url($link) ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="recipient_type" id="hiddenRecipientType" value="all">
    <?= csrf_field(); ?>
    <input type="hidden" name="selected_customers" id="hiddenSelectedCustomers" value="">
    <div class="st-broadcast-container">
        <!-- Recipient Selection Panel -->
        <div class="st-broadcast-section">
            <h3>1. Select Recipients</h3>
            <div class="st-recipient-tabs">
                <button type="button" class="st-recipient-tab active" data-recipient="all">Send All Contacts</button>
                <button type="button" class="st-recipient-tab" data-recipient="group">By Group</button>
                <button type="button" class="st-recipient-tab" data-recipient="selected">Selected Contacts</button>
            </div>

            <!-- Send All -->
            <div id="all-recipients" class="st-recipient-panel active">
                <div class="st-info-box">
                    <p>📢 <strong>Send to all active contacts</strong></p>
                    <p>Total Recipients: <strong id="allContactsCount">0</strong></p>
                </div>
            </div>

            <!-- By Group -->
            <div id="group-recipients" class="st-recipient-panel">
                <select class="st-input-field" id="broadcastGroupSelect" name="group_id">
                    <option value="">-- Select Group --</option>
                    <?php foreach ($groups as $group) : ?>
                        <option value="<?= $group->id ?>"><?= $group->name ?> (<?= $group->total_member ?> members)</option>
                    <?php endforeach; ?>
                </select>
                <div class="st-info-box" id="groupInfo"></div>
            </div>

            <!-- Selected Contacts -->
            <div id="selected-recipients" class="st-recipient-panel">
                <input type="text" class="st-input-field" id="contactSearchBroadcast" placeholder="Search contact...">
                <div class="st-customer-selection-list">
                    <div id="contactSelectionList">
                    </div>
                </div>
                <div class="st-info-box">
                    <p>Selected Recipients: <strong id="selectedContactsCount">0</strong></p>
                </div>
            </div>
        </div>

        <!-- Message Composer Panel -->
        <div class="st-broadcast-section">
            <h3>2. Compose Message</h3>
            <div class="form-group">
                <label>Broadcast Title</label>
                <input type="text" class="form-control" id="broadcastTitle" name="title" placeholder="Enter broadcast title...">
            </div>
            <div class="form-group">
                <label>WhatsApp Message</label>
                <textarea class="form-control" id="broadcastMessage" name="content" placeholder="Write your message here..." rows="5"></textarea>
            </div>
            <div class="form-group">
                <label>Upload Image (Optional)</label>
                <div class="st-file-upload">
                    <input type="file" name="file" id="broadcastImage" accept="image/*" onchange="previewBroadcastImage(event)">
                    <button type="button" class="st-btn st-btn-secondary" onclick="document.getElementById('broadcastImage').click()">📸 Choose Image</button>
                </div>
            </div>
        </div>

        <!-- Preview Panel -->
        <div class="st-broadcast-section">
            <h3>3. Preview</h3>
            <div class="st-whatsapp-preview-st-card">
                <div class="st-preview-bubble">
                    <div id="previewImage" class="st-preview-image"></div>
                    <div id="previewText" class="st-preview-text">Your message will appear here...</div>
                </div>
                <div class="st-preview-info">
                    <p><strong>Recipients:</strong> <span id="previewRecipients">None selected</span></p>
                    <p><strong>Title:</strong> <span id="previewTitle"></span></p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="st-broadcast-section st-broadcast-actions">
            <button type="button" class="st-btn st-btn-secondary" onclick="previewBroadcast()">👁️ Preview Message</button>
            <button type="button" class="st-btn st-btn-secondary" onclick="saveBroadcastDraft()">💾 Save Draft</button>
            <button type="button" class="st-btn st-btn-success st-btn-large" onclick="sendBroadcast()">📱 Send Broadcast</button>
            <button type="button" class="st-btn st-btn-secondary" onclick="resetBroadcastForm()">🔄 Reset Form</button>
        </div>
    </div>
</form>

<!-- Broadcast History -->
<div class="st-section-box" style="margin-top: 30px;">
    <h3>Broadcast History</h3>
    <table class="st-data-table" id="broadcastHistoryTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>Broadcast Title</th>
                <th>Recipient Type</th>
                <th>Total Recipients</th>
                <th>Attachment</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="broadcastHistoryBody">
            <?php $a = 1;
            foreach ($broadcasts as $broadcast) : ?>
                <tr>
                    <td><?= $a++ ?></td>
                    <td><?= $broadcast->created_at ?></td>
                    <td><?= $broadcast->title ?></td>
                    <td><?= $broadcast->type ?></td>
                    <td><?= $broadcast->total_recipient ?></td>

                    <!-- if file is not null -->
                    <?php if ($broadcast->file) : ?>
                        <td><img src="<?= asset_url('uploads/broadcasts/' . $broadcast->file) ?>" alt="<?= $broadcast->title ?>" width="100"></td>
                    <?php else : ?>
                        <td>No File</td>
                    <?php endif; ?>

                    <td>
                        <?php if ($broadcast->status === 'Sent') : ?>
                            <span class="st-badge active"><?= $broadcast->status ?></span>
                        <?php else : ?>
                            <span class="st-badge inactive"><?= $broadcast->status ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?= $broadcast->created_by ?></td>
                    <td><button class="st-btn-view st-btn-small" onclick="viewBroadcast('<?= $broadcast->content ?>')">View</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>

<script>
    // Customers Data
    const customersData = <?= json_encode($customers) ?>;


    // Broadcast History Data
    const broadcastHistoryData = [{
            id: 1,
            date: '2026-03-10 10:30',
            title: 'New Product Launch - Sugar Donut',
            recipientType: 'All Contacts',
            recipients: 5,
            attachment: 'Image',
            status: 'Sent',
            createdBy: 'Admin'
        },
        {
            id: 2,
            date: '2026-03-09 14:15',
            title: 'Weekend Promo - 20% Discount',
            recipientType: 'By Group',
            recipients: 3,
            attachment: 'No',
            status: 'Sent',
            createdBy: 'Admin'
        },
        {
            id: 3,
            date: '2026-03-08 09:00',
            title: 'Spring Collection Announcement',
            recipientType: 'Selected',
            recipients: 1,
            attachment: 'Image',
            status: 'Draft',
            createdBy: 'Admin'
        },
    ];


    // ========================================
    // WHATSAPP BROADCAST PAGE
    // ========================================

    function loadBroadcastPage() {
        setupBroadcastTabs();
        loadBroadcastContactList();
        // populateBroadcastGroupSelect();
        updateAllContactsCount();
        // loadBroadcastHistory();
    }

    function setupBroadcastTabs() {
        document.querySelectorAll('.st-recipient-tab').forEach(btn => {
            btn.addEventListener('click', (e) => {
                document.querySelectorAll('.st-recipient-tab').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.st-recipient-panel').forEach(p => p.classList.remove('active'));

                btn.classList.add('active');
                const recipientType = btn.dataset.recipient;
                document.getElementById('hiddenRecipientType').value = recipientType;
                document.getElementById(recipientType + '-recipients').classList.add('active');
            });
        });
    }

    function loadBroadcastContactList() {
        const list = document.getElementById('contactSelectionList');
        list.innerHTML = '';

        customersData.forEach(customer => {
            const div = document.createElement('div');
            div.className = 'st-contact-checkbox-item';
            div.innerHTML = `
            <label>
                <input type="checkbox" value="${customer.id}" onchange="updateSelectedContactsCount()">
                <span>${customer.name} (${customer.phone})</span>
            </label>
        `;
            list.appendChild(div);
        });

        const searchInput = document.getElementById('contactSearchBroadcast');
        if (searchInput) {
            searchInput.addEventListener('keyup', () => {
                const searchTerm = searchInput.value.toLowerCase();
                document.querySelectorAll('.st-contact-checkbox-item').forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }
    }

    function populateBroadcastGroupSelect() {
        const select = document.getElementById('broadcastGroupSelect');
        select.innerHTML = '<option value="">-- Select Group --</option>';
        groupsData.forEach(group => {
            const option = document.createElement('option');
            option.value = group.name;
            option.textContent = `${group.name} (${customersData.filter(c => c.group === group.name).length} members)`;
            select.appendChild(option);
        });

        select.addEventListener('change', () => {
            const groupName = select.value;
            const groupInfo = document.getElementById('groupInfo');
            if (groupName) {
                const members = customersData.filter(c => c.group === groupName).length;
                groupInfo.innerHTML = `<p>📢 <strong>${groupName}</strong></p><p>Total Members: <strong>${members}</strong></p>`;
            } else {
                groupInfo.innerHTML = '';
            }
        });
    }

    function updateAllContactsCount() {
        document.getElementById('allContactsCount').textContent = <?= count($customers) ?>;
    }

    function updateSelectedContactsCount() {
        const checked = document.querySelectorAll('#contactSelectionList input[type="checkbox"]:checked').length;
        document.getElementById('selectedContactsCount').textContent = checked;
        selectedCustomersForBroadcast = Array.from(document.querySelectorAll('#contactSelectionList input[type="checkbox"]:checked')).map(cb => parseInt(cb.value));
    }

    function previewBroadcastImage(event) {
        const file = event.target.files[0];
        if (file) {
            broadcastImageFile = file;
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('previewImage').innerHTML = `<img src="${e.target.result}" alt="preview">`;
            };
            reader.readAsDataURL(file);
        }
    }

    // onkeyup broadcastTitle and broadcastMessage to previewBroadcast
    document.getElementById('broadcastTitle').addEventListener('keyup', previewBroadcast);
    document.getElementById('broadcastMessage').addEventListener('keyup', previewBroadcast);

    function previewBroadcast() {
        const title = document.getElementById('broadcastTitle').value || 'Untitled Broadcast';
        const message = document.getElementById('broadcastMessage').value || 'No message';
        const recipientTab = document.querySelector('.st-recipient-tab.active').dataset.recipient;

        let recipientText = 'None selected';
        if (recipientTab === 'all') {
            recipientText = `All ${customersData.length} contacts`;
        } else if (recipientTab === 'group') {
            const groupName = document.getElementById('broadcastGroupSelect').value;
            const count = customersData.filter(c => c.group === groupName).length;
            recipientText = groupName ? `${groupName} (${count} members)` : 'None selected';
        } else if (recipientTab === 'selected') {
            recipientText = `${selectedCustomersForBroadcast.length} selected contact(s)`;
        }

        document.getElementById('previewText').textContent = message;
        document.getElementById('previewTitle').textContent = title;
        document.getElementById('previewRecipients').textContent = recipientText;

        showToast('Preview updated!');
    }

    function saveBroadcastDraft() {
        const title = document.getElementById('broadcastTitle').value;
        if (!title) {
            showToast('Please enter a broadcast title!');
            return;
        }

        const newBroadcast = {
            id: Math.max(...broadcastHistoryData.map(b => b.id), 0) + 1,
            date: new Date().toLocaleString('id-ID'),
            title: title,
            recipientType: 'Draft',
            recipients: 0,
            attachment: broadcastImageFile ? 'Image' : 'No',
            status: 'Draft',
            createdBy: 'Admin'
        };

        broadcastHistoryData.push(newBroadcast);
        loadBroadcastHistory();
        showToast('Broadcast saved as draft!');
    }

    function sendBroadcast() {
        const title = document.getElementById('broadcastTitle').value;
        const message = document.getElementById('broadcastMessage').value;

        if (!title) {
            showToast('Please enter a broadcast title!');
            return;
        }

        if (!message) {
            showToast('Please enter a message!');
            return;
        }

        const recipientType = document.getElementById('hiddenRecipientType').value;

        if (recipientType === 'group') {
            const groupSelect = document.getElementById('broadcastGroupSelect');
            if (!groupSelect.value) {
                showToast('Please select a group!');
                return;
            }
        } else if (recipientType === 'selected') {
            const selected = Array.from(document.querySelectorAll('#contactSelectionList input[type="checkbox"]:checked')).map(cb => cb.value);
            if (selected.length === 0) {
                showToast('Please select at least one contact!');
                return;
            }
            document.getElementById('hiddenSelectedCustomers').value = selected.join(',');
        }

        if (confirm('Are you sure you want to send this broadcast?')) {
            document.getElementById('broadcastForm').submit();
        }
    }

    function resetBroadcastForm() {
        document.getElementById('broadcastTitle').value = '';
        document.getElementById('broadcastMessage').value = '';
        document.getElementById('broadcastImage').value = '';
        document.getElementById('broadcastNotes').value = '';
        document.getElementById('previewImage').innerHTML = '';
        document.getElementById('previewText').textContent = 'Your message will appear here...';
        document.getElementById('previewTitle').textContent = '-';
        document.getElementById('previewRecipients').textContent = 'None selected';
        broadcastImageFile = null;
        selectedCustomersForBroadcast = [];
        document.querySelectorAll('#contactSelectionList input[type="checkbox"]').forEach(cb => cb.checked = false);
        updateSelectedContactsCount();
    }

    function loadBroadcastHistory() {
        const tbody = document.getElementById('broadcastHistoryBody');
        tbody.innerHTML = '';

        broadcastHistoryData.forEach((broadcast, index) => {
            const statusBadge = broadcast.status === 'Sent' ?
                '<span class="st-badge active">Sent</span>' :
                '<span class="st-badge inactive">Draft</span>';

            const row = document.createElement('tr');
            row.innerHTML = `
            <td>${index + 1}</td>
            <td>${broadcast.date}</td>
            <td>${broadcast.title}</td>
            <td>${broadcast.recipientType}</td>
            <td>${broadcast.recipients}</td>
            <td>${broadcast.attachment}</td>
            <td>${statusBadge}</td>
            <td>${broadcast.createdBy}</td>
            <td><button class="btn-view btn-small" onclick="viewBroadcast(${broadcast.id})">View</button></td>
        `;
            tbody.appendChild(row);
        });
    }

    function viewBroadcast(id) {
        const broadcast = broadcastHistoryData.find(b => b.id === id);
        if (broadcast) {
            alert(`Broadcast: ${broadcast.title}\nStatus: ${broadcast.status}\nRecipients: ${broadcast.recipients}\nDate: ${broadcast.date}`);
        }
    }


    loadBroadcastPage();

    setDataTables('#broadcastHistoryTable');
</script>
<?= $this->endSection() ?>