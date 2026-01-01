document.addEventListener("DOMContentLoaded", () => {

    
    const toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
    });

    /* ---------- ADD EVENT ---------- */
    const eventForm = document.getElementById("eventForm");

    if (eventForm) {
        eventForm.addEventListener("submit", e => {
            e.preventDefault();

            const data = new FormData(eventForm);

            fetch("add_event.php", {
                method: "POST",
                body: data
            })
            .then(res => res.text())
            .then(resp => {
                console.log("SERVER RESPONSE:", resp);

                if (resp.trim() === "success") {
                    Swal.fire("Success!", "Event added successfully.", "success")
                        .then(() => location.reload());
                } else {
                    Swal.fire("Error", resp, "error");
                }
            })
            .catch(err => {
                Swal.fire("Error", err.toString(), "error");
            });
        });
    }

    /* ---------- VIEW EVENT DETAILS ---------- */
    window.viewDetails = function(title, desc, date) {
        Swal.fire({
            title: title,
            html: `<p><b>Date:</b> ${date}</p><p>${desc}</p>`,
            icon: "info"
        });
    };
 /* ---------- EDIT / DELETE EVENTS ---------- */

window.deleteEvent = function(id) {
    Swal.fire({
        title: "Delete Event?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Delete"
    }).then(res => {
        if (res.isConfirmed) {
            fetch("delete_event.php", {
                method: "POST",
                body: new URLSearchParams({ id })
            })
            .then(r => r.text())
            .then(resp => {
                if (resp.trim() === "success") {
                    Swal.fire("Deleted!", "", "success")
                        .then(() => location.reload());
                }
            });
        }
    });
};

window.openEditEvent = function(id, title, desc, date) {
    Swal.fire({
        title: "Edit Event",
        html: `
            <input id="eTitle" class="swal2-input" value="${title}">
            <input id="eDate" type="datetime-local" class="swal2-input" value="${date}">
            <textarea id="eDesc" class="swal2-textarea">${desc}</textarea>
        `,
        preConfirm: () => ({
            title: document.getElementById("eTitle").value,
            date: document.getElementById("eDate").value,
            description: document.getElementById("eDesc").value
        })
    }).then(res => {
        if (res.isConfirmed) {
            fetch("edit_event.php", {
                method: "POST",
                body: new URLSearchParams({
                    id,
                    title: res.value.title,
                    date: res.value.date,
                    description: res.value.description
                })
            })
            .then(r => r.text())
            .then(resp => {
                if (resp.trim() === "success") {
                    Swal.fire("Updated!", "", "success")
                        .then(() => location.reload());
                }
            });
        }
    });
};

    /* ---------- MARK ATTENDANCE ---------- */
    const attendanceForm = document.getElementById("attendanceForm");

    if (attendanceForm) {
        attendanceForm.addEventListener("submit", e => {
            e.preventDefault();

            fetch("mark_attendance.php", {
                method: "POST",
                body: new FormData(attendanceForm)
            })
            .then(r => r.text())
            .then(res => {
                if (res.trim() === "success") {
                    toast.fire({
                        icon: "success",
                        title: "Attendance updated"
                    });
                    setTimeout(() => location.reload(), 1200);
                } else {
                    toast.fire({
                        icon: "error",
                        title: "Update failed"
                    });
                }
            });
        });
    }

    
    const btn = document.getElementById("getStartedBtn");
    const modal = document.getElementById("startModal");

    if (btn && modal) {
        btn.onclick = () => modal.style.display = "flex";
        modal.onclick = () => modal.style.display = "none";
    }

});
