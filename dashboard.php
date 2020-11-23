<?php

$styles = '
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
    }

    td, th {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
      color: #3f3f3f;
    }

    .task-style {
      cursor: pointer;
    }

    .task-style:hover {
      text-decoration: line-through;
    }

    .password-field {
      -webkit-text-security: disc;
    }

    .task-creator {
      border-radius: 3px;
      border: 1px solid lightgray;
      outline: none;
      padding: 0 6px;
    }

    .log-link {
      text-decoration: none !important;
    }
  </style>
';

require_once(__DIR__ . "/Partials/Authenticator.php");
require_once(__DIR__ . "/Resources/Components/Header.php");
require_once(__DIR__ . "/Partials/DatabaseConnector.php");

$notes = Note::orderBy("id", "DESC")->get();
$noteElements = "";

foreach ($notes as $note) {
  $noteElements .= '
    <div class="col-lg-6 mb-4" id="' . $note->id . '">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Notepad</h6>
        </div>
        <div class="card-body">
          <textarea id="' . $note->id . '-data" style="outline: none; border: none; width: 100%; height: 140px;" placeholder="Write something here...">' . $note->note . '</textarea>
          <hr>
          <div style="text-align: right;">
            <p id="' . $note->id . '-delete" style="display: none; margin: 0 8px 0 0;">Click again to delete.</p>
            <button class="btn btn-success btn-circle btn-sm" onClick="saveNote(' . $note->id . ')">
              <i class="fas fa-check"></i>
            </button>
            <button class="btn btn-danger btn-circle btn-sm" onClick="deleteNote(' . $note->id . ')">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  ';
}

$result = CronResult::orderBy("timestamp", "DESC")->first();
$cron_status = "";

if ($result) {
  $cron_status = ($result->is_successful ? "[OK]" : "[FAIL]") . " " . date("m/d H:i", strtotime($result->timestamp));

  if (!$result->is_successful) {
    $cron_status = '<span style="color: red">' . $cron_status . '</span>';
  }
} else {
  $cron_status = "N/A";
}

$servers = json_encode(Server::select("url")->get()->pluck("url")->toArray());
$error_report = AppError::all()->count();

if ($error_report === 0) {
  $error_report = "[OK] No Errors";
} else {
  $error_report = '<span style="color: red;">[FAIL] ' . $error_report . ' Error' . ($error_report === 1 ? '' : 's') . ' Found</span>';
}

$task_list = "";
$tasks = Task::all();

foreach ($tasks as $task) {
  $task_list .= '<li class="task-style" onClick="deleteTask(\'' . $task->id . '\')" id="task_' . $task->id . '">' . $task->text . '</li>';
}

?>

