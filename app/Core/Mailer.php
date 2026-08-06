<?php
declare(strict_types=1);

namespace ThemeHub\Core;

/**
 * Mailer Class
 * Handles email sending with templates
 */
final class Mailer
{
    private array $config;
    private array $attachments = [];

    public function __construct()
    {
        $this->config = config('mail', []);
    }

    public function to(string $email, string $name = ''): self
    {
        $this->config['to_email'] = $email;
        $this->config['to_name'] = $name;
        return $this;
    }

    public function subject(string $subject): self
    {
        $this->config['subject'] = $subject;
        return $this;
    }

    public function template(string $template, array $data = []): self
    {
        $this->config['template'] = $template;
        $this->config['template_data'] = $data;
        return $this;
    }

    public function body(string $html): self
    {
        $this->config['body'] = $html;
        return $this;
    }

    public function attach(string $file, string $name = ''): self
    {
        $this->attachments[] = ['file' => $file, 'name' => $name ?: basename($file)];
        return $this;
    }

    public function send(): bool
    {
        if (empty($this->config['to_email'])) {
            return false;
        }
        
        $body = $this->config['body'] ?? view('emails/' . ($this->config['template'] ?? 'default'), $this->config['template_data'] ?? [], true);
        
        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . ($this->config['from_name'] ?? config('app.name')) . ' <' . ($this->config['from_email'] ?? config('mail.from_email')) . '>',
            'Reply-To: ' . ($this->config['reply_to'] ?? config('mail.from_email')),
            'X-Mailer: ThemeHub/' . config('app.version', '1.0.0')
        ];
        
        // Add attachments
        foreach ($this->attachments as $attachment) {
            $fileContent = chunk_split(base64_encode(file_get_contents($attachment['file'])));
            $headers[] = 'Content-Type: multipart/mixed; boundary="themehub_boundary"';
            $body = '--themehub_boundary' . "\n";
            $body .= 'Content-Type: text/html; charset=UTF-8' . "\n\n";
            $body .= $body . "\n\n";
            $body .= '--themehub_boundary' . "\n";
            $body .= 'Content-Type: application/octet-stream; name="' . $attachment['name'] . '"' . "\n";
            $body .= 'Content-Transfer-Encoding: base64' . "\n\n";
            $body .= $fileContent . "\n\n";
            $body .= '--themehub_boundary--';
        }
        
        return mail(
            $this->config['to_email'],
            $this->config['subject'] ?? 'No Subject',
            $body,
            implode("\r\n", $headers)
        );
    }
}
