<?= $this->extend('template/index') ?>


<?= $this->section('head') ?>
<style>
    #menu-list-container ul {
        list-style: none;
        padding-left: 20px;
        padding-top: 5px;
        /* Indentasi untuk submenu */
        margin-bottom: 0;
    }

    #menu-list-container li {
        background-color: #f8f9fc;
        border: 1px solid #e3e6f0;
        padding: 10px 15px;
        margin-bottom: 5px;
        cursor: grab;
        /* display: flex;  */
        align-items: center;
        justify-content: space-between;
    }

    #menu-list-container li.sortable-ghost {
        /* Class yang ditambahkan SortableJS saat drag */
        opacity: 0.5;
        background-color: #e9ecef;
    }

    #menu-list-container li .menu-item-title {
        flex-grow: 1;
    }

    #menu-list-container li .menu-item-id {
        font-size: 0.8em;
        color: #6c757d;
        margin-left: 10px;
    }
</style>
<?= $this->endSection('head') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Order <?= $title; ?></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Home</a></li>
                    <li class="breadcrumb-item">Data <?= $title; ?></li>
                    <li class="breadcrumb-item active">Order</li>
                </ol>
            </div>
            <!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-12">

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="alert alert-info" role="alert">
                            Drag menu items to arrange their order and place them under the parent menu.
                        </div>

                        <div id="menu-list-container" class="list-group">
                            <?= view_cell('\App\Libraries\MenuCells::renderSortableMenu', ['menus' => $menus]) ?>
                        </div>

                        <hr>
                        <div class="text-right">
                            <button id="saveMenuOrder" class="btn btn-primary">Save Order Menu</button>
                            <a href="<?= base_url($link); ?>" class="btn btn-secondary">Cancel</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>


    </div>
</section>
<?= $this->endSection('content') ?>

<?= $this->section('script') ?>
<script src="<?= asset_url(); ?>assets/plugins/sortablejs/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        var sortableList = [].slice.call(document.querySelectorAll('.sortable-list'));

        // Loop through each nested sortable element
        for (var i = 0; i < sortableList.length; i++) {
            new Sortable(sortableList[i], {
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
            });
        }

        const menuListContainer = document.getElementById('menu-list-container');

        function serializeMenu(container) {
            const menuData = [];
            Array.from(container.children).forEach(function(ul) {
                if (ul.tagName === 'UL') {
                    Array.from(ul.children).forEach(function(li) {
                        const menuId = li.dataset.id;
                        const childrenUl = li.querySelector('ul');
                        const item = {
                            id: menuId,
                            children: childrenUl ? serializeMenu(li) : [] // Rekursif
                        };
                        menuData.push(item);
                    });
                }
            });
            return menuData;
        }

        // Event listener untuk tombol Simpan
        document.getElementById('saveMenuOrder').addEventListener('click', function() {
            const orderedMenus = serializeMenu(menuListContainer);
            console.log('Data to be sent:', orderedMenus);

            // Kirim data ke backend menggunakan AJAX
            fetch('<?= site_url($link . '/updateOrder') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest', // Penting untuk CI isAJAX()
                        '<?= csrf_header() ?>': '<?= csrf_hash() ?>' // CSRF token untuk keamanan
                    },
                    body: JSON.stringify(orderedMenus)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        // Mungkin reload halaman atau update UI
                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving the data.');
                });
        });
    });
</script>
<?= $this->endSection() ?>