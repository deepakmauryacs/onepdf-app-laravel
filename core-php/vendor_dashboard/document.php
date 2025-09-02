<?php
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login');
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$doc = null;
$linkUrl = '';
$views = 0;

if ($id && isset($mysqli)) {
    $stmt = $mysqli->prepare("SELECT d.filename, d.size, d.uploaded_at, l.slug,
        (SELECT COUNT(*) FROM link_analytics la JOIN links l2 ON la.link_id = l2.id WHERE l2.document_id = d.id) AS views
        FROM documents d LEFT JOIN links l ON l.document_id = d.id
        WHERE d.id = ? AND d.user_id = ? LIMIT 1");
    $stmt->bind_param('ii', $id, $_SESSION['user_id']);
    $stmt->execute();
    $res = $stmt->get_result();
    $doc = $res ? $res->fetch_assoc() : null;
    $stmt->close();
    if ($doc && !empty($doc['slug'])) {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $basePath = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
        $linkUrl = $scheme . $host . $basePath . '/view?doc=' . urlencode($doc['slug']);
    }
    $views = (int)($doc['views'] ?? 0);
}

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';

if (!$doc) {
    echo '<div class="container-fluid"><p class="mb-0">Document not found.</p></div>';
    include 'includes/footer.php';
    exit;
}
?>
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo htmlspecialchars($doc['filename']); ?></h1>
  </div>
  <div class="card mb-4">
    <div class="card-body">
      <p><strong>Size:</strong> <?php echo number_format($doc['size'] / 1024, 2); ?> KB</p>
      <p><strong>Uploaded:</strong> <?php echo htmlspecialchars($doc['uploaded_at']); ?></p>
      <p><strong>Views:</strong> <span id="viewCount"><?php echo $views; ?></span></p>
      <div id="linkSection">
        <?php if ($linkUrl): ?>
          <div class="input-group mb-3">
            <input type="text" class="form-control" id="viewerUrl" value="<?php echo htmlspecialchars($linkUrl); ?>" readonly>
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" id="copyLink" type="button">Copy</button>
            </div>
          </div>
          <a class="btn btn-sm btn-primary" href="embed.php?url=<?php echo urlencode($linkUrl); ?>">Embed Viewer</a>
        <?php else: ?>
          <button class="btn btn-primary" id="genLink" type="button">Generate Link</button>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function bindCopy(){
  $("#copyLink").on("click", function(){
    navigator.clipboard.writeText($("#viewerUrl").val());
  });
}
bindCopy();
$("#genLink").on("click", function(){
  $.post("api/generate_link.php", {id: <?php echo $id; ?>}, function(res){
    if(res.url){
      var html = '<div class="input-group mb-3">'
                 + '<input type="text" class="form-control" id="viewerUrl" value="'+res.url+'" readonly>'
                 + '<div class="input-group-append"><button class="btn btn-outline-secondary" id="copyLink" type="button">Copy</button></div></div>'
                 + '<a class="btn btn-sm btn-primary" href="embed.php?url='+encodeURIComponent(res.url)+'">Embed Viewer</a>';
      $("#linkSection").html(html);
      bindCopy();
    }
  }, 'json');
});
</script>
<?php include 'includes/footer.php'; ?>
