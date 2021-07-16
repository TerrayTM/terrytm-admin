<script src="/Resources/vendor/editor.js/editor.min.js"></script>
<script src="/Resources/vendor/editor.js/header.min.js"></script>
<script src="/Resources/vendor/editor.js/list.min.js"></script>
<script src="/Resources/vendor/editor.js/inline-code.min.js"></script>
<script src="/Resources/vendor/editor.js/image.min.js"></script>
<script>
  const editor = new EditorJS({
    holder: 'editorContainer',
    placeholder: 'Write stuff here!',
    autofocus: true,
    logLevel: 'ERROR',
    tools: {
      header: {
        class: Header,
        config: {
          placeholder: 'Header',
          levels: [2, 3, 4],
          defaultLevel: 3
        }
      },
      list: {
        class: List,
        inlineToolbar: true
      },
      inlineCode: {
        class: InlineCode
      },
      image: {
        class: ImageTool,
        config: {
          uploader: {
            uploadByFile: async (image) => {
              const sizeWarning = document.getElementById('sizeWarning');
              if (sizeWarning) {
                sizeWarning.style.display = 'none';
              }
              if (image.size > 2097152) {
                if (sizeWarning) {
                  sizeWarning.style.display = 'inline-block';
                }
              }
              const parts = image.name.split('.');
              if (parts.length == 1) { 
                return { success: 0 };
              }
              const extension = parts[parts.length - 1];
              const response = await asyncPostRequest('/Controllers/Admin/Blog.php', 'createImage', { extension }, { image });
              return {
                success: response.success ? 1 : 0,
                file: {
                  url: response.data.url,
                  id: response.data.id,
                  processed: false
                }
              };
            },
            uploadByUrl: async (link) => {
              console.warn('Upload by image URL is unsupported.');
              return {
                success: 1,
                file: {
                  url: 'https://terrytm.com/files/icon.png'
                }
              };
            }
          }
        }
      }
    }
  });
</script>
