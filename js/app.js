// Frontend JavaScript: communicates with the PHP backend.
const API = "../backend";

// Hide the logo gracefully if its image is unavailable.
document.querySelectorAll(".logo").forEach(img => {
  img.addEventListener("error", () => { img.style.display = "none"; });
});

// Home page: check whether the backend is reachable.
const status = document.getElementById("server-status");
if (status) {
  fetch(`${API}/get_students.php`)
    .then(response => {
      if (!response.ok) throw new Error("Backend request failed");
      return response.json();
    })
    .then(data => {
      status.textContent = `Connected ✓ (${data.count} students saved)`;
      status.className = "ok";
    })
    .catch(() => {
      status.textContent = "Not reachable — start the PHP server (see README)";
      status.className = "bad";
    });
}

// Form page: submit data without leaving the page.
const form = document.getElementById("studentForm");
if (form) {
  form.addEventListener("submit", async event => {
    event.preventDefault();
    const msg = document.getElementById("msg");
    const button = document.getElementById("submitBtn");
    msg.textContent = "";
    button.disabled = true;
    button.textContent = "Submitting…";
    try {
      const response = await fetch(`${API}/submit.php`, {
        method: "POST",
        body: new FormData(form)
      });
      const data = await response.json();
      if (!response.ok || !data.success) {
        msg.className = "error";
        msg.textContent = (data.errors || ["Could not save the student record."]).join(" ");
      } else {
        msg.className = "success";
        const link = document.createElement("a");
        link.href = "table.html";
        link.textContent = "View result table →";
        msg.textContent = data.message + " ";
        msg.appendChild(link);
        form.reset();
      }
    } catch (error) {
      msg.className = "error";
      msg.textContent = "Could not reach the backend. Start the PHP server and try again.";
    } finally {
      button.disabled = false;
      button.textContent = "Submit Registration";
    }
  });
}

// Table page: load records and calculate the class average on the server.
const tableBody = document.getElementById("tableBody");
if (tableBody) {
  fetch(`${API}/get_students.php`)
    .then(response => {
      if (!response.ok) throw new Error("Could not load records");
      return response.json();
    })
    .then(data => {
      document.getElementById("student-count").textContent = data.count;
      document.getElementById("average").textContent = `${data.average}%`;
      if (!data.students.length) {
        tableBody.innerHTML = '<tr><td colspan="7" class="loading">No students registered yet. Use the registration form to add the first record.</td></tr>';
        return;
      }
      tableBody.replaceChildren();
      data.students.forEach((student, index) => {
        const row = document.createElement("tr");
        [index + 1, student.enrollment, student.name, student.course,
          student.gender, student.marks].forEach(value => {
          const cell = document.createElement("td");
          cell.textContent = value;
          row.appendChild(cell);
        });
        const gradeCell = document.createElement("td");
        gradeCell.textContent = student.grade;
        gradeCell.className = "grade";
        row.appendChild(gradeCell);
        tableBody.appendChild(row);
      });
    })
    .catch(() => {
      tableBody.innerHTML = '<tr><td colspan="7" class="loading">Could not load data. Start the PHP server and refresh this page.</td></tr>';
    });
}
