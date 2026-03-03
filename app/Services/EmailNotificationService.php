<?php

namespace App\Services;

use App\Mail\WalletCreditMail;
use App\Mail\WalletDeductionMail;
use App\Mail\DocumentNotificationMail;
use App\Mail\WelcomeVerifiedUserWithCreditMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailNotificationService
{
    /**
     * Send wallet credit email notification
     * Uses user's existing WalletCreditMail class
     * 
     * @param string $email User email address
     * @param array $data Email data including user, amount, balance info
     * @return bool
     */
    public function sendWalletCreditEmail($email, $data = [])
    {
        try {
            if (!isset($data['user']) || !isset($data['amount'])) {
                Log::warning('EmailNotificationService: Missing required data for wallet credit email');
                return false;
            }

            $user = $data['user'];
            $amount = $data['amount'];
            $type = $data['type'] ?? 'earning';
            $balanceBefore = $data['balance_before'] ?? null;
            $balanceAfter = $data['balance_after'] ?? null;

            // Use the user's existing WalletCreditMail class
            Mail::to($email)->send(new WalletCreditMail(
                $user,
                $amount,
                $type,
                $balanceBefore,
                $balanceAfter
            ));

            Log::info('Wallet credit email sent successfully to: ' . $email);
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to send wallet credit email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send wallet deduction email notification
     * Uses user's existing WalletDeductionMail class
     * 
     * @param string $email User email address
     * @param array $data Email data including user, amount, balance info
     * @return bool
     */
    public function sendWalletDeductionEmail($email, $data = [])
    {
        try {
            if (!isset($data['user']) || !isset($data['amount'])) {
                Log::warning('EmailNotificationService: Missing required data for wallet deduction email');
                return false;
            }

            $user = $data['user'];
            $amount = $data['amount'];
            $balanceBefore = $data['balance_before'] ?? null;
            $balanceAfter = $data['balance_after'] ?? null;

            // Use the user's existing WalletDeductionMail class
            Mail::to($email)->send(new WalletDeductionMail(
                $user,
                $amount,
                $balanceBefore,
                $balanceAfter
            ));

            Log::info('Wallet deduction email sent successfully to: ' . $email);
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to send wallet deduction email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send read operation email notification
     * Uses WalletDeductionMail for read operations
     * 
     * @param string $email User email address
     * @param array $data Email data including user, resource info, deduction amount
     * @return bool
     */
    public function sendReadOperationEmail($email, $data = [])
    {
        try {
            if (!isset($data['user']) || !isset($data['amount'])) {
                Log::warning('EmailNotificationService: Missing required data for read operation email');
                return false;
            }

            $user = $data['user'];
            $amount = $data['amount'];
            $balanceBefore = $data['balance_before'] ?? null;
            $balanceAfter = $data['balance_after'] ?? null;

            // Use WalletDeductionMail for read operations (same as other deductions)
            Mail::to($email)->send(new WalletDeductionMail(
                $user,
                $amount,
                $balanceBefore,
                $balanceAfter
            ));

            Log::info('Read operation email sent successfully to: ' . $email);
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to send read operation email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send download operation email notification
     * Uses WalletDeductionMail for download operations
     * 
     * @param string $email User email address
     * @param array $data Email data including user, resource info, deduction amount
     * @return bool
     */
    public function sendDownloadOperationEmail($email, $data = [])
    {
        try {
            if (!isset($data['user']) || !isset($data['amount'])) {
                Log::warning('EmailNotificationService: Missing required data for download operation email');
                return false;
            }

            $user = $data['user'];
            $amount = $data['amount'];
            $balanceBefore = $data['balance_before'] ?? null;
            $balanceAfter = $data['balance_after'] ?? null;

            // Use WalletDeductionMail for download operations (same as other deductions)
            Mail::to($email)->send(new WalletDeductionMail(
                $user,
                $amount,
                $balanceBefore,
                $balanceAfter
            ));

            Log::info('Download operation email sent successfully to: ' . $email);
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to send download operation email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send document notification email
     * Uses user's existing DocumentNotificationMail class
     * 
     * @param string $email User email address
     * @param string $subject Email subject
     * @param array $data Email data
     * @return bool
     */
    public function sendDocumentNotificationEmail($email, $subject, $data = [])
    {
        try {
            if (!isset($data['user'])) {
                Log::warning('EmailNotificationService: Missing user data for document notification email');
                return false;
            }

            $user = $data['user'];

            // Use the user's existing DocumentNotificationMail class
            Mail::to($email)->send(new DocumentNotificationMail(
                $user,
                $subject,
                'general',
                $data
            ));

            Log::info('Document notification email sent successfully to: ' . $email);
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to send document notification email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send welcome email with credit for verified user
     * Uses user's existing WelcomeVerifiedUserWithCreditMail class
     * 
     * @param string $email User email address
     * @param array $data Email data including user and credit amount
     * @return bool
     */
    public function sendWelcomeVerifiedUserWithCreditEmail($email, $data = [])
    {
        try {
            if (!isset($data['user'])) {
                Log::warning('EmailNotificationService: Missing user data for welcome verified user email');
                return false;
            }

            $user = $data['user'];
            $creditAmount = $data['credit_amount'] ?? null;

            // Use the user's existing WelcomeVerifiedUserWithCreditMail class
            Mail::to($email)->send(new WelcomeVerifiedUserWithCreditMail(
                $user,
                $creditAmount
            ));

            Log::info('Welcome verified user email sent successfully to: ' . $email);
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to send welcome verified user email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generic method to send email with proper error handling
     * 
     * @param string $email User email address
     * @param string $method Method to call (sendWalletCreditEmail, sendWalletDeductionEmail, etc.)
     * @param array $data Email data
     * @return bool
     */
    public function sendEmail($email, $method, $data = [])
    {
        try {
            if (!method_exists($this, $method)) {
                Log::warning('EmailNotificationService: Method ' . $method . ' does not exist');
                return false;
            }

            return $this->$method($email, $data);
            
        } catch (\Exception $e) {
            Log::error('Failed to send email via ' . $method . ': ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Test email functionality
     * 
     * @param string $email Test email address
     * @return bool
     */
    public function testEmail($email)
    {
        try {
            Mail::to($email)->send(new DocumentNotificationMail(
                auth()->user() ?? null,
                'Test Email - Email Notification Service Working',
                'test',
                ['message' => 'This is a test email to verify the email notification service is working correctly.']
            ));

            Log::info('Test email sent successfully to: ' . $email);
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to send test email: ' . $e->getMessage());
            return false;
        }
    }
}