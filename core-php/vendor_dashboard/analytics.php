<?php
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login');
    exit;
}

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';

// --- Analytics calculations ---
$visits = 0;
$topDocs = [];

if (isset($mysqli)) {
    // Total visits in last 7 days
    $result = $mysqli->query("SELECT COUNT(*) AS cnt FROM link_analytics WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    if ($result) {
        $row = $result->fetch_assoc();
        $visits = (int)$row['cnt'];
    }

    // Top documents by views
    $docsSql = "SELECT d.filename, COUNT(*) AS views
                FROM link_analytics la
                JOIN links l ON la.link_id = l.id
                JOIN documents d ON l.document_id = d.id
                GROUP BY d.id
                ORDER BY views DESC
                LIMIT 5";
    if ($docsRes = $mysqli->query($docsSql)) {
        $topDocs = $docsRes->fetch_all(MYSQLI_ASSOC);
    }
}
?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Analytics</h1>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Visits (Last 7 Days)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $visits; ?></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Average Reading Time</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">N/A</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Reading Time</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">N/A</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 5 Documents</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Document</th>
                                    <th>Views</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($topDocs): ?>
                                    <?php foreach ($topDocs as $doc): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($doc['filename']); ?></td>
                                            <td><?php echo (int)$doc['views']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="2" class="text-center">No data</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 5 Visitor Cities</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">Visitor location analytics are not available.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
