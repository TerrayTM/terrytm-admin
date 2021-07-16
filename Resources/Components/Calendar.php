<script src="/Resources/vendor/scheduler/dhtmlxscheduler.js"></script>
<script src="/Resources/vendor/scheduler/ext/dhtmlxscheduler_limit.js"></script>
<script>
  scheduler.config.hour_date="%h:%i %A";
  scheduler.xy.scale_width = 70;
  scheduler.init('scheduler', new Date(), 'week');

  scheduler.attachEvent('onEventDeleted', (id, event) => {
    asyncPostRequest('/Controllers/Admin/Calendar.php', 'delete', { id }).then((response) => {
      if (!response.success) {
        document.getElementById('errorMessage').style.display = 'block';
      }
    });
  });

  scheduler.attachEvent('onEventChanged', (id, event) => {
    asyncPostRequest('/Controllers/Admin/Calendar.php', 'edit', {
      start_date: event['start_date'].toISOString().slice(0, 19).replace('T', ' '),
      end_date: event['end_date'].toISOString().slice(0, 19).replace('T', ' '),
      text: event['text'],
      id: event['id']
    }).then((response) => {
      if (!response.success) {
        document.getElementById('errorMessage').style.display = 'block';
      }
    });
  });

  scheduler.attachEvent('onEventAdded', (id, event) => {
    asyncPostRequest('/Controllers/Admin/Calendar.php', 'create', {
      start_date: event['start_date'].toISOString().slice(0, 19).replace('T', ' '),
      end_date: event['end_date'].toISOString().slice(0, 19).replace('T', ' '),
      text: event['text'],
      id: event['id']
    }).then((response) => {
      if (!response.success) {
        document.getElementById('errorMessage').style.display = 'block';
      }
    });
  });

  asyncPostRequest('/Controllers/Admin/Calendar.php', 'load').then((response) => {
    if (response.success && response.data.events) {
      scheduler.parse(response.data.events.map((event) => {
        return {
          start_date: new Date(`${event.start_date} UTC`),
          end_date: new Date(`${event.end_date} UTC`),
          text: event.text,
          id: event.event_id
        };
      }));
      document.getElementById('title').innerText = 'Calendar';
    } else {
      document.getElementById('errorMessage').style.display = 'block';
    }
  });
</script>
