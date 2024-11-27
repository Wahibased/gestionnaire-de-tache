fetch('/notifications')
  .then(response => response.json())
  .then(notifications => {
    const notificationContainer = document.querySelector('.notifications');
    notifications.forEach(notification => {
      const para = document.createElement('p');
      para.textContent = `${notification.message} - ${new Date(notification.timestamp).toLocaleString()}`;
      notificationContainer.appendChild(para);
    });
  })
  .catch(error => console.error('Erreur:', error));



