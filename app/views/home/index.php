  <!-- Basic Card Example -->
  <div class="card shadow mb-2">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Manual Report Generator</h6>
    </div>
    <div class="card-body row">
      <div class="col-md-12">
        <div class="input-group mb-3">
          <input list="ps_destination" id="send_url" placeholder="API Destination URL" class="form-control">
          <datalist id="ps_destination">
            <?php foreach ($data["project_send_destination"] as $p) : ?>
              <option value="<?= $p["url"] ?>"><?= $p["url"] ?></option>
            <?php endforeach ?>
          </datalist>
          <div class="input-group-append">
            <button class="btn btn-primary js-send" type="button">Send</button>
            <button class="btn btn-outline-danger js-cancel" type="button">Cancel</button>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card mb-4">
          <div class="card-body">
            <div class="form-group">
              <label for="project_folder">Project Name</label>
              <select class="form-control select2" name="project_folder" id="project_folder">
                <?php foreach ($data["project_folder"] as $p) : ?>
                  <option value='<?= json_encode($p) ?>'><?= $p["label"] ?></option>
                <?php endforeach ?>
              </select>
            </div>
            <div class="form-group">
              <label for="country">Format Date Region</label>
              <select class=" form-control select2" name="country" id="country">
                <?php foreach ($data["country"] as $p) : ?>
                  <option value="<?= $p["name"] ?>"><?= $p["name"] ?></option>
                <?php endforeach ?>
              </select>
            </div>
            <div class="form-group">
              <label for="exampleFormControlInput1">Start Date</label>
              <input type="date" class="form-control default-todays-date" name="start_date" id="start_date">
            </div>
            <div class="form-group">
              <label for="exampleFormControlInput1">End Date</label>
              <input type="date" class="form-control default-todays-date" name="end_date" id="end_date">
            </div>
            <button type="button" class="btn  btn-outline-primary btn-block js-filter-data">Show Data</button>
          </div>
        </div>
      </div>

      <div class="col-md-9">
        <div class="card mb-4">
          <div class="card-body">
            <table id="tableData" class="table table-sm">
              <thead>
                <tr>
                  <th></th>
                  <th style="width: 20%">Date UTC</th>
                  <th style="width: 20%">Date Coverted</th>
                  <th>Data</th>
                </tr>
              </thead>
              <tbody id="tableDataBody">

              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="card shadow">
    <div class="card-body">
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h5 mb-0 text-gray-800">Logs </h1>
        <button type="button" class="btn btn-sm  btn-outline-danger js-clear-log">Clear</button>
      </div>
      <div class="row">
        <div class="col-md-2">
          <h1 class="h6 mb-3  ">Total <span class="total-rows-execute text-muted">0</span> Rows Executed </h1>
        </div>
        <div class="col-md-2">
          <h1 class="h6 mb-3 "><i class="fas fa-check-circle text-success"></i> <span class="text-muted total-success-rows-execute">0</span> Success </h1>
        </div>
        <div class="col-md-2">
          <h1 class="h6 mb-3 "><i class="fas fa-times-circle text-danger"></i> <span class="text-muted total-failed-rows-execute">0</span> Failed</h1>
        </div>
      </div>
      </table>
      <table class="table table-sm tableBodyScroll">
        <thead>
          <tr>
            <th style="width: 18%">Date</th>
            <th style="width: 62%">Data</th>
            <th class="text-justify" style="width: 10%">Time (ms)</th>
            <th class="text-justify" style="width: 10%">Result</th>
          </tr>
        </thead>
        <tbody id="tableLogBody">

        </tbody>
      </table>

    </div>
  </div>