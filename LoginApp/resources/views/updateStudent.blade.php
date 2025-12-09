<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Update Student</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h1 class="h4 mb-0">Update Student Information</h1>
                        <small class="text-muted">Edit details and press Update</small>
                    </div>

                    <div>
                        <a href="{{ url('/students') }}" class="btn btn-outline-secondary btn-sm">← Back to List</a>
                    </div>
                </div>

                <!-- Alerts: session & validation -->
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('unsuccess'))
                    <div class="alert alert-danger">{{ session('unsuccess') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Card with Form -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('students.update', $students['id']) }}" method="POST" novalidate>
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Teacher Id</label>
                                <input type="number" name="teacher_id" class="form-control" value="{{ $students['teacher_id'] }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Student Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $students['name'] }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Student Class</label>
                                <input type="text" name="class" class="form-control" value="{{ $students['class'] }}" required>
                            </div>

                             <!-- State Dropdown Component -->
                            <x-state-select />

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Student Phone Number</label>
                                <input type="tel" name="phonenumber" class="form-control" maxlength="10" value="{{ $students['phonenumber'] }}" required>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ url('/students') }}" class="btn btn-outline-secondary">Cancel</a>
                                </div>

                                <div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

{{-- {{ $students->id }}
{{ $students->teacher_id }}
{{ $students->name }}
{{ $students->class }}
{{ $students->state }}
{{ $students->phonenumber }} --}}
