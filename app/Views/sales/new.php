<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<div class="st-sales-container">
    <form action="<?= base_url($link); ?>" method="post" enctype="multipart/form-data" id="transactionForm">
        <?= csrf_field(); ?>

        <div class="row">
            <!-- POS Panel (Left) -->
            <div class="col-12 col-xl-8">
                <div class="st-pos-panel mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="m-0">Point of Sale</h3>
                        <div class="st-badge active"><?= temp_lang('app.new'); ?> <?= esc($title); ?></div>
                    </div>

                    <div class="st-pos-controls">
                        <select class="st-input-field" id="productSelect">
                            <option value="">-- <?= temp_lang('app.select'); ?> <?= temp_lang('products.product'); ?> --</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= $product->id; ?>" data-price="<?= $product->price; ?>" data-stock="<?= $product->qty; ?>">
                                    <?= esc($product->name); ?> (Rp <?= number_format($product->price, 0, ',', '.'); ?>) - Stock: <?= $product->qty; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" class="st-input-field small" id="qtyInput" placeholder="Qty" min="1" value="1">
                        <button type="button" class="st-btn st-btn-secondary" id="addSalesItemBtn">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>

                    <!-- Transaction Items -->
                    <div class="st-section-box p-0 mt-4">
                        <div class="p-3">
                            <h4 class="m-0"><?= temp_lang('transactions.order_items'); ?></h4>
                        </div>
                        <div class="table-responsive">
                            <table class="st-data-table compact" id="productsTable">
                                <thead>
                                    <tr>
                                        <th><?= temp_lang('products.product'); ?></th>
                                        <th width="15%"><?= temp_lang('products.price'); ?></th>
                                        <th width="15%"><?= temp_lang('products.qty'); ?></th>
                                        <th width="20%"><?= temp_lang('transactions.subtotal_price'); ?></th>
                                        <th width="10%"><?= temp_lang('app.action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Rows added via JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Customer Info Section -->
                    <div class="st-section-box mt-4">
                        <h3><?= temp_lang('transactions.customer_info'); ?></h3>

                        <div class="st-form-row">
                            <div class="st-form-group">
                                <label for="order_date"><?= temp_lang('transactions.order_date'); ?> <small class="text-danger">*</small></label>
                                <input type="date" readonly class="st-input-field" id="order_date" name="order_date" value="<?= old('order_date', date('Y-m-d')); ?>" required>
                            </div>
                            <div class="st-form-group">
                                <label for="schedule_date"><?= temp_lang('transactions.schedule_date'); ?></label>
                                <input type="date" class="st-input-field" id="schedule_date" name="schedule_date" value="<?= old('schedule_date', date('Y-m-d')); ?>">
                            </div>
                        </div>

                        <div class="st-form-group custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="is_new_customer" name="is_new_customer" value="1" <?= old('is_new_customer') ? 'checked' : ''; ?>>
                            <label class="custom-control-label" for="is_new_customer"><?= temp_lang('app.new'); ?> <?= temp_lang('customers.customer'); ?></label>
                        </div>

                        <div id="existing_customer_div" class="<?= old('is_new_customer') ? 'd-none' : ''; ?>">
                            <div class="st-form-group">
                                <label for="customer_id"><?= temp_lang('app.select'); ?> <?= temp_lang('customers.customer'); ?></label>
                                <select class="st-input-field select2" id="customer_id" name="customer_id" style="width: 100%;">
                                    <option value="">- <?= temp_lang('app.select'); ?> <?= temp_lang('customers.customer'); ?> -</option>
                                    <?php foreach ($customers as $customer): ?>
                                        <option value="<?= $customer->id; ?>" <?= old('customer_id') == $customer->id ? 'selected' : ''; ?>><?= esc($customer->name); ?> - <?= esc($customer->phone); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div id="new_customer_div" class="<?= old('is_new_customer') ? '' : 'd-none'; ?>">
                            <div class="st-form-row">
                                <div class="st-form-group">
                                    <label for="customer_name"><?= temp_lang('customers.customer'); ?> <small class="text-danger">*</small></label>
                                    <input type="text" class="st-input-field" id="customer_name" name="customer_name" value="<?= old('customer_name'); ?>">
                                </div>
                                <div class="st-form-group">
                                    <label for="customer_phone"><?= temp_lang('customers.phone'); ?> <small class="text-danger">*</small></label>
                                    <input type="text" class="st-input-field" id="customer_phone" name="customer_phone" value="<?= old('customer_phone'); ?>">
                                </div>
                            </div>

                            <div class="st-form-row">
                                <div class="st-form-group">
                                    <label for="group_id">Group <small class="text-danger">*</small></label>
                                    <div class="d-flex gap-2 align-items-center">
                                        <select class="st-input-field" id="group_id" name="group_id" style="flex: 1;">
                                            <option value="">- Select Group -</option>
                                            <?php foreach ($groups as $group): ?>
                                                <option value="<?= $group->id; ?>"><?= esc($group->name); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="st-btn st-btn-info st-btn-small" id="toggleNewGroupBtn" title="Create New Group"><i class="fas fa-plus"></i></button>
                                    </div>
                                    <div id="new_group_input_div" class="mt-2 d-none">
                                        <input type="text" class="st-input-field" id="new_group_name" name="new_group_name" placeholder="Enter New Group Name">
                                    </div>
                                </div>
                                <div class="st-form-group">
                                    <label for="category">Category <small class="text-danger">*</small></label>
                                    <!-- category input text not select -->
                                    <input type="text" class="st-input-field" id="category" name="category">
                                </div>
                            </div>

                            <div class="st-form-group">
                                <label for="customer_address"><?= temp_lang('customers.address'); ?></label>
                                <textarea class="st-input-field" id="customer_address" name="customer_address"><?= old('customer_address'); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- list transactions history nya, hide in mobile -->
                <div class="st-pos-panel d-none d-md-block">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="m-0">Recent Transactions</h3>
                    </div>
                    <div class="st-section-box table-responsive">
                        <table class="st-data-table w-100" id="historyTable">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Date</th>
                                    <th>Transaction No</th>
                                    <th>Total Items</th>
                                    <th>Total Payment</th>
                                    <th>Payment Method</th>
                                    <th>Cashier</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($history)): ?>
                                    <?php foreach ($history as $index => $row): ?>
                                        <tr>
                                            <td><?= $index + 1; ?></td>
                                            <td><?= date('d/m/Y H:i:s', strtotime($row->created_at)); ?></td>
                                            <td><strong><?= esc($row->code); ?></strong></td>
                                            <td><?= number_format($row->total_items, 0); ?> items</td>
                                            <td>Rp <?= number_format($row->total_amount, 0, ',', '.'); ?></td>
                                            <td><?= esc($row->payment_method_name ?? '-'); ?></td>
                                            <td><?= esc($row->cashier_name ?? 'System'); ?></td>
                                            <td>
                                                <?php
                                                $statusClass = 'pending';
                                                if ($row->status === 'delivered' || $row->status === 'completed' || $row->status === 'paid') $statusClass = 'active';
                                                if ($row->status === 'cancelled') $statusClass = 'inactive';
                                                ?>
                                                <div class="st-badge <?= $statusClass; ?>"><?= ucfirst(str_replace('_', ' ', $row->status)); ?></div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">No recent transactions found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Payment Summary (Right) -->
            <div class="col-12 col-xl-4">
                <div class="st-pos-panel st-sticky-top" style="top: 20px;">
                    <h3>Summary</h3>

                    <div class="st-payment-summary">
                        <div class="st-summary-row border-bottom-0 pb-1">
                            <span>Subtotal:</span>
                            <span id="subtotalDisplay">Rp 0</span>
                        </div>

                        <div class="st-summary-row border-bottom-0 pb-1">
                            <span><?= temp_lang('transactions.discount_total'); ?>:</span>
                            <input type="number" step="0.01" class="st-input-field small" id="discount_total" name="discount_total" value="<?= old('discount_total', 0); ?>" onchange="calculateGrandTotal()">
                        </div>

                        <div class="st-summary-row border-bottom-0 pb-1">
                            <span><?= temp_lang('transactions.tax_total'); ?>:</span>
                            <input type="number" step="0.01" class="st-input-field small" id="tax_total" name="tax_total" value="<?= old('tax_total', 0); ?>" onchange="calculateGrandTotal()">
                        </div>

                        <div class="st-summary-row total mb-3 pb-3">
                            <span>Total:</span>
                            <span id="totalDisplay" class="h4 m-0">Rp 0</span>
                            <input type="hidden" id="grand_total" name="grand_total_hidden" value="0">
                        </div>

                        <hr>

                        <div class="mt-4">
                            <h5 class="mb-3">Payment Information</h5>
                            <div class="form-group">
                                <label for="payment_method_id"><?= temp_lang('transactions.method'); ?></label>
                                <select class="form-control" id="payment_method_id" name="payment_method_id">
                                    <?php foreach ($paymentMethods as $pm): ?>
                                        <option value="<?= $pm->id; ?>"><?= esc($pm->name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="payment_amount"><?= temp_lang('transactions.amount'); ?></label>
                                <input type="number" step="0.01" class="form-control" id="payment_amount" name="payment_amount" value="<?= old('payment_amount', 0); ?>" onchange="updateChange()" onkeyup="updateChange()">
                            </div>

                            <div class="st-summary-row border-0 py-1">
                                <span>Paid:</span>
                                <input type="number" readonly step="0.01" class="st-input-group p-0 text-right bg-transparent border-0" style="width: 120px;" id="paid_amount" name="paid_amount" value="<?= old('paid_amount', 0); ?>">
                            </div>

                            <div class="st-summary-row change border-0 pt-0">
                                <span>Remaining/Change:</span>
                                <span id="changeDisplay" class="font-weight-bold">Rp 0</span>
                            </div>

                            <div class="form-group mt-3">
                                <label for="payment_proof"><?= temp_lang('transactions.proof'); ?></label>
                                <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf">
                            </div>

                            <div class="form-group">
                                <label for="payment_reference"><?= temp_lang('transactions.reference'); ?></label>
                                <input type="text" class="form-control" id="payment_reference" name="payment_reference" placeholder="Reference No.">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label for="status"><?= temp_lang('transactions.status'); ?></label>
                            <select class="form-control" id="status" name="status">
                                <?php $statuses = ['pending', 'waiting_payment', 'paid', 'processing', 'delivered', 'completed', 'cancelled']; ?>
                                <?php foreach ($statuses as $st): ?>
                                    <option value="<?= $st; ?>" <?= old('status', 'completed') == $st ? 'selected' : ''; ?>><?= ucfirst(str_replace('_', ' ', $st)); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="payment_status"><?= temp_lang('transactions.payment_status'); ?></label>
                            <select class="form-control" id="payment_status" name="payment_status">
                                <?php $pStatuses = ['unpaid', 'partial', 'paid', 'refunded']; ?>
                                <?php foreach ($pStatuses as $pst): ?>
                                    <option value="<?= $pst; ?>" <?= old('payment_status', 'paid') == $pst ? 'selected' : ''; ?>><?= ucfirst($pst); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="note"><?= temp_lang('transactions.note'); ?></label>
                            <textarea class="form-control" id="note" name="note" rows="2"><?= old('note'); ?></textarea>
                        </div>

                        <button type="submit" class="st-btn st-btn-primary st-btn-block mt-4 py-3">
                            <i class="fas fa-save"></i> <?= temp_lang('app.save'); ?> Transaction
                        </button>
                        <a href="<?= base_url($link); ?>" class="st-btn st-btn-secondary st-btn-block mt-2">
                            <i class="fas fa-times"></i> <?= temp_lang('app.cancel'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Product Options Template -->
<select id="productOptionsTemplate" style="display:none;">
    <option value="">- <?= temp_lang('app.select'); ?> <?= temp_lang('products.product'); ?> -</option>
    <?php foreach ($products as $product): ?>
        <option value="<?= $product->id; ?>" data-price="<?= $product->price; ?>" data-stock="<?= $product->qty; ?>"><?= esc($product->name); ?></option>
    <?php endforeach; ?>
</select>

<?= $this->endSection('content') ?>

<?= $this->section('script') ?>
<script>
    $(function() {
        // Toggle new customer form
        $('#is_new_customer').on('change', function() {
            if ($(this).is(':checked')) {
                $('#new_customer_div').removeClass('d-none');
                $('#existing_customer_div').addClass('d-none');
                $('#customer_id').prop('required', false);
                $('#customer_name').prop('required', true);
            } else {
                $('#new_customer_div').addClass('d-none');
                $('#existing_customer_div').removeClass('d-none');
                $('#customer_id').prop('required', true);
                $('#customer_name').prop('required', false);
            }
        });

        // Toggle New Group Input
        $('#toggleNewGroupBtn').click(function() {
            var inputDiv = $('#new_group_input_div');
            inputDiv.toggleClass('d-none');
            if (inputDiv.hasClass('d-none')) {
                $('#group_id').prop('disabled', false);
                $('#new_group_name').val('').prop('required', false);
            } else {
                $('#group_id').prop('disabled', true).val('');
                $('#new_group_name').prop('required', true).focus();
            }
        });

        // Add Product via POS UI
        $('#addSalesItemBtn').click(function() {
            var productId = $('#productSelect').val();
            var qty = parseInt($('#qtyInput').val()) || 1;

            if (!productId) {
                alert('Please select a product!');
                return;
            }

            var selectedOption = $('#productSelect').find(':selected');
            var price = selectedOption.data('price');
            var stock = selectedOption.data('stock');

            var existingRow = $('#productsTable tbody tr').filter(function() {
                return $(this).find('.product-select-inner').val() == productId;
            });

            if (existingRow.length > 0) {
                var currentQty = parseInt(existingRow.find('.product-qty').val());
                var newQty = currentQty + qty;
                if (newQty > stock) newQty = stock;
                existingRow.find('.product-qty').val(newQty).trigger('change');
            } else {
                addProductRow(productId, price, qty, stock);
            }

            $('#productSelect').val('').trigger('change');
            $('#qtyInput').val('1');
        });

        function addProductRow(productId, price, qty, stock) {
            var options = $('#productOptionsTemplate').html();
            var row = `<tr>
                <td>
                    <select class="st-input-field product-select-inner" name="product_id[]" required onchange="updatePrice(this)">${options}</select>
                </td>
                <td><input type="number" step="1" class="st-input-field product-price" name="price[]" value="${price}" readonly></td>
                <td><input type="number" class="st-input-field product-qty" name="qty[]" min="1" max="${stock}" value="${qty}" required onchange="updateSubtotal(this)" onkeyup="updateSubtotal(this)"></td>
                <td><input type="text" class="st-input-field product-subtotal" readonly value="${(price * qty).toFixed(0)}"></td>
                <td><button type="button" class="st-btn-delete st-btn-small remove-row"><i class="fas fa-trash"></i></button></td>
            </tr>`;

            var $row = $(row);
            $row.find('.product-select-inner').val(productId);
            $('#productsTable tbody').append($row);
            calculateGrandTotal();
        }

        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            calculateGrandTotal();
        });

    });

    function updatePrice(sel) {
        var selectedOption = $(sel).find(':selected');
        var price = selectedOption.data('price');
        var stock = selectedOption.data('stock');
        var tr = $(sel).closest('tr');
        tr.find('.product-price').val(price);
        tr.find('.product-qty').attr('max', stock);
        updateSubtotal(sel);
    }

    function updateSubtotal(elem) {
        var tr = $(elem).closest('tr');
        var price = parseFloat(tr.find('.product-price').val()) || 0;
        var qtyInput = tr.find('.product-qty');
        var qty = parseFloat(qtyInput.val()) || 0;
        var maxStock = parseFloat(qtyInput.attr('max')) || 0;

        if (qty > maxStock) {
            qtyInput.val(maxStock);
            qty = maxStock;
            alert('Cannot order more than available stock (' + maxStock + ')');
        }

        var subtotal = price * qty;
        tr.find('.product-subtotal').val(subtotal.toFixed(0));
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        var subtotalAll = 0;
        $('.product-subtotal').each(function() {
            subtotalAll += parseFloat($(this).val()) || 0;
        });

        $('#subtotalDisplay').text('Rp ' + subtotalAll.toLocaleString('id-ID'));

        var discount = parseFloat($('#discount_total').val()) || 0;
        var tax = parseFloat($('#tax_total').val()) || 0;

        var grandTotal = (subtotalAll - discount) + tax;
        $('#totalDisplay').text('Rp ' + grandTotal.toLocaleString('id-ID'));
        $('#grand_total').val(grandTotal.toFixed(0));

        updateChange();
    }

    function updateChange() {
        var grandTotal = parseFloat($('#grand_total').val()) || 0;
        var paymentAmount = parseFloat($('#payment_amount').val()) || 0;

        $('#paid_amount').val(paymentAmount.toFixed(0));

        var remaining = grandTotal - paymentAmount;

        if (remaining <= 0) {
            $('#changeDisplay').text('Rp ' + Math.abs(remaining).toLocaleString('id-ID') + ' (Change)');
            $('#changeDisplay').css('color', '#28a745');
        } else {
            $('#changeDisplay').text('Rp ' + remaining.toLocaleString('id-ID') + ' (Due)');
            $('#changeDisplay').css('color', '#dc3545');
        }

        if (paymentAmount > 0) {
            if (paymentAmount >= grandTotal) {
                $('#payment_status').val('paid');
            } else {
                $('#payment_status').val('partial');
            }
        } else {
            $('#payment_status').val('unpaid');
        }
    }
</script>
<?= $this->endSection() ?>