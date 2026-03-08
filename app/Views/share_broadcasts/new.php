<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= temp_lang('broadcasts.compose'); ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url($link); ?>"><?= $title; ?></a></li>
                    <li class="breadcrumb-item active"><?= temp_lang('broadcasts.compose'); ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <form action="<?= base_url($link); ?>" method="post">
            <?= csrf_field(); ?>
            <div class="row">
                <!-- Left Column: Form -->
                <div class="col-md-7">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><?= temp_lang('broadcasts.compose'); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label><?= temp_lang('broadcasts.select_template'); ?></label>
                                <select name="broadcast_id" id="broadcast_id" class="form-control select2" required style="width: 100%;">
                                    <option value=""><?= temp_lang('broadcasts.select_template'); ?></option>
                                    <?php foreach ($templates as $t): ?>
                                        <option value="<?= $t->id; ?>"><?= esc($t->title); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- <div id="variables_container" style="display: none;">
                                <hr>
                                <h5><?= temp_lang('broadcasts.variables'); ?></h5>
                                <div id="variables_list"></div>
                            </div> -->

                            <hr>
                            <div class="form-group">
                                <label><?= temp_lang('broadcasts.select_customers'); ?></label>
                                <div class="mb-2">
                                    <button type="button" class="btn btn-xs btn-info" id="select_all_customers">Select All</button>
                                    <button type="button" class="btn btn-xs btn-secondary" id="deselect_all_customers">Deselect All</button>
                                </div>
                                <select name="customer_ids[]" id="customer_ids" class="form-control select2" multiple="multiple" data-placeholder="<?= temp_lang('broadcasts.select_customers'); ?>" style="width: 100%;">
                                    <?php foreach ($customers as $c): ?>
                                        <option value="<?= $c->id; ?>"><?= esc($c->name); ?> (<?= esc($c->phone); ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="custom_phones"><?= temp_lang('broadcasts.custom_phone'); ?></label>
                                <select name="custom_phones[]" id="custom_phones" class="form-control select2-tags" multiple="multiple" data-placeholder="08123xxx or GroupID@g.us (Press Enter to add)" style="width: 100%;">
                                </select>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="<?= base_url($link); ?>" class="btn btn-secondary"><?= temp_lang('app.cancel'); ?></a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> <?= temp_lang('broadcasts.send'); ?></button>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Preview -->
                <div class="col-md-5">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title"><?= temp_lang('broadcasts.preview'); ?></h3>
                        </div>
                        <div class="card-body">
                            <div id="preview_box" style="background: #e9ecef; padding: 15px; border-radius: 8px; border-left: 5px solid #17a2b8; min-height: 200px; white-space: pre-wrap; font-family: sans-serif; position: relative;">
                                <div id="preview_content" class="text-muted"><em>Please select a template to see preview...</em></div>
                                <div style="position: absolute; bottom: 5px; right: 10px; font-size: 0.75rem; color: #6c757d;">WhatsApp Preview</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        let rawContent = "";
        let variableList = [];

        // Initialize Tags for Custom Phone
        $('.select2-tags').select2({
            theme: 'bootstrap4',
            tags: true,
            tokenSeparators: [',', ' ']
        });

        // Select All Customers
        $('#select_all_customers').on('click', function() {
            $('#customer_ids > option').prop('selected', true);
            $('#customer_ids').trigger('change');
        });

        // Deselect All Customers
        $('#deselect_all_customers').on('click', function() {
            $('#customer_ids > option').prop('selected', false);
            $('#customer_ids').trigger('change');
        });

        $('#broadcast_id').on('change', function() {
            const id = $(this).val();
            if (!id) {
                $('#variables_container').hide();
                $('#preview_content').html('<em>Please select a template to see preview...</em>').addClass('text-muted');
                rawContent = "";
                return;
            }

            $.ajax({
                url: '<?= base_url($link); ?>/get_variables/' + id,
                method: 'GET',
                success: function(response) {
                    rawContent = response.content;
                    variableList = response.variables;

                    let html = '';
                    variableList.forEach(v => {
                        html += `
                        <div class="form-group">
                            <label>{${v.name}}</label>
                            <input type="text" class="form-control var-input" data-var="${v.name}" name="vars[${v.name}]" placeholder="Enter value for ${v.name}">
                        </div>
                    `;
                    });

                    $('#variables_list').html(html);
                    if (variableList.length > 0) {
                        $('#variables_container').show();
                    } else {
                        $('#variables_container').hide();
                    }

                    updatePreview();
                }
            });
        });

        $(document).on('input', '.var-input', function() {
            updatePreview();
        });

        function updatePreview() {
            let content = rawContent;
            $('.var-input').each(function() {
                const varName = $(this).data('var');
                const val = $(this).val() || `<span class="bg-warning text-dark text-xs px-1" style="border-radius:2px;">{${varName}}</span>`;
                content = content.replace(new RegExp('{' + varName + '}', 'g'), val);
            });

            if (content) {
                $('#preview_content').html(content).removeClass('text-muted');
            } else {
                $('#preview_content').html('<em>Template content is empty</em>').addClass('text-muted');
            }
        }
    });
</script>
<?= $this->endSection() ?>