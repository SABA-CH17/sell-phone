function switchSection(targetId) {
    var brands = document.getElementById("brands-section");
    var apple = document.getElementById("apple-section");
    var samsung = document.getElementById("samsung-section");

    brands.classList.add("fade-out");

    setTimeout(function () {
        brands.style.display = "none";

        apple.style.display = "none";
        samsung.style.display = "none";
        apple.classList.remove("fade-in");
        samsung.classList.remove("fade-in");

        var target = document.getElementById(targetId);
        target.style.display = "block";

        void target.offsetWidth;
        target.classList.add("fade-in");
    }, 300);
}

function showApple() {
    switchSection("apple-section");
}

function showSamsung() {
    switchSection("samsung-section");
}

function goToTab(tabButtonId) {
    var btn = document.getElementById(tabButtonId);
    if (btn) {
        var tab = bootstrap.Tab.getOrCreateInstance(btn);
        tab.show();
    }
}

// Tracks the user's current picks as they move through the tabs
var selection = {
    model_id: null,
    brand: null,
    model_name: null,
    storage: null,
    condition: null,
    pricing: null
};

function formatDelta(value) {
    value = parseFloat(value) || 0;
    if (value === 0) return 'No extra charge';
    return (value > 0 ? '+' : '') + 'AED ' + value;
}

// Fetches this model's live pricing from the database and fills in every
// storage/condition/accessory price tag inside the given section.
function loadModelPricing(modelId, sectionSelector) {
    $(sectionSelector + ' .price-tag').text('Loading...');

    fetch('get-model-pricing.php?model_id=' + encodeURIComponent(modelId))
        .then(function (res) { return res.json(); })
        .then(function (data) {
            selection.pricing = data;
            $(sectionSelector + ' .price-tag').each(function () {
                var key = $(this).data('price-for');
                if (key && data.hasOwnProperty(key)) {
                    $(this).text(formatDelta(data[key]));
                }
            });
        })
        .catch(function () {
            $(sectionSelector + ' .price-tag').text('Price unavailable');
        });
}

$(document).ready(function () {

    $(document).on('click', '#pills-home .card', function () {
        $(this).closest('.row').find('.card').removeClass('selected-card');
        $(this).addClass('selected-card');
        var col = $(this).closest('.col');
        selection.model_id = col.data('model-id');
        selection.model_name = col.data('model-name');
        selection.brand = 'Apple';
        loadModelPricing(selection.model_id, '#apple-section');
        $('#pills-profile-tab').tab('show');
    });

    $(document).on('click', '#pills-profile .storage-card', function () {
        $(this).siblings('.storage-card').removeClass('selected-card');
        $(this).addClass('selected-card');
        selection.storage = $(this).data('key');
        $('#pills-contact-tab').tab('show');
    });

    $(document).on('click', '#pills-contact .condi-card', function () {
        $(this).siblings('.condi-card').removeClass('selected-card');
        $(this).addClass('selected-card');
        selection.condition = $(this).data('key');
        $('#pills-disabled-tab').tab('show');
    });

    $(document).on('click', '#pills-models .card', function () {
        $(this).closest('.row').find('.card').removeClass('selected-card');
        $(this).addClass('selected-card');
        var col = $(this).closest('.col');
        selection.model_id = col.data('model-id');
        selection.model_name = col.data('model-name');
        selection.brand = 'Samsung';
        loadModelPricing(selection.model_id, '#samsung-section');
        $('#pills-storage-tab').tab('show');
    });

    $(document).on('click', '#pills-storage .storage-card', function () {
        $(this).siblings('.storage-card').removeClass('selected-card');
        $(this).addClass('selected-card');
        selection.storage = $(this).data('key');
        $('#pills-condition-tab').tab('show');
    });

    $(document).on('click', '#pills-condition .condi-card', function () {
        $(this).siblings('.condi-card').removeClass('selected-card');
        $(this).addClass('selected-card');
        selection.condition = $(this).data('key');
        $('#pills-accessories-tab').tab('show');
    });


});

function goBack() {
    var brands = document.getElementById("brands-section");
    var apple = document.getElementById("apple-section");
    var samsung = document.getElementById("samsung-section");

    apple.classList.remove("fade-in");
    samsung.classList.remove("fade-in");

    setTimeout(function () {
        apple.style.display = "none";
        samsung.style.display = "none";

        brands.style.display = "block";
        void brands.offsetWidth;
        brands.classList.remove("fade-out");
    }, 300);
}
function goToEstimateTab() {
    goToTab('pills-estimate-tab');
    calculateEstimate();
}

