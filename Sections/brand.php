<section class="brands-section" id="brands-section">
    <div class="section-header">
        <span class="section-tag">TOP BRANDS</span>
        <h2 class="section-title">Brands We Buy</h2>
        <p class="section-subtitle">Get the best prices for your used smartphones from all major<br> brands</p>
    </div>

    <div class="brands-container">
        <div class="brands-grid">
          <button class="card-one brand-card"
        id="apple-tab"
        type="button"
        onclick="showApple()">

    <div class="brand-icon">
        <img src="imgs/apple-icon.png" alt="Apple"
             style="width:60px;height:60px;object-fit:contain;">
    </div>

    <h3 class="brand-h3">Apple</h3>

</button>
            <button class="card-two brand-card"
        id="samsung-tab"
        type="button"
        onclick="showSamsung()">

    <div class="brand-icon">
        <img src="imgs/samsung.png"
             alt="Samsung"
             style="width:60px;height:60px;object-fit:contain;">
    </div>

    <h3 class="brand-h3">Samsung</h3>

</button>
        </div>
    </div>
</section>

<?php include ("Sections/apple.php"); ?>
<?php include ("Sections/samsung.php"); ?>

