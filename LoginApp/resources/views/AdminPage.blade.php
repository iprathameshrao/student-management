<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Page</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-white bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <span class="badge bg-primary fs-6">S</span>
                <span class="fw-bold">Students</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain"
                aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-3 text-end">
                        <div class="small text-muted">Teacher</div>
                        <div class="fw-semibold">{{ $teacher }}</div>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                        </form>
                    </li>
                </ul>
            </div> dashboard
        </div>
    </nav>

    <!-- MAIN -->
    <div class="container my-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-3">
            <div>
                <h1 class="h4 mb-0">List Of All Students Under Teacher</h1>
                <div class="text-muted small">Records assigned to the selected teacher</div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ url('/students/create') }}" class="btn btn-success">Add New Student</a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
            </div>
        </div>

        <!-- Controls: search + count -->
        <div class="row mb-3">
            <div class="col-md-6 mb-2 mb-md-0">
                <div class="input-group">
                    <span class="input-group-text">🔎</span>
                    <input id="tableSearch" type="text" class="form-control" placeholder="Search by name, state, phone...">
                </div>
            </div>

            <div class="col-md-6 text-md-end">
                <div class="d-inline-block align-middle">
                    <span class="badge bg-info text-dark">
                        Total:
                        @if($students instanceof \Illuminate\Support\Collection)
                            {{ $students->count() }}
                        @elseif(is_array($students))
                            {{ count($students) }}
                        @else
                            1
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- TABLE CARD -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="studentsTable" class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Teacher ID</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>State</th>
                                <th>Phone</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                            <tbody>
                                @foreach ($students as $s)
                                    <tr>
                                        <td>{{ $s['id'] }}</td>
                                        <td>{{ $s['teacher_id'] }}</td>
                                        <td>{{ $s['name'] }}</td>
                                        <td>{{ $s['class'] }}</td>
                                        <td>{{ $s['state'] }}</td>
                                        <td>{{ $s['phonenumber'] }}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group" aria-label="actions">
                                                <a href="{{ url('/students/' . $s['id']) }}" class="btn btn-outline-info">View</a>
                                                <a href="{{ route('students.edit', ['student' => $s['id']]) }}" class="btn btn-outline-warning">Edit</a>

                                                <form action="{{ route('students.destroy', ['student' => $s['id']]) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this student?');">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- BOTTOM ACTIONS -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="btn-group">
                <a href="{{ url('/students/') }}" class="btn btn-outline-primary">View All Student List</a>
                <!-- Delete all kept commented as original -->
            </div>

            <div>
                <!-- If you later use paginator on backend, uncomment this and pass $students as paginator -->
                {{-- {{ $students->links() }} --}}
            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Small JS: client-side table search (works with current markup) -->
    <script>
        (function(){
            const input = document.getElementById('tableSearch');
            if(!input) return;
            input.addEventListener('input', function(){
                const q = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('#studentsTable tbody tr');
                rows.forEach(r => {
                    const text = r.innerText.toLowerCase();
                    r.style.display = text.indexOf(q) === -1 ? 'none' : '';
                });
            });
        })();
    </script>

</body>

</html>
