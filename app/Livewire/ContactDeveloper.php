<?php

namespace App\Livewire;

use Livewire\Component;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class ContactDeveloper extends Component
{
    public $name, $email, $message;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'nullable|string|max:5000',
    ];

    public function submit()
    {
        $validatedData = $this->validate();

        try {
            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'dnexus204@gmail.com';
            $mail->Password = 'robz skwu mmcr fjtv'; // Use environment variable for Gmail app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Sender and recipient
            $mail->setFrom($this->email, $this->name);
            $mail->addAddress('dnexus204@gmail.com');

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Contact Form Submission';
            $mail->Body = "
                <html>
                    <head>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                color: #333;
                            }
                            .email-content {
                                padding: 20px;
                                background-color: #f9f9f9;
                                border-radius: 5px;
                            }
                            .email-header {
                                text-align: center;
                                margin-bottom: 20px;
                            }
                            .email-header img {
                                max-width: 50%;
                                height: 50%;
                            }
                        </style>
                    </head>
                    <body>
                        <div class='email-header'>
                            <img src='https://img.freepik.com/free-vector/isometric-web-hosting-support-composition-with-tech-support-online-clients_1284-54457.jpg?t=st=1737010902~exp=1737014502~hmac=bb5b800c7f7d1d0bd9ca278b20f7d33ac59d099a0c8f9b93de24f15072553898&w=740' alt='Header Image'>
                        </div>
                        <div class='email-content'>
                            <h2>New Contact Form Submission</h2>
                            <p><strong>Name:</strong> " . e($this->name) . "</p>
                            <p><strong>Email:</strong> " . e($this->email) . "</p>
                            <p><strong>Message:</strong></p>
                            <p>" . nl2br(e($this->message)) . "</p>
                        </div>
                    </body>
                </html>
            ";

            // Send the email
            $mail->send();

            // Success message
            session()->flash('success', 'Your message has been sent successfully!');

            // Reset the form fields
            $this->reset(['name', 'email', 'message']);

        } catch (Exception $e) {
            // Log the error and set the error message
            Log::error('Email Error: ' . $e->getMessage());
            session()->flash('error', 'Failed to send your message. Please try again later.');
        }
    }

    public function render()
    {
        return view('livewire.contact-developer');
    }
}
