<?php
// --- DB connect ---
$host = '127.0.0.1';
$db   = 'onepdf';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) { http_response_code(500); die('DB connect failed'); }
$mysqli->set_charset($charset);

// ---- helpers ----
function run($mysqli, $sql, $label){
    if (!$mysqli->query($sql)) {
        throw new Exception("$label failed: ".$mysqli->error);
    }
}
function getOne($mysqli, $sql){
    $res = $mysqli->query($sql);
    if (!$res) return null;
    $row = $res->fetch_row();
    $res->free();
    return $row ? $row[0] : null;
}

$tblOpts = " ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ";

// 1) Ensure USERS exists (default unsigned id) and engine is InnoDB
run($mysqli, "CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    use_id CHAR(16) NOT NULL,
    country VARCHAR(100) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    company VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    agreed_terms TINYINT(1) NOT NULL DEFAULT 0
) $tblOpts", "Create users");

$usersEngine = getOne($mysqli, "SELECT ENGINE FROM INFORMATION_SCHEMA.TABLES
  WHERE TABLE_SCHEMA='".$mysqli->real_escape_string($db)."' AND TABLE_NAME='users' LIMIT 1");
if ($usersEngine && strtoupper($usersEngine) !== 'INNODB') {
    run($mysqli, "ALTER TABLE users ENGINE=InnoDB", "Convert users to InnoDB");
}

// Detect EXACT type for users.id (e.g., 'int(10) unsigned', 'bigint(20) unsigned', etc.)
$fkUserIdColDef = getOne($mysqli, "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA='".$mysqli->real_escape_string($db)."'
    AND TABLE_NAME='users' AND COLUMN_NAME='id' LIMIT 1");
$fkUserIdColDef = $fkUserIdColDef ? strtoupper($fkUserIdColDef) : 'INT UNSIGNED';

// 2) Ensure PLANS exists and engine is InnoDB
run($mysqli, "CREATE TABLE IF NOT EXISTS plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    billing_cycle VARCHAR(20) NOT NULL
) $tblOpts", "Create plans");

$plansEngine = getOne($mysqli, "SELECT ENGINE FROM INFORMATION_SCHEMA.TABLES
  WHERE TABLE_SCHEMA='".$mysqli->real_escape_string($db)."' AND TABLE_NAME='plans' LIMIT 1");
if ($plansEngine && strtoupper($plansEngine) !== 'INNODB') {
    run($mysqli, "ALTER TABLE plans ENGINE=InnoDB", "Convert plans to InnoDB");
}

// Detect EXACT type for plans.id (handles legacy signed/unsigned/int/bigint)
$fkPlanIdColDef = getOne($mysqli, "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA='".$mysqli->real_escape_string($db)."'
    AND TABLE_NAME='plans' AND COLUMN_NAME='id' LIMIT 1");
$fkPlanIdColDef = $fkPlanIdColDef ? strtoupper($fkPlanIdColDef) : 'INT';

// Seed plans
run($mysqli, "INSERT INTO plans (id, name, price, billing_cycle) VALUES
 (1,'Free',0.00,'free'),
 (2,'Pro',12.00,'month'),
 (3,'Pro',499.00,'year'),
 (4,'Business',25.00,'month'),
 (5,'Business',1999.00,'year')
 ON DUPLICATE KEY UPDATE name=VALUES(name), price=VALUES(price), billing_cycle=VALUES(billing_cycle)", "Seed plans");

// 3) USER_PLAN — use detected exact types for both FKs
// 3) USER_PLAN — use detected exact types for both FKs (with status + timestamps)
run($mysqli, "CREATE TABLE IF NOT EXISTS user_plan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id $fkUserIdColDef NOT NULL,
    plan_id $fkPlanIdColDef NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE DEFAULT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 = Active, 2 = Inactive',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_user (user_id),
    KEY idx_plan (plan_id),
    CONSTRAINT fk_user_plan_user FOREIGN KEY (user_id) REFERENCES users(id)
      ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_user_plan_plan FOREIGN KEY (plan_id) REFERENCES plans(id)
      ON DELETE RESTRICT ON UPDATE CASCADE
) $tblOpts", "Create user_plan");


// 4) DOCUMENTS (FK → users)
run($mysqli, "CREATE TABLE IF NOT EXISTS documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id $fkUserIdColDef NOT NULL,
    filename VARCHAR(255) NOT NULL,
    filepath VARCHAR(255) NOT NULL,
    size BIGINT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_user (user_id),
    CONSTRAINT fk_documents_user FOREIGN KEY (user_id) REFERENCES users(id)
      ON DELETE CASCADE ON UPDATE CASCADE
) $tblOpts", "Create documents");

// Back-compat: ensure user_id exists if coming from very old installs
$col = getOne($mysqli, "SHOW COLUMNS FROM documents LIKE 'user_id'");
if (!$col) {
    run($mysqli, "ALTER TABLE documents ADD COLUMN user_id $fkUserIdColDef NOT NULL AFTER id", "Alter documents add user_id");
    run($mysqli, "ALTER TABLE documents ADD KEY idx_user (user_id)", "Alter documents add idx user");
    run($mysqli, "ALTER TABLE documents ADD CONSTRAINT fk_documents_user FOREIGN KEY (user_id) REFERENCES users(id)
      ON DELETE CASCADE ON UPDATE CASCADE", "Alter documents add fk");
}

// 5) LINKS (FK → documents)
run($mysqli, "CREATE TABLE IF NOT EXISTS links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    document_id INT NOT NULL,
    slug VARCHAR(20) NOT NULL UNIQUE,
    permissions JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_document (document_id),
    CONSTRAINT fk_links_document FOREIGN KEY (document_id) REFERENCES documents(id)
      ON DELETE CASCADE ON UPDATE CASCADE
) $tblOpts", "Create links");

// 6) LINK ANALYTICS (FK → links)
run($mysqli, "CREATE TABLE IF NOT EXISTS link_analytics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    link_id INT NOT NULL,
    event VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_link (link_id),
    CONSTRAINT fk_la_link FOREIGN KEY (link_id) REFERENCES links(id)
      ON DELETE CASCADE ON UPDATE CASCADE
) $tblOpts", "Create link_analytics");

// 7) CONTACT & NEWSLETTER (no FKs)
run($mysqli, "CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    company VARCHAR(100),
    subject VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) $tblOpts", "Create contact_messages");

run($mysqli, "CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) $tblOpts", "Create newsletter_subscribers");

// 8) NOTIFICATIONS (FK → users) — drop & recreate to avoid partial schema
run($mysqli, "DROP TABLE IF EXISTS notifications", "Drop notifications (if exists)");
run($mysqli, "CREATE TABLE notifications (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id $fkUserIdColDef NULL,
    audience ENUM('user','all') NOT NULL DEFAULT 'user',
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info','success','warning','error','system') DEFAULT 'info',
    priority TINYINT UNSIGNED NOT NULL DEFAULT 1,
    action_url VARCHAR(1024) DEFAULT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    read_at DATETIME DEFAULT NULL,
    metadata JSON DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_user_unread_created (audience, user_id, is_read, created_at),
    KEY idx_created (created_at),
    KEY idx_user (user_id)
) $tblOpts", "Create notifications (no FK yet)");

run($mysqli, "ALTER TABLE notifications
    ADD CONSTRAINT fk_notifications_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE ON UPDATE CASCADE", "Add FK notifications->users");
