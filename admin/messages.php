<?php
// admin/messages.php - Manage Customer Inquiries
$adminTitle = "Messages Inbox";
require_once __DIR__ . '/header.php';

// Handle Actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($_GET['action'] === 'mark_read') {
        $stmt = $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: messages.php?msg=read');
        exit;
    } elseif ($_GET['action'] === 'delete') {
        $stmt = $db->prepare("DELETE FROM contact_messages WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: messages.php?msg=deleted');
        exit;
    }
}

// Fetch all messages
$messages = $db->query("SELECT * FROM contact_messages ORDER BY id DESC")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Customer Inquiries</h2>
        <p class="text-muted mb-0">Messages submitted through the storefront contact form</p>
    </div>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Message successfully updated!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm p-4 bg-white rounded">
    <?php if (empty($messages)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
            <h5>Your Inbox is Empty</h5>
            <p>No contact messages have been submitted yet.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Status</th>
                        <th>Sender Name</th>
                        <th>Email / Phone</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $msg): ?>
                        <tr class="<?= $msg['is_read'] ? '' : 'table-light fw-bold' ?>">
                            <td>
                                <?php if ($msg['is_read']): ?>
                                    <span class="badge bg-secondary">Read</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">New</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($msg['name']) ?></td>
                            <td>
                                <div><?= htmlspecialchars($msg['email']) ?></div>
                                <?php if (!empty($msg['phone'])): ?>
                                    <small class="text-muted"><?= htmlspecialchars($msg['phone']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($msg['subject']) ?></td>
                            <td style="max-width: 300px;">
                                <div class="text-truncate" title="<?= htmlspecialchars($msg['message']) ?>">
                                    <?= htmlspecialchars($msg['message']) ?>
                                </div>
                            </td>
                            <td><small class="text-muted"><?= date('M j, Y', strtotime($msg['created_at'])) ?></small></td>
                            <td class="text-end">
                                <?php if (!$msg['is_read']): ?>
                                    <a href="messages.php?action=mark_read&id=<?= $msg['id'] ?>" class="btn btn-sm btn-outline-success me-1" title="Mark as Read">
                                        <i class="fas fa-check"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="messages.php?action=delete&id=<?= $msg['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this message?');" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
