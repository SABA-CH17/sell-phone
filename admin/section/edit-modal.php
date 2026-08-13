<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="border-radius:14px;">

      <form method="POST" id="editPriceForm">
        <input type="hidden" name="id" id="edit_id" value="">

        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">
            Edit Pricing — <span id="edit_model_name"></span>
          </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        

        <div class="modal-body">

          <label class="form-label" style="font-size:12.5px; color:#797979c5;">Base Price</label>
          <input type="text" class="form-control mb-3" name="base" id="edit_base" value="">

          <h6 style="color:#0B1E3F; font-size:13px;">Storage</h6>
          <div class="row g-3">
              <div class="col-md-4">
                  <label class="form-label" style="font-size:12.5px; color:#797979c5;">128GB</label>
                  <input type="text" class="form-control" name="storage_128" id="edit_storage_128" value="">
              </div>
              <div class="col-md-4">
                  <label class="form-label" style="font-size:12.5px; color:#797979c5;">256GB</label>
                  <input type="text" class="form-control" name="storage_256" id="edit_storage_256" value="">
              </div>
              <div class="col-md-4">
                  <label class="form-label" style="font-size:12.5px; color:#797979c5;">512GB</label>
                  <input type="text" class="form-control" name="storage_512" id="edit_storage_512" value="">
              </div>
          </div>

          <hr class="my-3">
          <h6 style="color:#0B1E3F; font-size:13px;">Condition</h6>
          <div class="row g-3">
              <div class="col-md-4">
                  <label class="form-label" style="font-size:12.5px; color:#797979c5;">Flawless</label>
                  <input type="text" class="form-control" name="condition_flawless" id="edit_condition_flawless" value="">
              </div>
              <div class="col-md-4">
                  <label class="form-label" style="font-size:12.5px; color:#797979c5;">Good</label>
                  <input type="text" class="form-control" name="condition_good" id="edit_condition_good" value="">
              </div>
              <div class="col-md-4">
                  <label class="form-label" style="font-size:12.5px; color:#797979c5;">Fair/Cracked</label>
                  <input type="text" class="form-control" name="condition_fair" id="edit_condition_fair" value="">
              </div>
          </div>

          <hr class="my-3">
          <h6 style="color:#0B1E3F; font-size:13px;">Accessories</h6>
          <div class="row g-3">
              <div class="col-md-3">
                  <label class="form-label" style="font-size:12.5px; color:#797979c5;">Charger</label>
                  <input type="text" class="form-control" name="acc_charger" id="edit_acc_charger" value="">
              </div>
              <div class="col-md-3">
                  <label class="form-label" style="font-size:12.5px; color:#797979c5;">Box</label>
                  <input type="text" class="form-control" name="acc_box" id="edit_acc_box" value="">
              </div>
              <div class="col-md-3">
                  <label class="form-label" style="font-size:12.5px; color:#797979c5;">Earbuds</label>
                  <input type="text" class="form-control" name="acc_earbuds" id="edit_acc_earbuds" value="">
              </div>
              <div class="col-md-3">
                  <label class="form-label" style="font-size:12.5px; color:#797979c5;">Warranty</label>
                  <input type="text" class="form-control" name="acc_warranty" id="edit_acc_warranty" value="">
              </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="save" class="btn" style="background:#0B1E3F; color:#fff;">Save Changes</button>
        </div>

      </form>
    </div>
  </div>
</div>