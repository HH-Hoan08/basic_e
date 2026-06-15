<?php
$pageTitle = 'Gửi thông báo cho người dùng';
?>
<h2 class="mb-4"><?= $pageTitle ?></h2>

<?php if (isset($message)): ?>
    <div class="alert alert-<?= htmlspecialchars($message['type']) ?>"><?= htmlspecialchars($message['text']) ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= BASE_URL ?>index.php?page=admin&action=send_email" method="POST">
            <div class="mb-3">
                <label for="recipient_type" class="form-label">Gửi đến <span class="text-danger">*</span></label>
                <select class="form-select" id="recipient_type" name="recipient_type" required onchange="toggleSpecificUserInput()">
                    <option value="all">Tất cả người dùng</option>
                    <option value="member">Hạng Member</option>
                    <option value="silver">Hạng Bạc (Silver)</option>
                    <option value="gold">Hạng Vàng (Gold)</option>
                    <option value="diamond">Hạng Kim Cương (Diamond)</option>
                    <option value="specific">Người dùng cụ thể</option>
                </select>
            </div>

            <div class="mb-3 d-none" id="specific_user_group">
                <label for="specific_user" class="form-label">Tên người dùng cụ thể</label>
                <input type="text" class="form-control" id="specific_user" name="specific_user" placeholder="Nhập username...">
            </div>

            <div class="mb-3">
                <label for="subject" class="form-label">Chủ đề <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>

            <div class="mb-3">
                <label for="body" class="form-label">Nội dung thông báo <span class="text-danger">*</span></label>
                <textarea class="form-control" id="body" name="body" rows="8" required></textarea>
            </div>

            <hr>
            <div class="d-flex justify-content-end">
                <button type="submit" name="send_email_submit" class="btn btn-success"><i class="fas fa-paper-plane me-2"></i>Gửi thông báo</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleSpecificUserInput() {
    const recipientType = document.getElementById('recipient_type').value;
    const specificUserGroup = document.getElementById('specific_user_group');
    specificUserGroup.classList.toggle('d-none', recipientType !== 'specific');
}
</script>