<body id="page-top">
  <div class="modal fade" id="errorContent" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Error Log</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline: none;">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <textarea style="width: 100%; outline: none; cursor: default; height: 400px;" readonly></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Delete</button>
        </div>
      </div>
    </div>
  </div>
  <div id="wrapper">
    <?php require_once(__DIR__ . "/Resources/Components/SideBar.php"); ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <?php require_once(__DIR__ . "/Resources/Components/HeadBar.php"); ?>
        <div class="container-fluid">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <a href="#" onClick="createNote(event)" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus-square fa-sm text-white-50"></i> Create Note</a>
          </div>
          <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Cron Status</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo($cron_status); ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-cog fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Remind List</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">N/A</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Errors</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo($error_report); ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Repl.it Status</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800" id="serverHealth"">Loading...</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xl-8 col-lg-7">
              <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Analytics <?php echo(date("(F, Y)")); ?></h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Actions:</div>
                      <a class="dropdown-item" href="#" onClick="deleteAnalytics()">Clear <?php echo(date("F")); ?> Data </a>
                      <a class="dropdown-item" href="#" onClick="deleteAnalytics(true)">Clear All Data</a>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="chart-area">
                    <canvas id="chart"></canvas>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-lg-5">
              <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">To Do List</h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Actions:</div>
                      <a class="dropdown-item" href="#" onClick="createTask(event)">Create Task</a>
                    </div>
                  </div>
                </div>
                <div class="card-body" style="height: 360px; overflow: auto;">
                  <div id="taskEmpty" style="display: <?php echo(strlen($task_list) > 0 ? "none" : "flex"); ?>; width: 100%; height: 300px; align-items: center; justify-content: center; flex-flow: column;">
                    <span class="fa fa-bell" style="font-size: 128px;"></span>
                    <p style="font-size: 32px; margin: 16px 0 0 0;">No tasks available!</p>
                  </div>
                  <ol id="taskContainer">
                    <?php echo($task_list); ?>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6 mb-4">
              <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Password Manager</h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Master Password</div>
                      <div style="padding: 0 8px;">
                        <form>
                          <input id="master" autocomplete class="form-control" type="password"/>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive" style="height: 209px; overflow: auto;">
                    <table>
                      <tr>
                        <th>Account</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>View</th>
                        <th>Delete</th>
                      </tr>
                      <?php

                      $accounts = Account::all();

                      foreach ($accounts as $account) {
                        echo('
                          <tr>
                            <td>' . $account->name . '</td>
                            <td>' . $account->username .'</td>
                            <td class="password-field" id="P' . $account->id . '" cipher="' . $account->password . '">00000000000000000000</td>
                            <td class="center"><a href="#" onClick="viewAccount(event, ' . $account->id . ')"><span class="fa fa-eye"></span></a></td>
                            <td class="center"><a href="#" onClick="deleteAccount(event, ' . $account->id . ')"><span class="fa fa-trash"></span></a></td>
                          </tr>
                        ');
                      }

                      for ($i = 0; $i < 3 - $accounts->count(); ++$i) {
                        echo("<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>");
                      }

                      ?>
                      <tr>
                        <td contenteditable spellcheck="false" id="accountName"></td>
                        <td contenteditable spellcheck="false" id="accountUsername"></td>
                        <td contenteditable spellcheck="false" id="accountPassword"></td>
                        <td></td>
                        <td class="center"><a href="#" onClick="createAccount(event)"><span class="fa fa-save"></span></a></td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6 mb-4">
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Status Log</h6>
                </div>
                <div class="card-body">
                  <div style="width: 100%; height: 209px; overflow: auto; border: 1px solid lightgray; border-radius: 3px; padding: 8px; cursor: default;" readonly id="log">No entries available!</div>
                </div>
              </div>
            </div>
            <div id="notepadAnchor"></div>
            <?php echo($noteElements); ?>
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
  <?php require_once(__DIR__ . "/Resources/Components/Chart.php"); ?>
  <script src="/Resources/vendor/crypto.js/crypto.min.js"></script>
  <script>
    let logEntries = false;
    let taskCreating = false;
    const deletePending = {};
    const timeoutTracker = new Set();
    const inputFields = ['accountName', 'accountUsername', 'accountPassword'];
    const errorData = [];
    inputFields.forEach((i) => removeFormatting(document.getElementById(i)));
    
    function removeFormatting(element) {
      element.addEventListener('paste', (event) => {
        event.preventDefault();
        const text = (event.originalEvent || event).clipboardData.getData('text/plain');
        document.execCommand('insertHTML', false, text);
      });
    }

    function log(text) {
      if (!logEntries) {
        document.getElementById('log').innerHTML = '';
        logEntries = true;
      }
      if (text.includes('[Error]')) {
        text = `<span style="color: red;">${text}</span>`;
      }
      document.getElementById('log').innerHTML += `> ${text}<br>`;
    }

    function updateTaskEmpty() {
      if (document.getElementById('taskContainer').childElementCount === 0) {
        document.getElementById('taskEmpty').style.display = 'flex';
      }
    }

    function deleteTask(id) {
      asyncPostRequest('/Controllers/Admin/Dashboard.php', 'deleteTask', { id }).then(({ status }) => {
        log(status);
        const node = document.getElementById(`task_${id}`);
        node.parentElement.removeChild(node);
        updateTaskEmpty();
      });
    }

    function createTask(event) {
      if (!taskCreating) {
        const element = document.createElement('li');
        element.classList.add('task-creator');
        element.setAttribute('contenteditable', 'true');
        element.setAttribute('spellcheck', 'false');
        element.addEventListener('keydown', taskInput);
        removeFormatting(element);
        document.getElementById('taskEmpty').style.display = 'none';
        document.getElementById('taskContainer').appendChild(element);
        element.focus();
        taskCreating = true;
      }
      event.preventDefault();
    }

    async function retrieveMaster() {
      const master = document.getElementById('master').value.trim();
      if (master.length < 8) {
        return null;
      }
      const hash = CryptoJS.SHA256(master).toString();
      const { status, data } = await asyncPostRequest('/Controllers/Admin/Dashboard.php', 'validateMaster', { hash });
      log(status);
      if (!data.valid) {
        return null;
      }
      return master;
    }

    async function createAccount(event) {
      event.preventDefault();
      const name = document.getElementById('accountName').innerText.trim();
      const username = document.getElementById('accountUsername').innerText.trim();
      let password = document.getElementById('accountPassword').innerText.trim();
      const master = await retrieveMaster();
      if (name.length === 0 || username.length === 0 || password.length === 0 || !master) {
        log("[Error] Insufficient Data");
      } else {
        password = CryptoJS.AES.encrypt(password, master).toString();
        postRequest('/Controllers/Admin/Dashboard.php', 'createAccount', { name, username, password });
      }
    }

    function deleteAccount(event, id) {
      postRequest('/Controllers/Admin/Dashboard.php', 'deleteAccount', { id });
      event.preventDefault();
    }

    async function viewAccount(event, id) {
      event.preventDefault();
      id = `P${id}`;
      if (!timeoutTracker.has(id)) {
        const element = document.getElementById(id);
        const cipher = element.getAttribute('cipher');
        const master = await retrieveMaster();
        if (!master) {
          log('[Error] Invalid Master Password');
          return;
        }
        try {
          const bytes = CryptoJS.AES.decrypt(cipher, master);
          const password = bytes.toString(CryptoJS.enc.Utf8);
          element.innerText = password;
        } catch (error) {
          log('[Error] Password Decryption Failed');
          element.innerText = '0'.repeat(20);
          return;
        }
        element.setAttribute('style', '-webkit-text-security: none;');
        timeoutTracker.add(id);
        setTimeout(() => {
          element.innerText = '0'.repeat(20);
          element.setAttribute('style', '-webkit-text-security: disc;');
          timeoutTracker.delete(id);
        }, 10000);
      }
    }

    function taskInput(event) {
      if (event.keyCode === 13) {
        const text = event.target.innerText.trim();
        if (text) {
          asyncPostRequest('/Controllers/Admin/Dashboard.php', 'createTask', { text }).then(({ status, data }) => {
            log(status);
            const current = event.target;
            current.parentElement.removeChild(current);
            const item = document.createElement('li');
            item.classList.add('task-style');
            item.setAttribute('onclick', `deleteTask('${data.id}')`);
            item.id = `task_${data.id}`;
            item.innerHTML = text;
            document.getElementById('taskContainer').appendChild(item);
            taskCreating = false;
          });
          event.preventDefault();
        }
      } else if (event.keyCode === 27) {
        const current = event.target;
        current.parentElement.removeChild(current);
        taskCreating = false;
        updateTaskEmpty();
      }
    }

    async function deleteError(event, id) {
      const response = await asyncPostRequest('/Controllers/Admin/Dashboard.php', 'deleteError', { location: errorData[id].location });
      if (response.success) {
        errorData[id] = null;
        $('#errorContent').modal('hide');
      }
    }

    function showError(event, id) {
      event.preventDefault();
      if (errorData[id]) {
        document.querySelector('#errorContent .modal-body textarea').value = errorData[id].content;
        document.querySelector('#errorContent .btn-primary').setAttribute('onClick', `deleteError(event, ${id})`);
        $('#errorContent').modal('show');
      }
    }

    async function checkErrors() {
      const response = await asyncPostRequest('/Controllers/Admin/Dashboard.php', 'checkErrors');
      if (response.success) {
        for (let i = 0; i < response.data.errors.length; ++i) {
          const error = response.data.errors[i];
          log(`[Error] Error Log: <a href="#" onClick="showError(event, ${i})" class="log-link">${error.location}</a>`);
          errorData.push(error);
        }
      }
    }

    async function checkServerHealth() {
      const servers = JSON.parse('<?php echo($servers); ?>');
      let success = 0;
      for (let i = 0; i < servers.length; ++i) {
        try {
          const response = await Promise.race([
            fetch(servers[i]),
            new Promise((_, reject) => setTimeout(() => reject(new Error()), 3000))
          ]);
          if (response.ok) {
            ++success;
          }
        } catch (error) {
          log(`[Error] Server Offline: ${servers[i]}`);
        }
      }
      document.getElementById('serverHealth').innerHTML = `Servers: ${success}/${servers.length}`;
    }

    function createNote(event) {
      event.preventDefault();
      const id = `A${+ new Date()}`;
      const element = `
        <div class="col-lg-6 mb-4" id="${id}">
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Notepad</h6>
            </div>
            <div class="card-body">
              <textarea id="${id}-data" style="outline: none; border: none; width: 100%; height: 140px;" placeholder="Write something here..."></textarea>
              <hr>
              <div style="text-align: right;">
                <p id="${id}-delete" style="display: none; margin: 0 8px 0 0;">Click again to delete.</p>
                <button class="btn btn-success btn-circle btn-sm" onClick="saveNote('${id}')">
                  <i class="fas fa-check"></i>
                </button>
                <button class="btn btn-danger btn-circle btn-sm" onClick="deleteNote('${id}')">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      `;
      document.getElementById('notepadAnchor').outerHTML += element;
      setTimeout(() => {
        document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
      }, 50);
    }

    function deleteNote(id) {
      if (deletePending[id]) {
        if (id[0] === 'A') {
          const element = document.getElementById(id);
          element.parentElement.removeChild(element);
        } else {
          postRequest('/Controllers/Admin/Dashboard.php', 'deleteNote', { id });
        }
      } else {
        deletePending[id] = true;
        document.getElementById(`${id}-delete`).style.display = 'inline-block';
        setTimeout(() => {
          const deleteElement = document.getElementById(`${id}-delete`);
          if (deleteElement) {
            deleteElement.style.display = 'none';
          }
          delete deletePending[id];
        }, 3000);
      }
    }

    function saveNote(id) {
      const note = document.getElementById(`${id}-data`).value;
      postRequest('/Controllers/Admin/Dashboard.php', id[0] === 'A' ? 'createNote' : 'editNote', { id, note });
    }

    function deleteAnalytics(all = false) {
      postRequest('/Controllers/Admin/Dashboard.php', 'deleteAnalytics', { type: all ? 'All' : 'Month' });
    }

    checkServerHealth();
    checkErrors();
  </script>
</body>
</html>
