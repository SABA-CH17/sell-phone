<section class="samsung-section" id="samsung-section">
    <div class="section-header">
        <span class="section-tag">SEARCH SAMSUNG MODEL TO SELL</span>
        <h2 class="section-title">Samsung</h2>
    </div>
       <div class="navbar-pills">
         <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="pills-models-tab" data-bs-toggle="pill" data-bs-target="#pills-models" type="button" role="tab" aria-controls="pills-models" aria-selected="true">Models</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="pills-storage-tab" data-bs-toggle="pill" data-bs-target="#pills-storage" type="button" role="tab" aria-controls="pills-storage" aria-selected="false">Storage</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="pills-condition-tab" data-bs-toggle="pill" data-bs-target="#pills-condition" type="button" role="tab" aria-controls="pills-condition" aria-selected="false">Condition</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="pills-accessories-tab" data-bs-toggle="pill" data-bs-target="#pills-accessories" type="button" role="tab" aria-controls="pills-accessories" aria-selected="false">Accessories</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="pills-estimate-tab" data-bs-toggle="pill" data-bs-target="#pills-estimate" type="button" role="tab" aria-controls="pills-estimate" aria-selected="false">Estimate</button>
  </li>
</ul>
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-models" role="tabpanel" aria-labelledby="pills-models-tab" tabindex="0">

  <?php
  require_once(__DIR__ . "/../admin/include/db-connect.php");
  $brand_filter = "Samsung";
  $stmt = mysqli_prepare($conn, "SELECT * FROM models WHERE brand = ? ORDER BY model_name");
  mysqli_stmt_bind_param($stmt, "s", $brand_filter);
  mysqli_stmt_execute($stmt);
  $models_result = mysqli_stmt_get_result($stmt);
  ?>
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">

    <?php while ($m = mysqli_fetch_assoc($models_result)): ?>
    <div class="col" data-model-id="<?php echo (int) $m['id']; ?>" data-model-name="<?php echo htmlspecialchars($m['model_name']); ?>">
        <div class="card h-80">
            <img src="<?php echo htmlspecialchars($m['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($m['model_name']); ?>">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($m['model_name']); ?></h5>
            </div>
        </div>
    </div>
    <?php endwhile; ?>

<div class="btn-group" role="group" aria-label="Basic example">
  <button type="button" class="btn-baxt" onclick="goBack()">BACK</button>
  <button type="button" class="btn-baxt" onclick="goToTab('pills-storage-tab')">NEXT</button>
</div>

</div>
</div>

  <div class="tab-pane fade" id="pills-storage" role="tabpanel" aria-labelledby="pills-storage-tab" tabindex="0">
         <div class="storage-container">
            <div class="storage-card" data-key="storage_128">
                <h2 class="h2store">128 GB</h2>
            </div>

            <div class="storage-card" data-key="storage_256">
                <h2 class="h2store">256 GB</h2>
            </div>

            <div class="storage-card" data-key="storage_512">
                <h2 class="h2store">512 GB</h2>
            </div>
        </div>
        <div class="btn-group-button" role="group" aria-label="Basic example">
            <button type="button" class="btn-baxt" onclick="goToTab('pills-models-tab')">BACK</button>
            <button type="button" class="btn-baxt" onclick="goToTab('pills-condition-tab')">NEXT</button>
         </div>

  </div>
  <div class="tab-pane fade" id="pills-condition" role="tabpanel" aria-labelledby="pills-condition-tab" tabindex="0">
     <div class="condi-container">
            <div class="condi-card" data-key="condition_flawless">
                <h2 class="h2con">Flawless</h2>
                <p>Looks brand new, no scratches or dents.</p>
            </div>

            <div class="condi-card" data-key="condition_good">
                <h2 class="h2con">Good</h2>
                 <p>Light signs of wear, fully working.</p>
            </div>

            <div class="condi-card" data-key="condition_fair">
                <h2 class="h2con">Fair/Cracked</h2>
                 <p>Heavy wear, cracked screen or back, but functional.</p>
            </div>
        </div>
        <div class="btn-group-button" role="group" aria-label="Basic example" style="text-align:center;">
            <button type="button" class="btn-baxt" onclick="goToTab('pills-storage-tab')">BACK</button>
            <button type="button" class="btn-baxt" onclick="goToTab('pills-accessories-tab')">NEXT</button>
        </div>

  </div>
  <div class="tab-pane fade" id="pills-accessories" role="tabpanel" aria-labelledby="pills-accessories-tab" tabindex="0">
    <div class="row">
    <div class="col-md-3">
        <div class="custom-control custom-checkbox image-checkbox">
            <input type="checkbox" class="custom-control-input" id="cs1a" data-key="acc_charger">
            <label class="custom-control-label" for="cs1a">
                 <h2 class="h2con">Charger</h2>
            </label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="custom-control custom-checkbox image-checkbox">
            <input type="checkbox" class="custom-control-input" id="cs1b" data-key="acc_box">
            <label class="custom-control-label" for="cs1b">
                <h2 class="h2con">Box</h2>
            </label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="custom-control custom-checkbox image-checkbox">
            <input type="checkbox" class="custom-control-input" id="cs1c" data-key="acc_earbuds">
            <label class="custom-control-label" for="cs1c">
                <h2 class="h2con">Earbuds</h2>
            </label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="custom-control custom-checkbox image-checkbox">
            <input type="checkbox" class="custom-control-input" id="cs1d" data-key="acc_warranty">
            <label class="custom-control-label" for="cs1d">
                 <h2 class="h2con">Warrenty Card</h2>
            </label>
        </div>
    </div>
     <div class="btn-group-checkbutton" role="group" aria-label="Basic example" style="text-align:center ; align-items:center">
            <button type="button" class="btn-baxt" onclick="goToTab('pills-condition-tab')">BACK</button>
            <button type="button" class="btn-baxt" onclick="goToEstimateTab()">See Estimate</button>
        </div>
    </div>

    <div class="tab-pane fade" id="pills-estimate" role="tabpanel" aria-labelledby="pills-estimate-tab" tabindex="0">
        <div class="row justify-content-center text-center" style="padding:30px 0;">
            <div class="col-md-6">
                <h2 class="h2con" style="margin-bottom:10px;">Your Estimated Offer</h2>
                <div id="estimatePriceDisplay" style="font-size:38px; font-weight:700; color:#0B1E3F;">Calculating...</div>
                <p id="estimateNote" style="color:#797979; font-size:13px; margin-top:8px;"></p>
            </div>
        </div>
        <div class="btn-group-checkbutton" role="group" aria-label="Basic example" style="text-align:center ; align-items:center">
            <button type="button" class="btn-baxt" onclick="goToTab('pills-accessories-tab')">BACK</button>
            <button type="button" class="btn-baxt" onclick="showFormModal()">Continue</button>
        </div>
    </div>
</div>
</section>