function calculateEstimate() {
    var sectionSelector = selection.brand === 'Samsung' ? '#samsung-section' : '#apple-section';
    var $section = $(sectionSelector);
    var $display = $section.find('#estimatePriceDisplay');
    var $note = $section.find('#estimateNote');
    var $summary = $section.find('#estimateSummary');

    if (!selection.model_id || !selection.storage || !selection.condition) {
        $display.text('--');
        $note.text('Please complete model, storage and condition first.');
        $summary.text('');
        return;
    }

    $display.text('Calculating...');
    $note.text('');

    var accessories = [];
    var accessoryLabels = [];
    $section.find('.custom-control-input:checked').each(function () {
        var key = $(this).data('key');
        if (key) {
            accessories.push(key);
            accessoryLabels.push($(this).siblings('label').find('h2').text().trim());
        }
    });

    var storageLabel = selection.storage.replace('storage_', '') + 'GB';
    var conditionLabel = selection.condition.replace('condition_', '');
    conditionLabel = conditionLabel.charAt(0).toUpperCase() + conditionLabel.slice(1);

    var summaryText = 'Based on: ' + selection.model_name + ' ' + storageLabel + ', ' + conditionLabel + ' condition';
    if (accessoryLabels.length) {
        summaryText += ' + ' + accessoryLabels.join(', ');
    }
    $summary.text(summaryText);

    var params = new URLSearchParams();
    params.append('model_id', selection.model_id);
    params.append('storage', selection.storage);
    params.append('condition', selection.condition);
    accessories.forEach(function (a) { params.append('accessories[]', a); });

    fetch('get-price.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params.toString()
    })
    .then(function (res) { return res.json(); })
    .then(function (data) {
        selection.price = data.price || 0;
        $display.text('AED ' + selection.price);
        $note.text(data.note || '');
    })
    .catch(function () {
        $display.text('--');
        $note.text('Could not calculate estimate. Please try again.');
    });
}

function showFormModal() {
   
    var modal = new bootstrap.Modal(document.getElementById('myFormModal'));
    modal.show();
}

function submitFormAndProceed() {
     
    var form = document.getElementById('finalForm');
    var name = form.elements['name'].value.trim();
    var phone = form.elements['phone'].value.trim();
    var email = form.elements['email'].value.trim();
    var address = form.elements['address'].value.trim();
    var alertBox = document.getElementById('finalFormAlert');
    var submitBtn = document.getElementById('finalFormSubmitBtn');

    alertBox.style.display = 'none';

    if (!selection.model_id || !selection.storage || !selection.condition) {
        alertBox.className = 'alert alert-danger';
        alertBox.textContent = 'Please pick a model, storage and condition before submitting.';
        alertBox.style.display = 'block';
        return;
    }
    if (!name || !phone) {
        alertBox.className = 'alert alert-danger';
        alertBox.textContent = 'Please enter your name and phone number.';
        alertBox.style.display = 'block';
        return;
    }

    var accessories = [];
    $('#pills-disabled .custom-control-input:checked, #pills-accessories .custom-control-input:checked').each(function () {
        accessories.push($(this).data('key'));
    });

    submitBtn.disabled = true;
    submitBtn.textContent = 'Submitting...';

    var priceParams = new URLSearchParams();
    priceParams.append('model_id', selection.model_id);
    priceParams.append('storage', selection.storage);
    priceParams.append('condition', selection.condition);
    accessories.forEach(function (a) { priceParams.append('accessories[]', a); });

    fetch('get-price.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: priceParams.toString()
    })
    .then(function (res) { return res.json(); })
    .then(function (priceData) {
        var price = priceData.price || 0;

        // Switched to FormData (instead of URLSearchParams) so uploaded device photos ride along with the request
        var leadFormData = new FormData();
        leadFormData.append('model_id', selection.model_id);
        leadFormData.append('brand', selection.brand);
        leadFormData.append('model_name', selection.model_name);
        leadFormData.append('storage', selection.storage);
        leadFormData.append('condition', selection.condition);
        leadFormData.append('accessories', accessories.join(', '));
        leadFormData.append('price', price);
        leadFormData.append('name', name);
        leadFormData.append('phone', phone);
        leadFormData.append('email', email);
        leadFormData.append('address', address);

        var photoInput = form.querySelector('input[name="device_photos[]"]');
        if (photoInput && photoInput.files.length) {
            for (var i = 0; i < photoInput.files.length; i++) {
                leadFormData.append('device_photos[]', photoInput.files[i]);
            }
        }

        console.log(leadFormData);
        return fetch('save-lead.php', {
            method: 'POST',
            body: leadFormData
        }).then(function (res) { return res.json(); });
    })
    .then(function (data) {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Submit';

        alertBox.className = data.success ? 'alert alert-success' : 'alert alert-danger';
        alertBox.textContent = data.success
            ? 'Thanks! Your estimated offer is AED ' + data.price + '. We will contact you shortly.'
            : data.message;
        alertBox.style.display = 'block';

        if (data.success) {
            setTimeout(function () {
                var modalEl = document.getElementById('myFormModal');
                var modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                form.reset();
                alertBox.style.display = 'none';
            }, 2200);
        }
    })
    .catch(function () {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Submit';
        alertBox.className = 'alert alert-danger';
        alertBox.textContent = 'Network error. Please try again.';
        alertBox.style.display = 'block';
    });
}