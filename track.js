document.getElementById('cycleForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const startDate = new Date(document.getElementById('start_date').value);
    const periodLength = parseInt(document.getElementById('period_length').value);
    const cycleLength = parseInt(document.getElementById('cycle_length').value);
    const email = document.getElementById('email').value;

    const ovulationDate = new Date(startDate);
    ovulationDate.setDate(ovulationDate.getDate() + 14);

    const nextPeriodDate = new Date(startDate);
    nextPeriodDate.setDate(nextPeriodDate.getDate() + cycleLength);

    document.getElementById('ovulation_date').innerText = ovulationDate.toDateString();
    document.getElementById('period_date').innerText = nextPeriodDate.toDateString();
    
    // Submit the form data to the server via AJAX
    const formData = new FormData();
    formData.append('start_date', startDate.toISOString().split('T')[0]);
    formData.append('period_length', periodLength);
    formData.append('cycle_length', cycleLength);
    formData.append('email', email);
    formData.append('ovulation_date', ovulationDate.toISOString().split('T')[0]);
    formData.append('next_period_date', nextPeriodDate.toISOString().split('T')[0]);

    fetch('track.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            // Optionally reset form inputs or perform other actions upon success
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('An error occurred while submitting the form.', 'error');
    });
});

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerText = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}




