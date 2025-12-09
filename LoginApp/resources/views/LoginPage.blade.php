<!--
    <!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login page</title>
    <style>
        h3{font-weight:bold; text-align:center; }
        body { text-align:center; font-family: Arial, Helvetica, sans-serif; margin-top:20px; }
        form { margin:0 auto; width:320px; padding:20px; border:1px solid #ccc; border-radius:10px; text-align:left; }
        label { font-weight:bold; }
        input { width:100%; padding:8px; margin-top:5px; box-sizing:border-box; }
        button { padding: 10px 50px; margin: 5px; cursor: pointer; color: red; }
        .success { color: #2b8; font-size:1em; margin-bottom:10px; }
        .unsuccess { color: rgba(204, 52, 52, 1); font-size:1em; margin-bottom:10px; }


    </style>
</head>
<body>
    <h1>Login</h1>
{{-- 
    @if(session('success'))
        <div class="success"> {{ session('success') }} </div>
    @endif

     @if(session('unsuccess'))
        <div class="unsuccess"> {{ session('unsuccess') }} </div>
    @endif
    @if ($errors->any())
    <div class="unsuccess">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <form action="{{ route('login.check') }}" method="POST">
        @csrf
 --}}
        <label>Enter Email:</label>
        <input type="email" name="email" required>
        <br><br>

        <label>Enter Password:</label>
        <input type="password" name="password" required>
        <br><br>

        <button type="submit">Login</button>
    </form>

    <h3> Don't have an Account? Register!!!</h3>
    {{-- <a href="{{ route('create.user') }}"> --}}
    <button type="button"> Register </button>
    </a>



</body>
</html> 
-->



<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Login Page</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    

    <div class="container" style="max-width: 400px;">
        <h2 class="text-center mb4 fw-bold">{{ session()->get('name') }}</h2>
        <!-- Title -->
        <h1 class="text-center mb-4 fw-bold">Login</h1>


        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Message -->
        @if(session('unsuccess'))
            <div class="alert alert-danger text-center">
                {{ session('unsuccess') }}
            </div>
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

        <!-- Login Card -->
        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('login.check') }}" method="POST">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Enter Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}"required>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Enter Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <!-- Login Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>

                </form>

            </div>
        </div>

        <!-- Register Button -->
        <p class="text-center mt-3 fw-semibold">
            Don't have an account? Register!
        </p>

        <div class="d-grid">
            <a href="{{ route('create.user') }}" class="btn btn-success">
                Register
            </a>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
