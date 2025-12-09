<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Create User</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">

    <div class="container" style="max-width: 420px;">

        <!-- Title -->
        <h1 class="text-center mb-4 fw-bold">Create New User</h1>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>There were errors with your submission:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('create.user.store') }}" method="POST">
                    @csrf
                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">User Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">User Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                  
                    <div class="mb-3">
                    <label class="form-label fw-semibold">Role</label>
                    <select name="role" class="form-control" required>
                        <option value="" disabled selected>Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                    </select>
                    </div>
                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Create User</button>
                    </div>

                </form>

            </div>
        </div>

        <!-- Already Registered -->
        <p class="text-center mt-3 fw-semibold">Already Registered?</p>

        <div class="d-grid">
            <a href="{{ route('login.page') }}" class="btn btn-outline-secondary">
                Login
            </a>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
