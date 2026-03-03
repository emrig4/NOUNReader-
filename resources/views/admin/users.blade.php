<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cleanup Test Users - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-0">Cleanup Test Users</h4>
                                <small>Delete unwanted test accounts safely</small>
                            </div>
                            <div class="col-md-4">
                                <form method="GET" action="{{ route('cleanup-users') }}" class="d-flex">
                                    <input type="text" 
                                           name="search" 
                                           value="{{ old('search', $search ?? '') }}" 
                                           class="form-control form-control-sm me-2" 
                                           placeholder="Search by email or name...">
                                    <button type="submit" class="btn btn-sm btn-light">
                                        🔍 Search
                                    </button>
                                    @if($search ?? false)
                                        <a href="{{ route('cleanup-users') }}" class="btn btn-sm btn-outline-light ms-1">
                                            Clear
                                        </a>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6>📋 Instructions:</h6>
                                    <ul class="mb-0">
                                        <li>You can delete any user except yourself and other admins</li>
                                        <li>Click "Delete User" to remove test accounts</li>
                                        <li>Use search to find specific users quickly</li>
                                        <li>This is safer than using Tinker commands</li>
                                    </ul>
                                </div>
                                <div class="col-md-4 text-end">
                                    <small><strong>Total Users: {{ $users->total() }}</strong></small><br>
                                    <small>Showing {{ $users->count() }} of {{ $users->total() }}</small><br>
                                    @if($search ?? false)
                                        <small class="text-warning">🔍 Search: "{{ $search }}"</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Email</th>
                                        <th>Registered</th>
                                        <th>Admin Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->created_at->format('M j, Y g:i A') }}</td>
                                        <td>
                                            @if($user->is_admin)
                                                <span class="badge bg-success">Admin</span>
                                            @else
                                                <span class="badge bg-secondary">User</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('cleanup-users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete {{ $user->email }}? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete User</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            @if($search ?? false)
                                                <strong>🔍 No users found for "{{ $search }}"</strong>
                                                <br>
                                                <small>Try a different search term or clear the search.</small>
                                                <br>
                                                <a href="{{ route('cleanup-users') }}" class="btn btn-sm btn-outline-primary mt-2">View All Users</a>
                                            @else
                                                <strong>🎉 All test users cleaned up!</strong>
                                                <br>
                                                <small>Your database is now clean and ready.</small>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($users->hasPages())
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    Page {{ $users->currentPage() }} of {{ $users->lastPage() }}
                                    (Showing {{ $users->firstItem() }}-{{ $users->lastItem() }} of {{ $users->total() }} users)
                                </small>
                                <div>
                                    {{ $users->appends(request()->query())->links() }}
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="mt-4 text-center">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                            <a href="{{ route('logout') }}" class="btn btn-outline-danger">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


