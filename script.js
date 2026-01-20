// Initialize Data
let events = JSON.parse(localStorage.getItem('events')) || [];
let attendance = JSON.parse(localStorage.getItem('attendance')) || [];

// Navigation
function showSection(sectionId) {
    document.querySelectorAll('.content-section').forEach(s => s.style.display = 'none');
    document.getElementById(sectionId).style.display = 'block';
    renderAll();
}

// Render everything
function renderAll() {
    renderEvents();
    renderAttendance();
    renderDashboard();
    updateSelectors();
}

// --- EVENT MANAGEMENT ---
const eventForm = document.getElementById('eventForm');
eventForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const id = document.getElementById('edit-id').value;
    const newEvent = {
        id: id ? parseInt(id) : Date.now(),
        title: document.getElementById('title').value,
        date: document.getElementById('date').value,
        description: document.getElementById('description').value
    };

    if (id) {
        events = events.map(ev => ev.id == id ? newEvent : ev);
    } else {
        events.push(newEvent);
    }

    localStorage.setItem('events', JSON.stringify(events));
    resetEventForm();
    renderAll();
    Swal.fire('Success', 'Event saved successfully!', 'success');
});

function renderEvents() {
    const list = document.getElementById('events-list');
    list.innerHTML = events.map(ev => `
        <div class="event-card">
            <h3>${ev.title}</h3>
            <p><strong>Date:</strong> ${new Date(ev.date).toLocaleString()}</p>
            <p>${ev.description}</p>
            <div class="event-actions">
                <button onclick="editEvent(${ev.id})" style="background:green">Edit</button>
                <button onclick="deleteEvent(${ev.id})" style="background:red">Delete</button>
            </div>
        </div>
    `).join('');
}

function deleteEvent(id) {
    events = events.filter(ev => ev.id !== id);
    attendance = attendance.filter(att => att.eventId !== id); // Cleanup attendance
    localStorage.setItem('events', JSON.stringify(events));
    localStorage.setItem('attendance', JSON.stringify(attendance));
    renderAll();
}

function editEvent(id) {
    const ev = events.find(e => e.id === id);
    document.getElementById('edit-id').value = ev.id;
    document.getElementById('title').value = ev.title;
    document.getElementById('date').value = ev.date;
    document.getElementById('description').value = ev.description;
    document.getElementById('form-title').innerText = "Edit Event";
    document.getElementById('cancel-edit').style.display = "inline-block";
}

function resetEventForm() {
    eventForm.reset();
    document.getElementById('edit-id').value = "";
    document.getElementById('form-title').innerText = "Add New Event";
    document.getElementById('cancel-edit').style.display = "none";
}

// --- ATTENDANCE MANAGEMENT ---
const attForm = document.getElementById('attendanceForm');
attForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const eventId = parseInt(document.getElementById('att-event-id').value);
    const status = document.getElementById('att-status').value;

    const existingIndex = attendance.findIndex(a => a.eventId === eventId);
    if (existingIndex > -1) {
        attendance[existingIndex].status = status;
    } else {
        attendance.push({ eventId, status });
    }

    localStorage.setItem('attendance', JSON.stringify(attendance));
    renderAll();
    Swal.fire('Updated', 'Attendance marked!', 'success');
});

function renderAttendance() {
    const list = document.getElementById('attendance-records');
    list.innerHTML = attendance.map(att => {
        const event = events.find(e => e.id === att.eventId);
        return `
            <tr>
                <td>${event ? event.title : 'Deleted Event'}</td>
                <td class="${att.status}">${att.status}</td>
                <td><button onclick="deleteAttendance(${att.eventId})">Remove</button></td>
            </tr>
        `;
    }).join('');
}

function deleteAttendance(eventId) {
    attendance = attendance.filter(a => a.eventId !== eventId);
    localStorage.setItem('attendance', JSON.stringify(attendance));
    renderAll();
}

function updateSelectors() {
    const select = document.getElementById('att-event-id');
    select.innerHTML = '<option value="">Select Event</option>' + 
        events.map(ev => `<option value="${ev.id}">${ev.title}</option>`).join('');
}

// --- DASHBOARD ---
function renderDashboard() {
    const total = events.length;
    const attended = attendance.filter(a => a.status === 'Present').length;
    const rate = total > 0 ? Math.round((attended / total) * 100) : 0;

    document.getElementById('dash-attendance-rate').innerText = `${rate}% attendance rate`;
    document.getElementById('dash-attendance-count').innerText = `${attended} events attended`;
    document.getElementById('dash-total-events').innerText = `Total Events: ${total}`;
}

// Initial Load
renderAll();