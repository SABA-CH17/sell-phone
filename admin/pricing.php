<?php
include("include/db-connect.php");
include("include/auth-check.php");

$blank_price = [
    "base" => 0,
    "storage_128" => 0, "storage_256" => 0, "storage_512" => 0,
    "condition_flawless" => 0, "condition_good" => 0, "condition_fair" => 0,
    "acc_charger" => 0, "acc_box" => 0, "acc_earbuds" => 0, "acc_warranty" => 0,
];

$price_fields = ["base", "storage_128", "storage_256", "storage_512",
    "condition_flawless", "condition_good", "condition_fair",
    "acc_charger", "acc_box", "acc_earbuds", "acc_warranty"];

function get_model_price($conn, $id, $blank_price) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM model_pricing WHERE model_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row ?: $blank_price;
}

function save_model_price($conn, $id, $data, $price_fields) {
    $columns = implode(", ", $price_fields);
    $placeholders = implode(", ", array_fill(0, count($price_fields), "?"));
    $updates = implode(", ", array_map(fn($f) => "$f = VALUES($f)", $price_fields));

    $sql = "INSERT INTO model_pricing (model_id, $columns) VALUES (?, $placeholders)
            ON DUPLICATE KEY UPDATE $updates";

    $stmt = mysqli_prepare($conn, $sql);
    $types = "i" . str_repeat("i", count($price_fields));
    $params = array_merge([$id], array_map(fn($f) => (int)($data[$f] ?? 0), $price_fields));
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    return mysqli_stmt_execute($stmt);
}


if (isset($_GET['ajax']) && $_GET['ajax'] === 'get') {
    header('Content-Type: application/json');
    $id = (int) $_GET['id'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM models WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $model = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$model) {
        http_response_code(404);
        echo json_encode(["error" => "Model not found"]);
        exit;
    }

    echo json_encode([
        "id"         => $id,
        "model_name" => $model['model_name'],
        "brand"      => $model['brand'],
        "price"      => get_model_price($conn, $id, $blank_price),
    ]);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax']) && $_POST['ajax'] === 'save') {
    header ('content-Type: application/json' );
    $id = (int) $_POST['id'];

    if (save_model_price($conn, $id, $_POST, $price_fields)) {
        echo json_encode(["success" => true, "id" => $id, "price" => get_model_price($conn, $id, $blank_price)]);
    } else {
        echo json_encode(["success" => false,"error" => mysqli_error($conn)]);
    }
    exit;
}

