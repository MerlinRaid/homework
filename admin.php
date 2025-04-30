<?php
require_once("include/settings_example.php");
require_once("include/mysqli.php");
$db = new Db();

// Kontrolli admini sisselogimist
if ($_COOKIE["admin_auth"] !== "true") {
    header("Location: login.php");
    exit;
}

// Kustutamine
if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);
    $db->dbQuery("DELETE FROM feedback WHERE id = " . $db->dbFix($id));
    header("Location: index.php?page=admin");
    exit;
}

// Andmete laadimine
$rows = $db->dbGetArray("SELECT * FROM feedback ORDER BY submitted_at DESC");

// Kuupäev eesti keeles
setlocale(LC_TIME, 'et_EE.UTF-8');
?>
<div class="container mt-5">
    <h2>Laekunud tagasiside</h2>
    <div class="d-flex justify-content-end align-items-center mb-3">
        <a href="index.php" class="btn btn-outline-success me-1">Avaleht</a>
        <a href="logout.php" class="btn btn-outline-danger">Logi välja</a>
    </div>

    <?php if (!empty($rows)): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Aeg</th>
                    <th>Nimi</th>
                    <th>E-post</th>
                    <th>Sõnum</th>
                    <th>Kustuta</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><?= date('d.m.Y H:i', strtotime($r["submitted_at"])) ?></td>
                        <td><?= htmlspecialchars($r["name"]) ?></td>
                        <td><?= htmlspecialchars($r["email"]) ?></td>
                        <td><?= nl2br(htmlspecialchars($r["message"])) ?></td>
                        <td>
                            <a href="index.php?page=admin&delete=<?= $r["id"] ?>"
                               onclick="return confirm('Kustuta see kirje?')"
                               class="btn btn-sm btn-outline-danger">X</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted">Tagasisidet ei ole veel saabunud.</p>
    <?php endif; ?>
</div>
