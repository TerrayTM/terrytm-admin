<script src="/Resources/vendor/editor.js/editor.min.js"></script>
<script src="/Resources/vendor/editor.js/header.min.js"></script>
<script src="/Resources/vendor/editor.js/list.min.js"></script>
<script src="/Resources/vendor/editor.js/inline-code.min.js"></script>
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
          placeholder: 'Header Content',
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
      }
    }
  });
</script>
