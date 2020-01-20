function deleteMessages() {
  setTimeout(() => {
    postRequest('/Controllers/Admin/Dashboard.php', 'deleteMessages', { back: window.location.href });
  }, 0);
}

function logout() {
  postRequest('/Controllers/Admin/Login.php', 'logout');
}