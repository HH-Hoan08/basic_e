<?php
// Import các class của PHPMailer vào không gian tên chung
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Tải autoloader của Composer
require_once __DIR__ . '/../../vendor/autoload.php';

class MailService {
    private PHPMailer $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->configure();
    }

    private function configure() {
        // Cài đặt Server SMTP
        $this->mailer->isSMTP();
        $this->mailer->Host       = 'smtp.gmail.com'; 
        $this->mailer->SMTPAuth   = true;
        
        // THAY ĐỔI CÁC THÔNG TIN DƯỚI ĐÂY BẰNG THÔNG TIN CỦA BẠN
        $this->mailer->Username   = 'phamhuuphu31102005@gmail.com'; 
        // 🚨 CHÚ Ý: Điền mật khẩu ứng dụng MỚI của bạn vào đây. KHÔNG dùng lại mật khẩu cũ đã lộ.
        $this->mailer->Password   = 'cgwu ibcq jmhi bncb'; 
        
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mailer->Port       = 465;
        $this->mailer->CharSet    = 'UTF-8';

        // Người gửi
        $this->mailer->setFrom('no-reply@basicshop.com', 'Basic Shop');
    }

    // Hàm 1: Gửi email đặt lại mật khẩu (OTP)
    public function sendPasswordResetEmail(string $recipientEmail, string $token): bool {
        try {
            $this->mailer->clearAllRecipients(); // Xóa người nhận cũ để tránh gửi nhầm
            $this->mailer->addAddress($recipientEmail);

            // Nội dung email
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Yêu cầu đặt lại mật khẩu cho Basic Shop';
            $this->mailer->Body    = "
                <p>Chào bạn,</p>
                <p>Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>
                <p>Mã OTP của bạn là: <strong>{$token}</strong></p>
                <p>Mã này sẽ hết hạn sau 15 phút.</p>
                <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
                <p>Trân trọng,<br>Đội ngũ Basic Shop</p>
            ";
            $this->mailer->AltBody = "Mã OTP của bạn là: {$token}. Mã sẽ hết hạn sau 15 phút.";

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            // Ghi log lỗi để debug
            error_log("Lỗi Gửi Mail (Reset Password): {$this->mailer->ErrorInfo}");
            return false;
        }
    }

    // Hàm 2 (ĐƯỢC THÊM MỚI): Hàm chung để gửi các Email định dạng HTML (như Hóa đơn)
    public function sendHtmlEmail(string $recipientEmail, string $subject, string $body): bool {
        try {
            $this->mailer->clearAllRecipients(); // Xóa người nhận cũ
            $this->mailer->addAddress($recipientEmail);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;
            
            // Loại bỏ thẻ HTML cho các trình duyệt email không hỗ trợ HTML (văn bản thuần)
            $this->mailer->AltBody = strip_tags(str_replace(['<br>', '<br/>', '</p>'], "\n", $body));

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            // Ghi log lỗi
            error_log("Lỗi Gửi Mail (HTML Email): {$this->mailer->ErrorInfo}");
            return false;
        }
    }
}