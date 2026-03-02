<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Helpdesk System</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


</head>
<body>

<nav class="d-flex justify-content-between align-items-center px-3 py-2" style="background:#f5f5f5;">

    <div>

        @auth
            | <a href="/tickets" class="mx-2">Tickets</a>

            @if(auth()->user()->role === 'admin')
                | <a href="/categories" class="mx-2">Categories</a>
                | <a href="/departments" class="mx-2">Departments</a>
                | <a href="/users/create" class="mx-2">Create User</a>
            @endif
        @endauth
    </div>

    <div>
        @auth
            <span class="me-3">
                {{ auth()->user()->name }}
                ({{ ucfirst(auth()->user()->role) }})
            </span>

            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button class="btn btn-sm btn-outline-danger">
                    Logout
                </button>
            </form>
        @endauth


    </div>
</nav>



    <main style="padding:20px;">
        @yield('content')
    </main>

    @stack('scripts')

</body>
</html>
