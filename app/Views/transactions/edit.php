<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= temp_lang('app.edit'); ?> <?= esc($title); ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url($link); ?>"><?= esc($title); ?></a></li>
                    <li class="breadcrumb-item active"><?= temp_lang('app.edit'); ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <form action="<?= base_url($link . '/' . esc($transaction->id)); ?>" method="post" enctype="multipart/form-data" id="transactionForm">
            <?= csrf_field(); ?>
            <input type='hidden' name='_method' value='PUT' />

            <div class="row">
                <!-- Left Column: Customer & Details -->
                <div class="col-12 col-xl-4">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">Order Details</h3>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <label for="order_date"><?= temp_lang('transactions.order_date'); ?> <small class="text-danger">*</small></label>
                                <input type="date" class="form-control" id="order_date" name="order_date" value="<?= old('order_date', date('Y-m-d', strtotime($transaction->order_date))); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="schedule_date"><?= temp_lang('transactions.schedule_date'); ?></label>
                                <input type="date" class="form-control" id="schedule_date" name="schedule_date" value="<?= old('schedule_date', $transaction->schedule_date ? date('Y-m-d', strtotime($transaction->schedule_date)) : ''); ?>">
                            </div>

                            <div class="form-group">
                                <label for="delivery_date"><?= temp_lang('transactions.delivery_date'); ?></label>
                                <input type="date" class="form-control" id="delivery_date" name="delivery_date" value="<?= old('delivery_date', $transaction->delivery_date ? date('Y-m-d', strtotime($transaction->delivery_date)) : ''); ?>">
                            </div>

                            <div class="form-group custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="is_new_customer" name="is_new_customer" value="1" <?= old('is_new_customer') ? 'checked' : ''; ?>>
                                <label class="custom-control-label" for="is_new_customer"> <?= temp_lang('app.new'); ?> <?= temp_lang('customers.customer'); ?></label>
                            </div>

                            <div id="existing_customer_div" class="<?= old('is_new_customer') ? 'd-none' : ''; ?>">
                                <div class="form-group">
                                    <label for="customer_id"><?= temp_lang('app.select'); ?> <?= temp_lang('customers.customer'); ?></label>
                                    <select class="form-control select2" id="customer_id" name="customer_id">
                                        <option value="">- <?= temp_lang('app.select'); ?> <?= temp_lang('customers.customer'); ?> -</option>
                                        <?php foreach ($customers as $customer): ?>
                                            <option value="<?= $customer->id; ?>" <?= (old('customer_id', $transaction->customer_id) == $customer->id) ? 'selected' : ''; ?>><?= esc($customer->name); ?> - <?= esc($customer->phone); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div id="new_customer_div" class="<?= old('is_new_customer') ? '' : 'd-none'; ?>">
                                <div class="form-group">
                                    <label for="customer_name"><?= temp_lang('customers.customer'); ?> <small class="text-danger">*</small></label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?= old('customer_name'); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="customer_phone"><?= temp_lang('customers.phone'); ?> <small class="text-danger">*</small></label>
                                    <input type="text" class="form-control" id="customer_phone" name="customer_phone" value="<?= old('customer_phone'); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="customer_address"><?= temp_lang('customers.address'); ?></label>
                                    <textarea class="form-control" id="customer_address" name="customer_address"><?= old('customer_address'); ?></textarea>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group">
                                <label for="status"><?= temp_lang('transactions.status'); ?></label>
                                <?php
                                $statuses = ['pending', 'waiting_payment', 'paid', 'processing', 'delivered', 'cancelled'];
                                $currentStatus = old('status', $transaction->status);
                                ?>
                                <select class="form-control" id="status" name="status">
                                    <?php foreach ($statuses as $st): ?>
                                        <option value="<?= $st ?>" <?= $currentStatus == $st ? 'selected' : '' ?>><?= ucfirst(str_replace('_', ' ', $st)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="payment_status"><?= temp_lang('transactions.payment_status'); ?></label>
                                <?php
                                $pStatuses = ['unpaid', 'partial', 'paid', 'refunded'];
                                $currentPStatus = old('payment_status', $transaction->payment_status);
                                ?>
                                <select class="form-control" id="payment_status" name="payment_status">
                                    <?php foreach ($pStatuses as $pst): ?>
                                        <option value="<?= $pst ?>" <?= $currentPStatus == $pst ? 'selected' : '' ?>><?= ucfirst($pst) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <hr>

                            <div class="form-group">
                                <label for="note"><?= temp_lang('transactions.note'); ?></label>
                                <textarea class="form-control" id="note" name="note" rows="3"><?= old('note', $transaction->note); ?></textarea>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Right Column: Products & Totals -->
                <div class="col-12 col-xl-8">
                    <div class="card">
                        <div class="card-header bg-secondary">
                            <h3 class="card-title"><?= temp_lang('transactions.order_items'); ?></h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap" id="productsTable">
                                <thead>
                                    <tr>
                                        <th><?= temp_lang('products.product'); ?></th>
                                        <th width="15%"><?= temp_lang('products.price'); ?></th>
                                        <th width="15%"><?= temp_lang('products.qty'); ?></th>
                                        <th width="20%"><?= temp_lang('transactions.subtotal_price'); ?></th>
                                        <th width="10%"><button type="button" class="btn btn-sm btn-success" id="addRow"><i class="fas fa-plus"></i></button></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty(old('product_id'))): ?>
                                        <?php foreach ($details as $detail): ?>
                                            <tr>
                                                <td>
                                                    <select class="form-control product-select" name="product_id[]" required onchange="updatePrice(this)">
                                                        <option value="">- <?= temp_lang('app.select'); ?> <?= temp_lang('products.product'); ?> -</option>
                                                        <?php foreach ($products as $product): ?>
                                                            <?php
                                                            // Calculate effectively available stock, considering the stock already reserved in this detail line
                                                            $effectiveStock = $product->qty;
                                                            if ($detail->product_id == $product->id) {
                                                                $effectiveStock += $detail->qty;
                                                            }
                                                            ?>
                                                            <option value="<?= $product->id; ?>" data-price="<?= $product->price; ?>" data-stock="<?= $effectiveStock; ?>" <?= $detail->product_id == $product->id ? 'selected' : ''; ?>>
                                                                <?= esc($product->name); ?> (Stock: <?= $effectiveStock; ?>)
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td><input type="number" step="0.01" class="form-control product-price" name="price[]" readonly value="<?= esc($detail->price) ?>"></td>
                                                <td><input type="number" class="form-control product-qty" name="qty[]" min="1" max="<?= $effectiveStock ?? 999999 ?>" value="<?= esc($detail->qty) ?>" required onchange="updateSubtotal(this)" onkeyup="updateSubtotal(this)"></td>
                                                <td><input type="text" class="form-control product-subtotal readonly" readonly value="<?= esc($detail->total_price) ?>"></td>
                                                <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <!-- Need to implement JS repopulation if validation fails, or assume the user handles it -->
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right"><?= temp_lang('transactions.discount_total'); ?></th>
                                        <th><input type="number" step="0.01" class="form-control" id="discount_total" name="discount_total" value="<?= old('discount_total', $transaction->discount_total); ?>" onchange="calculateGrandTotal()"></th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right"><?= temp_lang('transactions.tax_total'); ?></th>
                                        <th><input type="number" step="0.01" class="form-control" id="tax_total" name="tax_total" value="<?= old('tax_total', $transaction->tax_total); ?>" onchange="calculateGrandTotal()"></th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right"><?= temp_lang('transactions.grand_total'); ?></th>
                                        <th><input type="text" class="form-control font-weight-bold readonly" id="grand_total" readonly value="<?= esc($transaction->total_amount) ?>"></th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right"><?= temp_lang('transactions.paid_amount'); ?></th>
                                        <th><input type="number" step="0.01" class="form-control" id="paid_amount" name="paid_amount" value="<?= old('paid_amount', $transaction->paid_amount); ?>"></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><?= temp_lang('app.update'); ?></button>
                            <a href="<?= base_url($link); ?>" class="btn btn-secondary"><?= temp_lang('app.cancel'); ?></a>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</section>

