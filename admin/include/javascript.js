(function () {
  'use strict'
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  tooltipTriggerList.forEach(function (tooltipTriggerEl) {
    new bootstrap.Tooltip(tooltipTriggerEl)
  })
})()
function updateLeadStatus(selectEl) {
    var id = selectEl.getAttribute('data-id');
    var previousStatus = selectEl.getAttribute('data-status');
    var newStatus = selectEl.value;

    selectEl.disabled = true;

    var params = new URLSearchParams();
    params.append('id', id);
    params.append('status', newStatus);

    fetch('update-lead-status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params.toString()
    })
    .then(function (res) { return res.json(); })
    .then(function (data) {
        selectEl.disabled = false;
        if (data.success) {
            selectEl.setAttribute('data-status', newStatus);
        } else {
            // Revert the dropdown if the save failed
            selectEl.value = previousStatus;
            alert(data.message || 'Could not update status.');
        }
    })
    .catch(function () {
        selectEl.disabled = false;
        selectEl.value = previousStatus;
        alert('Network error. Could not update status.');
    });
}
