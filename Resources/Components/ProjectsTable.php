<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-2 text-gray-800">Manage Projects</h1>
  <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Download Table</a>
</div>
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Date</th>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>
          <?php

            $projects = Project::select(["id", "name", "type", "date", "link"])->orderBy("id")->get();

            foreach ($projects as $project) {
              echo('
                <tr>
                  <td>' . $project->id . '</td>
                  <td><a target="_blank" href="' . $project->url() . '">' . $project->name . '</a></td>
                  <td>' . $project->type . '</td>
                  <td>' . $project->date . '</td>
                  <td class="center"><a href="/projects.php?edit=' . $project->id . '"><span class="fa fa-edit"></span></a></td>
                  <td class="center"><a href="#" onClick="deleteRow(event, \'' . $project->id . '\')"><span class="fa fa-trash"></span></a></td>
                </tr>
              ');
            }
            
          ?>
        </tbody>
      </table>
    </div>
    <a href="/projects.php?create=true" class="btn btn-primary">Create</a>
  </div>
</div>
