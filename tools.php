<?php

require_once(__DIR__ . "/Partials/Authenticator.php");
require_once(__DIR__ . "/Resources/Components/Header.php");

$jobs = implode(", ", array_map(function ($item) {
  return str_replace(".php", "", $item);
}, array_diff(scandir(__DIR__ . "/Jobs"), [".", "..", ".htaccess"])));

?>

<body id="page-top">
  <div id="wrapper">
    <?php require_once(__DIR__ . "/Resources/Components/SideBar.php"); ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <?php require_once(__DIR__ . "/Resources/Components/HeadBar.php"); ?>
        <div class="container-fluid">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-2 text-gray-800">Manage Tools</h1>
          </div>
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Tools</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Tool</th>
                      <th>Parameter</th>
                      <th>Execute</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>API Email</td>
                      <td contenteditable spellcheck="false" id="email">{"to": "contact@terrytm.com", "subject": "Test Email", "message": "Hello World!"}</td>
                      <td class="center"><a href="#" onClick="execute(event, 'email')"><span class="fa fa-play"></span></a></td>
                    </tr>
                    <tr>
                      <td>Job Trigger</td>
                      <td contenteditable spellcheck="false" id="job">{"name": "WakeServer", "choices": "<?php echo($jobs); ?>"}</td>
                      <td class="center"><a href="#" onClick="execute(event, 'job')"><span class="fa fa-play"></span></a></td>
                    </tr>
                    <tr>
                      <td>SSL Test</td>
                      <td contenteditable spellcheck="false" id="ssl">{"url": "https://terrytm.com"}</td>
                      <td class="center"><a href="#" onClick="execute(event, 'ssl')"><span class="fa fa-play"></span></a></td>
                    </tr>
                    <tr>
                      <td>Discover IP</td>
                      <td contenteditable spellcheck="false" id="address">{"cloudflare": "true"}</td>
                      <td class="center"><a href="#" onClick="execute(event, 'address')"><span class="fa fa-play"></span></a></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Output</h6>
            </div>
            <div class="card-body">
              <div style="width: 100%; height: 600px; overflow: auto; border: 1px solid lightgray; border-radius: 3px; padding: 8px; cursor: default;" readonly id="output">
                TerryTM API Output<br>
                ---------------------<br>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php require_once(__DIR__ . "/Resources/Components/Footer.php"); ?>
    </div>
  </div>
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <?php require_once(__DIR__ . "/Resources/Components/Scripts.php"); ?>
  <script>
    let executing = false;

    function log(message) {
      const today = new Date();
      const now = `${today.getFullYear()}-${today.getMonth()+1}-${today.getDate()} ${today.getHours()}:${today.getMinutes()}:${today.getSeconds()}`;
      document.getElementById('output').innerHTML += `[${now}] ${message}<br>`;
    }

    document.querySelectorAll('td[contenteditable]').forEach((element) => element.addEventListener('paste', (event) => {
        event.preventDefault();
        const text = (event.originalEvent || event).clipboardData.getData('text/plain');
        document.execCommand('insertHTML', false, text);
    }));

    function execute(event, target) {
      event.preventDefault();
      if (executing) {
        return;
      }
      executing = true;
      try {
        const query = JSON.parse(document.getElementById(target).innerText.trim());
        if (Object.keys(query).some((key) => typeof query[key] === 'object' || typeof query[key] === 'array')) {
          throw "Query must be a flattened object.";
        }
        log("Executing request...");
        asyncPostRequest('/Controllers/Admin/Tools.php', target, query).then((response) => {
          if (response.success && response.data.output) {
            log(response.data.output);
            log("Complete!");
          } else {
            log("An error has occurred!");
          }
        });
      } catch (exception) {
        log(exception);
      }
      executing = false;
    }
  </script>
</body>
</html>