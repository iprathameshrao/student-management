<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-white bg-white shadow-sm">
        <div class="container-fluid">

            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="#">
                <span class="badge bg-primary rounded-circle p-2">D</span>
                Dashboarddd
            </a>

            <div class="d-flex ms-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>


    <!-- MAIN CONTENT -->
    <div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 80vh;">

        <div class="text-center mb-4">
            {{-- <h1 class="fw-semibold">Welcome, {{ Auth::user()->name }} 👋</h1> --}}
             <h1 class="fw-semibold">Welcome,{{ session()->get('teacher_name') }} 👋</h1>{{-- here we take teacher name from sesson --}}

            
            <p class="text-secondary">Manage your students and account from here.</p>
        </div>

        <div class="card shadow-sm p-4" style="max-width: 500px; width: 100%;">
            <div class="d-grid gap-3">

                <a class="btn btn-primary btn-lg" href="{{ route('students.index') }}">
                    📚 View All Students
                </a>

                <a class="btn btn-success btn-lg" href="{{ route('students.create') }}">
                    ➕ Add New Student
                </a>

                <a class="btn btn-outline-secondary btn-lg" href="#">
                    ⚙️ Profile Settings (Optional)
                </a>

            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
