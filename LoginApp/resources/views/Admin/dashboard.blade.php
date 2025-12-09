<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Admin Page</title>
  </head>
  <body>

    <div class="text-center" style="margin-top: 50px;" >

        <h1>This is Admin Dashboard Page</h1>
  
    <a class="btn btn-primary" href="{{ route('create.user') }}" role="button">Create Teachers</a>
    <a class="btn btn-primary" href="{{ route('students.create') }}" role="button">Create Students</a>
    <a class="btn btn-primary" href="{{ route('teachers.all') }}" role="button">View Teachers</a>
    <a class="btn btn-primary" href="{{ route('students.all') }}" role="button">View All Students</a>
     <br><br>

        <!-- LOGOUT BUTTON -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-danger" type="submit">Logout</button>
        </form>

</div>

</body>
</html>