if (isset($_GET['ajax']) && $_GET['ajax'] === 'delete') {
    header('Content-Type: application/json');
    $id = (int) $_GET['id'];

    $stmt = mysqli_prepare($conn, "DELETE FROM model_pricing WHERE model_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    echo json_encode(["success" => true, "id" => $id]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax']) && $_POST['ajax'] === 'save') {
    header('Content-Type: application/json');
    $id = (int) $_POST['id'];

    if (save_model_price($conn, $id, $_POST, $price_fields)) {
        echo json_encode(["success" => true, "id" => $id, "price" => get_model_price($conn, $id, $blank_price)]);
    } else {
        echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
    }
    exit;
}

require_once('include/a-header.php');
require_once('section/sidebar.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $id = (int) $_POST['id'];
    save_model_price($conn, $id, $_POST, $price_fields);
    header("Location: pricing.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = mysqli_prepare($conn, "DELETE FROM model_pricing WHERE model_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: pricing.php");
    exit;
}

$models_result = mysqli_query($conn, "SELECT * FROM models ORDER BY brand, model_name");
$models = [];
if ($models_result) {
    while ($row = mysqli_fetch_assoc($models_result)) {
        $models[$row['id']] = $row;
    }
}
?>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
    <?php $editing_id = isset($_GET['edit']) ? (int) $_GET['edit'] : null;
$editing_price = $editing_id ? get_model_price($conn, $editing_id, $blank_price) : null; ?>
</button>


<div class="main-content">
    <div class="content-header">
        <div>
            <h1 class="main-h1">Model Pricing</h1>
            <p class="current-date">Set base price, storage, condition and accessory rates for each model</p>
        </div>
        <div class="admin-avatar">AD</div>
    </div>

    <?php
    include('section/edit-modal.php');
    ?>

    <div class="input-group flex-nowrap mb-3" style="max-width:320px;">
        <span class="input-group-text" style="background:#f9fafb;"><i class="fa-solid fa-magnifying-glass"></i></span>
        <input type="text" id="pricingSearchInput" class="form-control" placeholder="Search by model name or brand...">
    </div>

    <div class="table-responsive" style="border:1px solid #eef1f5; border-radius:14px;">
    <table class="table align-middle mb-0" style="font-size:12.5px; white-space:nowrap;">
        <thead style="background:#f9fafb;">
            <tr>
                <th rowspan="2" style="color:#0B1E3F; vertical-align:middle;">Model</th>
                <th rowspan="2" style="color:#797979c5; vertical-align:middle;">Base Price</th>
                <th colspan="3" class="text-center" style="color:#0B1E3F;">Storage</th>
                <th colspan="3" class="text-center" style="color:#0B1E3F;">Condition</th>
                <th colspan="4" class="text-center" style="color:#0B1E3F;">Accessories</th>
                <th rowspan="2" class="text-center" style="color:#0B1E3F; vertical-align:middle;">Actions</th>
            </tr>
            <tr>
                <th style="color:#797979c5;">128GB</th>
                <th style="color:#797979c5;">256GB</th>
                <th style="color:#797979c5;">512GB</th>
                <th style="color:#797979c5;">Flawless</th>
                <th style="color:#797979c5;">Good</th>
                <th style="color:#797979c5;">Fair/Cracked</th>
                <th style="color:#797979c5;">Charger</th>
                <th style="color:#797979c5;">Box</th>
                <th style="color:#797979c5;">Earbuds</th>
                <th style="color:#797979c5;">Warranty</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($models)): ?>
                <tr><td colspan="12" class="text-center text-muted py-3">No models found. Add models on the Models page first.</td></tr>
            <?php endif; ?>
            <?php foreach ($models as $id => $model): ?>
                <?php $p = get_model_price($conn, $id, $blank_price); ?>
                <tr id="row-<?php echo $id; ?>"
                    data-search="<?php echo htmlspecialchars(strtolower($model['model_name'] . ' ' . $model['brand'])); ?>">
                    <td class="fw-semibold" style="color:#333;">
                        <?php echo htmlspecialchars($model['model_name']); ?>
                        <br><span style="font-size:10.5px; color:#797979c5;"><?php echo htmlspecialchars($model['brand']); ?></span>
                    </td>
                    <td data-field="base"><?php echo $p['base']; ?></td>
                    <td data-field="storage_128"><?php echo $p['storage_128']; ?></td>
                    <td data-field="storage_256"><?php echo $p['storage_256']; ?></td>
                    <td data-field="storage_512"><?php echo $p['storage_512']; ?></td>
                    <td data-field="condition_flawless"><?php echo $p['condition_flawless']; ?></td>
                    <td data-field="condition_good"><?php echo $p['condition_good']; ?></td>
                    <td data-field="condition_fair"><?php echo $p['condition_fair']; ?></td>
                    <td data-field="acc_charger"><?php echo $p['acc_charger']; ?></td>
                    <td data-field="acc_box"><?php echo $p['acc_box']; ?></td>
                    <td data-field="acc_earbuds"><?php echo $p['acc_earbuds']; ?></td>
                    <td data-field="acc_warranty"><?php echo $p['acc_warranty']; ?></td>
                    <td class="text-center">
                        <a href="pricing.php?edit=<?php echo $id; ?>"
                           class="btn btn-sm edit-btn"
                           data-id="<?php echo $id; ?>"
                           style="background:#eef1f5; color:#0B1E3F; border:none;"><i class="fa-solid fa-pencil"></i>Edit</a>
                        <a href="pricing.php?delete=<?php echo $id; ?>"
                           class="btn btn-sm delete-btn"
                           data-id="<?php echo $id; ?>"
                           style="background:#fdeaea; color:#c0392b; border:none;"><i class="fa-solid fa-trash"></i>Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr id="pricingNoResults" style="display:none;">
                <td colspan="12" class="text-center text-muted py-3">No models match your search.</td>
            </tr>
        </tbody>
    </table>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var pricingSearchInput = document.getElementById('pricingSearchInput');
    var allRows = document.querySelectorAll('tbody tr[data-search]');
    var pricingNoResults = document.getElementById('pricingNoResults');

    if (pricingSearchInput) {
        pricingSearchInput.addEventListener('input', function () {
            var query = pricingSearchInput.value.trim().toLowerCase();
            var visibleCount = 0;

            allRows.forEach(function (row) {
                var match = row.getAttribute('data-search').indexOf(query) !== -1;
                row.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });

            if (pricingNoResults) {
                pricingNoResults.style.display = (visibleCount === 0) ? '' : 'none';
            }
        });
    }

    var editModalEl = document.getElementById('staticBackdrop');
    var editModal = new bootstrap.Modal(editModalEl);
    document.querySelectorAll('.edit-btn').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault(); 
            var id = link.getAttribute('data-id');

            fetch('pricing.php?ajax=get&id=' + id)
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.error) { alert(data.error); return; }

                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_model_name').textContent = data.model_name;
                    document.getElementById('edit_base').value = data.price.base;
                    document.getElementById('edit_storage_128').value = data.price.storage_128;
                    document.getElementById('edit_storage_256').value = data.price.storage_256;
                    document.getElementById('edit_storage_512').value = data.price.storage_512;
                    document.getElementById('edit_condition_flawless').value = data.price.condition_flawless;
                    document.getElementById('edit_condition_good').value = data.price.condition_good;
                    document.getElementById('edit_condition_fair').value = data.price.condition_fair;
                    document.getElementById('edit_acc_charger').value = data.price.acc_charger;
                    document.getElementById('edit_acc_box').value = data.price.acc_box;
                    document.getElementById('edit_acc_earbuds').value = data.price.acc_earbuds;
                    document.getElementById('edit_acc_warranty').value = data.price.acc_warranty;

                    editModal.show();
                })
                .catch(function (err) { console.error(err); alert('Could not load pricing data.'); });
        });
    });
    var editForm = document.getElementById('editPriceForm');
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            e.preventDefault(); 
            var formData = new FormData(editForm);
            formData.append('ajax', 'save');

            fetch('pricing.php', { method: 'POST', body: formData })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (!data.success) { alert('Could not save changes.'); return; }

                    var row = document.getElementById('row-' + data.id);
                    if (row) {
                        Object.keys(data.price).forEach(function (field) {
                            var cell = row.querySelector('[data-field="' + field + '"]');
                            if (cell) cell.textContent = data.price[field];
                        });
                    }
                    editModal.hide();
                })
                .catch(function (err) { console.error(err); alert('Could not save changes.'); });
        });
    }

    document.querySelectorAll('.delete-btn').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault(); 
            var id = link.getAttribute('data-id');
            if (!confirm('Delete pricing for this model?')) return;
            fetch('pricing.php?ajax=delete&id=' + id)
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (!data.success) { alert('Could not delete.'); return; }
                    var row = document.getElementById('row-' + data.id);
                    if (row) row.remove();
                })
                .catch(function (err) { console.error(err); alert('Could not delete.'); });
        });
    });

});
</script>
</html>