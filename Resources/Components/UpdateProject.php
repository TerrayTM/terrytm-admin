<?php

$project = null;

if (isset($_GET['edit'])) {
  $project = Project::find($_GET['edit']);
}

$technology_options = json_encode(ProjectTechnology::select("technology")->distinct()->get()->pluck("technology")->toArray());
$tag_values = json_encode(ProjectTag::select("name")->distinct()->get()->pluck("name")->toArray());
$tag_colors = json_encode(ProjectTag::select("color")->distinct()->get()->pluck("color")->toArray());

?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-2 text-gray-800"><?php echo($project ? "Edit Project" : "Create Project"); ?></h1>
</div>
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><?php echo($project->name ?? "Create Project"); ?> (Basic Info)</h6>
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
        <input class="btn btn-primary" type="submit">
      </div>
    </form>
  </div>
</div>
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><?php echo($project->name ?? "Create Project"); ?> (Associated Info)</h6>
  </div>
  <div class="card-body">
    <div class="form-group">
      <label>Technologies</label>
      <div class="form-group technology-container" style="display: none;">
        <?php

        if ($project) {
          foreach ($project->technologies as $technology) {
            echo('<button class="btn btn-info tag" onClick="deleteTechnology(event, ' .  $technology->id . ')">' . $technology->technology . '<span class="fa fa-trash"></span></button>');
          }
        }

        ?>
      </div>
      <div class="input-group mb-2 mr-sm-2">
        <input placeholder="Technology" class="form-control" type="text" id="technology">
        <div class="input-group-append">
          <button class="input-group-text btn btn-info" <?php echo($project ? '' : 'disabled') ?> onClick="createTechnology(event, <?php echo($project ? $project->id : 'null') ?>)">Add</button>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label>Tags</label>
      <div class="form-group tag-container" style="display: none;">
        <?php

        if ($project) {
          foreach ($project->tags as $tag) {
            echo('<button class="btn btn-info tag" style="background-color: '. $tag->color .';" onClick="deleteTag(event, ' .  $tag->id . ')">' . $tag->name . '<span class="fa fa-trash"></span></button>');
          }
        }

        ?>
      </div>
      <div class="input-group mb-2 mr-sm-2">
        <input placeholder="Value" class="form-control" type="text" id="tagValue">
        <input placeholder="Color" class="form-control" type="text" id="tagColor">
        <div class="input-group-append">
          <button class="input-group-text btn btn-info" <?php echo($project ? '' : 'disabled') ?> onClick="createTag(event, <?php echo($project ? $project->id : 'null') ?>)">Add</button>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label>Images</label>
      <ul id="image-container">
        <?php

        $images = [];

        if ($project) {
          $images = $project->images()->orderBy("order")->get();

          foreach ($images as $image) {
            echo('
              <li class="ui-state-default" id="I' . $image->id . '">
                <button onClick="deleteImage(event, ' .  $image->id . ')">&times;</button>
                <img src="' . $image->url . '" alt="Project Image" style="width: 100%;">
              </li>
            ');
          }
        }

        ?>
      </ul>
      <br>
      <button class="btn btn-success" <?php echo($project ? '' : 'disabled') ?> onClick="uploadImages(<?php echo($project ? $project->id : 'null') ?>)">Upload Images</button>
      <button class="btn btn-info" <?php echo($project && count($images) > 0 ? '' : 'disabled') ?> onClick="saveImageOrder(<?php echo($project ? $project->id : 'null') ?>)">Save Image Order</button>
    </div>
    <form action="/Controllers/Admin/Projects.php" method="post" id="upload" style="display: none;" enctype="multipart/form-data">
      <?php echo($token_input); ?>
      <input type="hidden" name="request" value="upload">
      <input type="hidden" id="id" name="id" value="-1">
      <input type="file" id="images" name="images[]" onChange="postUpload()" multiple accept="image/*">
    </form>
  </div>
</div>
