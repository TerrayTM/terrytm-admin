function postRequest(action, type, payload = null) {
  const form = document.createElement('form');
  form.setAttribute('action', action);
  form.setAttribute('method', 'post');
  if (payload) {
    payload.token = token;
  } else {
    payload = { token };
  }
  payload.request = type;
  Object.keys(payload).forEach((key) => {
    const param = document.createElement('input');
    param.setAttribute('type', 'hidden');
    param.setAttribute('name', key);
    param.setAttribute('value', payload[key]);
    form.appendChild(param);
  });
  document.body.appendChild(form);
  form.submit();
}