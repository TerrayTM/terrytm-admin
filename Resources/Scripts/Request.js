function createForm(action, type, payload = null) {
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
  return form;
}

function postRequest(action, type, payload = null) {
  const form = createForm(action, type, payload);
  document.body.appendChild(form);
  form.submit();
}

async function asyncPostRequest(action, type, payload = null, files = null) {
  const body = new FormData(createForm(action, type, payload));
  if (files) {
    Object.keys(files).forEach((key) => {
      if (body.has(key)) {
        throw Error();
      }
      body.append(key, files[key])
    });
  }
  body.append('async', true);
  try {
    let response = await fetch(action, { body, method: 'post' });
    response = await response.json();
    if (response.success) {
      return { status: `[Success] POST: ${action}`, success: true, data: response.data };
    } else {
      throw Error();
    }
  } catch (error) {
    return { status: `[Error] POST: ${action}`, success: false };
  }
}
