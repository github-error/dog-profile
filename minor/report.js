// Function to switch between sections
function showSection(sectionId) {
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => section.classList.add('hidden'));

    document.getElementById(sectionId).classList.remove('hidden');

    // Remove active class from all sidebar items
    document.querySelectorAll('.sidebar ul li').forEach(item => item.classList.remove('active'));

    // Add active class to clicked item
    event.target.classList.add('active');
}

// User Reports Chart
const ctxUser = document.getElementById('userChart').getContext('2d');
new Chart(ctxUser, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
        datasets: [{
            label: 'New Users',
            data: [500, 700, 900, 1200, 1400],
            backgroundColor: '#3498db'
        }]
    },
    options: { responsive: true }
});

// Dog & Cattle Reports Chart
const ctxDog = document.getElementById('dogChart').getContext('2d');
new Chart(ctxDog, {
    type: 'pie',
    data: {
        labels: ['Labrador', 'Bulldog', 'German Shepherd', 'Jersey Cattle', 'Holstein Cattle'],
        datasets: [{
            label: 'Breed Popularity',
            data: [40, 20, 30, 25, 35],
            backgroundColor: ['#e74c3c', '#2ecc71', '#f1c40f', '#9b59b6', '#3498db']
        }]
    },
    options: { responsive: true }
});

// Engagement Reports Chart
const ctxEngagement = document.getElementById('engagementChart').getContext('2d');
new Chart(ctxEngagement, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
        datasets: [{
            label: 'Total Interactions',
            data: [5000, 8000, 12000, 15000, 20000],
            borderColor: '#e67e22',
            fill: false
        }]
    },
    options: { responsive: true }
});