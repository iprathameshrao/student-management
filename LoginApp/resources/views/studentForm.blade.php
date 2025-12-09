
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Student</title>

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* small custom tweaks */
        body { background-color: #f8f9fa; }
        .card { max-width: 720px; margin: 36px auto; border: 0; border-radius: 12px; box-shadow: 0 6px 18px rgba(50,50,93,0.08); }
        .form-help { font-size: .85rem; color: #6c757d; }
        .required::after { content: "*"; color: #d9534f; margin-left: 3px; font-weight: 600; }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <h3 class="card-title mb-0 me-3">Create New Student</h3>
                <small class="text-muted">Add student details</small>
            </div>

            <!-- Flash messages (success / error) -->
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Validation errors summary -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>There are some problems with your input.</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('students.store') }}" method="POST" class="row g-3 needs-validation" novalidate>
                @csrf

                <!-- Student Name -->
                <div class="col-12">
                    <label for="name" class="form-label required">Student Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name') }}" placeholder="Enter full name" required autofocus>
                    
                </div>

                <!-- Student Class -->
                <div class="col-md-6">
                    <label for="class" class="form-label required">Class</label>
                    <input type="text" class="form-control" id="class" name="class"
                        value="{{ old('class') }}" placeholder="e.g. MCA, 2nd Year" required>
                </div>

                <!-- Phone Number -->
                <div class="col-md-6">
                    <label for="phonenumber" class="form-label required">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text">+91</span>
                        <input type="tel" maxlength="10"
                               pattern="[6-9]{1}[0-9]{9}"
                               class="form-control @error('phonenumber') is-invalid @enderror"
                               id="phonenumber" name="phonenumber"
                               value="{{ old('phonenumber') }}" placeholder="9876543210" required>
                        @error('phonenumber')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @else
                            <div class="form-help">10 digits. starts with 6-9.</div>
                        @enderror
                    </div>
                </div>

                <!-- State Select (your component) -->
                <div class="col-md-8">
                    <label for="state" class="form-label required" value="{{ old('state') }}">State</label>
                    <!-- If <x-state-select /> renders a select, make sure it contains id="state" and name="state" -->
                    <x-state-select :selected="old('state')" id="state" class="form-select @error('state') is-invalid @enderror" />
                   
                </div>

                <!-- Optional Teacher ID (hidden or visible depending on your app) -->
                {{-- Uncomment if you want teacher_id input on create form --}}
                <div class="col-md-4">
                    <label for="teacher_id" class="form-label required">Teacher ID</label>
                    <input type="number" id="teacher_id" name="teacher_id" value="{{ old('teacher_id') }}"
                           class="form-control"  required>
                </div>

                <!-- Submit / Cancel -->
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ url('/students') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Student</button>
                </div>
            </form>

            <!-- Small note beneath the form -->
            <p class="text-muted small mt-3 mb-0">All fields marked with <span class="required"></span> are required.</p>
        </div>
    </div>
</div>

<!-- Bootstrap JS (for validation feedback & components) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Client-side bootstrap validation script (progressive enhancement) -->
<script>
    (function () {
        'use strict'
        // Fetch forms we want to apply custom validation to
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                // Use browser validation first
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>

</body>
</html>
