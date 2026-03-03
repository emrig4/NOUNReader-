<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pending Resources - WORKING</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .btn-success {
            background-color: #23A455 !important;
            border-color: #23A455 !important;
        }
        .btn-success:hover {
            background-color: #1e8a47 !important;
            border-color: #1e8a47 !important;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">Simple Admin Pending Resources</h1>
                
                <!-- Success Messages -->
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if(isset($pendingResources) && $pendingResources->count() > 0): ?>
                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h5 class="card-title mb-0">Pending Resources</h5>
                                            <h3 class="text-warning mb-0"><?= $pendingResources->total() ?></h3>
                                        </div>
                                        <div class="ms-3">
                                            <i class="bi bi-clock h2 text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Resources Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Pending Resources List</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Field</th>
                                            <th>Submitted</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($pendingResources as $resource): ?>
                                            <tr>
                                                <td>
                                                    <strong>#<?= $resource->id ?></strong>
                                                </td>
                                                <td>
                                                    <div class="fw-medium"><?= htmlspecialchars(Str::limit($resource->title, 60)) ?></div>
                                                    <?php if($resource->admin_notes): ?>
                                                        <small class="text-muted">
                                                            <i class="bi bi-sticky"></i> Previous notes available
                                                        </small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary"><?= ucfirst($resource->type) ?></span>
                                                </td>
                                                <td><?= ucfirst($resource->field) ?></td>
                                                <td>
                                                    <?php if($resource->submitted_at): ?>
                                                        <div class="small">
                                                            <?= date('M d, Y', strtotime($resource->submitted_at)) ?>
                                                        </div>
                                                        <small class="text-muted">
                                                            <?= date('H:i', strtotime($resource->submitted_at)) ?>
                                                        </small>
                                                    <?php else: ?>
                                                        <span class="text-muted">Unknown</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <!-- Approve Button -->
                                                        <form method="POST" action="/admin/simple-approve/<?= $resource->id ?>" class="d-inline">
                                                            <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                                                            <button type="submit" 
                                                                    class="btn btn-success btn-sm"
                                                                    title="Approve Resource #<?= $resource->id ?>"
                                                                    onclick="return confirm('Approve Resource #<?= $resource->id ?>?')">
                                                                <i class="bi bi-check"></i> Approve
                                                            </button>
                                                        </form>
                                                        
                                                        <!-- Reject Button -->
                                                        <button type="button" 
                                                                class="btn btn-danger btn-sm"
                                                                title="Reject Resource #<?= $resource->id ?>"
                                                                onclick="rejectResource(<?= $resource->id ?>, '<?= addslashes($resource->title) ?>')">
                                                            <i class="bi bi-x"></i> Reject
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <?php if($pendingResources->hasPages()): ?>
                                <div class="mt-3">
                                    <?= $pendingResources->links() ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- No Resources -->
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                            <h3 class="mt-3 text-success">No Pending Resources!</h3>
                            <p class="text-muted">All resources have been reviewed.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Resource</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="rejectForm" method="POST">
                    <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                    <div class="modal-body">
                        <p id="rejectQuestion">Are you sure you want to reject this resource?</p>
                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">Admin Notes (Required)</label>
                            <textarea class="form-control" id="admin_notes" name="admin_notes" rows="4" required 
                                      placeholder="Please provide feedback on why this resource is being rejected..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject Resource</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function rejectResource(resourceId, resourceTitle) {
            document.getElementById('rejectQuestion').textContent = 
                'Are you sure you want to reject Resource #' + resourceId + '?';
            document.getElementById('rejectForm').action = '/admin/simple-reject/' + resourceId;
            new bootstrap.Modal(document.getElementById('rejectModal')).show();
        }
    </script>
</body>
</html>
