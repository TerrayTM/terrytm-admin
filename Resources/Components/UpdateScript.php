<script>
  const technologySource = <?php echo($technology_options) ?>;
  const tagValueSource = <?php echo($tag_values) ?>;
  const tagColorSource = <?php echo($tag_colors) ?>;
  const imageContainer = document.getElementById('image-container');
  $('#technology').autocomplete({ source: technologySource });
  $('#tagValue').autocomplete({ source: tagValueSource });
  $('#tagColor').autocomplete({ source: tagColorSource });
  updateContainer('.technology-container');
  updateContainer('.tag-container');
  $("#image-container").sortable();
  $("#image-container").disableSelection();
  imageContainer.style.display = imageContainer.childElementCount > 0 ? 'block' : 'none';

  function uploadImages(id) {
    document.getElementById('id').value = id;
    document.getElementById('images').click();
  }

  function deleteImage(event, id) {
    postRequest('/Controllers/Admin/Projects.php', 'deleteImage', { id });
    // To do
    // Use async
  }

  function postUpload() {
    document.getElementById('upload').submit();
  }

  function saveImageOrder(id) {
    const images = $("#image-container").sortable("toArray").map((i) => i.substr(1));
    postRequest('/Controllers/Admin/Projects.php', 'order', { images: JSON.stringify(images), id });
  }

  function updateContainer(selector) {
    const element = document.querySelector(selector);
    if (element.childElementCount === 0) {
        element.style.display = 'none';
    } else {
        element.style.display = 'block';
    }
  }

  async function deleteItem(event, id, method, container) {
    event.preventDefault();
    const response = await asyncPostRequest('/Controllers/Admin/Projects.php', method, { id });
    if (response.success) {
        const parent = event.target.parentElement;
        parent.removeChild(event.target);
        updateContainer(container);
    }
  }

  async function deleteTag(event, id) {
    await deleteItem(event, id, 'deleteTag', '.tag-container');
  }

  async function deleteTechnology(event, id) {
    await deleteItem(event, id, 'deleteTechnology', '.technology-container');
  }

  async function createTechnology(event, id) {
    event.preventDefault();
    const inputElement = document.getElementById('technology');
    const technology = inputElement.value.trim();
    if (technology.length === 0) {
        return;
    }
    const response = await asyncPostRequest('/Controllers/Admin/Projects.php', 'createTechnology', { id, technology });
    if (response.success) {
        if (!technologySource.includes(technology)) {
          technologySource.push(technology);
          $('#technology').autocomplete({ source: technologySource });
        }
        const parent = document.querySelector('.technology-container');
        const element = document.createElement('button');
        element.innerHTML = `${technology}<span class="fa fa-trash"></span>`;
        element.className = 'btn btn-info tag';
        element.setAttribute('onClick', `deleteTechnology(event, ${response.data.id})`);
        parent.appendChild(element);
        inputElement.value = '';
        updateContainer('.technology-container');
    }
  }

  async function createTag(event, id) {
    event.preventDefault();
    const valueElement = document.getElementById('tagValue');
    const colorElement = document.getElementById('tagColor');
    const value = valueElement.value.trim();
    const color = colorElement.value.trim();
    if (value.length === 0 || color.length === 0) {
        return;
    }
    const response = await asyncPostRequest('/Controllers/Admin/Projects.php', 'createTag', { id, value, color });
    if (response.success) {
        if (!tagValueSource.includes(value)) {
          tagValueSource.push(value);
          $('#tagValue').autocomplete({ source: tagValueSource });
        }
        if (!tagColorSource.includes(color)) {
          tagColorSource.push(color);
          $('#tagColor').autocomplete({ source: tagColorSource });
        }
        const parent = document.querySelector('.tag-container');
        const element = document.createElement('button');
        element.innerHTML = `${value}<span class="fa fa-trash"></span>`;
        element.className = 'btn btn-info tag';
        element.setAttribute('onClick', `deleteTag(event, ${response.data.id})`);
        element.style.backgroundColor = color;
        parent.appendChild(element);
        valueElement.value = '';
        colorElement.value = '';
        updateContainer('.tag-container');
    }
  }
</script>