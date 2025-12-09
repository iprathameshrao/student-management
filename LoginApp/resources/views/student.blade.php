<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Students Table</title>
</head>
<body>

<h2>Students List (From API)</h2>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Class</th>
            <th>State</th>
            <th>Phone</th>
        </tr>
    </thead>

    <tbody id="student-table-body">
        <!-- Rows will be added here -->
    </tbody>
</table>

<script>
// 1. API URL
const apiUrl = "http://localhost:8002/api/view";

// 2. Call API using fetch()
fetch(apiUrl)
    .then(response => response.json())  // convert JSON text → JS object
    .then(data => {

        // 3. Get table body element
        const tableBody = document.getElementById("student-table-body");

        // 4. Loop through each student in API response
        data.forEach(student => {

            // 5. Create a new table row
            const row = document.createElement("tr");

            // 6. Create and fill each cell (td)
            row.innerHTML = `
                <td>${student.Id}</td>
                <td>${student.Name}</td>
                <td>${student.Class}</td>
                <td>${student.State}</td>
                <td>${student.PhoneNumber}</td>
            `;

            // 7. Add row into table body
            tableBody.appendChild(row);
        });

    })
    .catch(error => {
        console.log("Error:", error);
    });
</script>

</body>
</html>