<!-- Product Options Template -->
<select id="productOptions" style="display:none;">
    <option value="">- Select Product -</option>
    <?php foreach ($products as $product): ?>
        <option value="<?= $product->id; ?>" data-price="<?= $product->price; ?>" data-stock="<?= $product->qty; ?>"><?= esc($product->name); ?> (Stock: <?= $product->qty; ?>)</option>
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

        // Add Product Row
        $('#addRow').click(function() {
            var options = $('#productOptions').html();
            var row = `<tr>
                <td><select class="form-control product-select" name="product_id[]" required onchange="updatePrice(this)">${options}</select></td>
                <td><input type="number" step="0.01" class="form-control product-price" name="price[]" readonly></td>
                <td><input type="number" class="form-control product-qty" name="qty[]" min="1" value="1" required onchange="updateSubtotal(this)" onkeyup="updateSubtotal(this)"></td>
                <td><input type="text" class="form-control product-subtotal readonly" readonly></td>
                <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
            </tr>`;
            $('#productsTable tbody').append(row);
        });

        // Remove row
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            calculateGrandTotal();
        });

        calculateGrandTotal();

    });

    function updatePrice(sel) {
        var selectedOption = $(sel).find(':selected');
        var price = selectedOption.data('price');
        var stock = selectedOption.data('stock');
        var tr = $(sel).closest('tr');

        tr.find('.product-price').val(price);

        var qtyInput = tr.find('.product-qty');
        qtyInput.attr('max', stock);

        // if user already typed a quantity larger than new stock, reset it
        if (parseFloat(qtyInput.val()) > stock) {
            qtyInput.val(stock);
        }

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
        tr.find('.product-subtotal').val(subtotal.toFixed(2));
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        var subtotalAll = 0;
        $('.product-subtotal').each(function() {
            subtotalAll += parseFloat($(this).val()) || 0;
        });

        var discount = parseFloat($('#discount_total').val()) || 0;
        var tax = parseFloat($('#tax_total').val()) || 0;

        var grandTotal = (subtotalAll - discount) + tax;
        $('#grand_total').val(grandTotal.toFixed(2));
    }
</script>
<?= $this->endSection('scripts') ?>