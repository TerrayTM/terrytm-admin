<?php

$project = null;

if (isset($_GET['edit'])) {
  $project = Project::find($_GET['edit']);
}

?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-2 text-gray-800"><?php echo($project ? "Edit Project" : "Create Project"); ?></h1>
</div>
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><?php echo($project->name ?? "Create Project"); ?></h6>
  </div>
  <div class="card-body">
    <form action="/Controllers/Admin/Projects.php" method="post">
      <?php echo($token_input); ?>
      <input type="hidden" name="request" value="<?php echo($project ? "edit" : "create"); ?>">
      <input type="hidden" name="id" value="<?php echo($project->id ?? ""); ?>">
      <div class="form-group">
        <label>Name</label>
        <input class="form-control" type="text" name="name" value="<?php echo($project->name ?? ""); ?>">
      </div>
      <div class="form-group">
        <label>Type</label>
        <select class="form-control" name="type">
          <option <?php echo($project && $project->type === "Active Project" ? "selected" : ""); ?>>Active Project</option>
          <option <?php echo($project && $project->type === "Past Project" ? "selected" : ""); ?>>Past Project</option>
        </select>
      </div>
      <div class="form-group">
        <label>Date</label>
        <input class="form-control" type="date" name="date" value="<?php echo($project->date ?? ""); ?>">
      </div>
      <div class="form-group">
        <label>Author</label>
        <input class="form-control" type="text" name="author" value="<?php echo($project->author ?? ""); ?>">
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea style="height: 200px;" class="form-control" name="description"><?php echo($project->description ?? ""); ?></textarea>
      </div>
      <div class="form-group">
        <label>Link</label>
        <input class="form-control" type="text" name="link" value="<?php echo($project->link ?? ""); ?>">
      </div>
      <div class="form-group">
        <label>Technologies</label>
        <?php 
        
        if ($project) {
          $technologies = $project->technologies;
          $added = false;

          foreach ($technologies as $technology) {
            if (!$added) {
              echo('<div class="form-group">');
              
              $added = true;
            }
            echo('<div class="btn btn-info" style="cursor: pointer;">' . $technology->technology . '</div>');
          }

          if ($added) {
            echo('</div>');
          }
        }

        ?>
        <div class="input-group mb-2 mr-sm-2">
          <input class="form-control" type="text" name="technology">
          <div class="input-group-append">
            <div class="input-group-text" style="cursor: pointer;">+</div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label>Images</label>
        <?php
        
        if ($project) {
          $images = $project->images;
          $added = false;
          
          foreach ($images as $image) {
            if (!$added) {
              echo('<div class="form-group" style="display: flex; overflow-x: auto; border: 1px solid #ccc; border-radius: 6px;">');
              
              $added = true;
            }

            echo('
              <div style="margin: 8px; border: 1px solid #ccc; padding: 8px; border-radius: 6px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                  <input type="number" value="' . $image->order . '" style="margin-right: 8px;" class="form-control">
                  <div class="btn btn-info" style="cursor: pointer;">X</div>
                </div>
                <img src="' . $image->url . '" alt="Project Image" style="width: 240px;">
              </div>
            ');
          }
          
          if ($added) {
            echo('</div>');
          }
        }

        ?>
        <br>
        <input class="form-control" type="text" name="link" value="<?php echo($project->link ?? ""); ?>">
      </div>
      <div class="form-group">
        <input class="btn btn-primary" type="submit">
      </div>
    </form>
  </div>
</div>