<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\WalletCreditMail;
use App\Mail\WalletDeductionMail;
use App\Mail\DocumentNotificationMail;
use App\Mail\WelcomeVerifiedUserWithCreditMail;

/**
 * Service to integrate notify() function with email notifications
 * This service automatically triggers email notifications based on notify() calls
 */
class NotifyEmailIntegrationService
{
    private static $notificationQueue = [];
    private static $currentUser = null;
    private static $currentResource = null;
    private static $currentAmount = null;

    /**
     * Initialize the notification context
     */
    public static function initContext($user = null, $resource = null, $amount = null)
    {
        self::$currentUser = $user;
        self::$currentResource = $resource;
        self::$currentAmount = $amount;
    }

    /**
     * Handle notify() success calls and trigger appropriate email notifications
     */
    public static function handleNotifySuccess($message, $title = null)
    {
        try {
            if (!self::$currentUser) {
                self::$currentUser = auth()->user();
            }

            if (!self::$currentUser) {
                return; // No user context available
            }

            // Parse the message to determine notification type
            $notificationType = self::parseNotificationType($message);

            switch ($notificationType) {
                case 'wallet_credit':
                    self::sendWalletCreditEmail();
                    break;
                    
                case 'wallet_deduction':
                    self::sendWalletDeductionEmail();
                    break;
                    
                case 'read_operation':
                    self::sendReadOperationEmail();
                    break;
                    
                case 'download_operation':
                    self::sendDownloadOperationEmail();
                    break;
                    
                case 'document_submitted':
                    self::sendDocumentSubmittedEmail();
                    break;
                    
                case 'welcome_with_credit':
                    self::sendWelcomeWithCreditEmail();
                    break;
            }

        } catch (\Exception $e) {
            Log::error('Failed to handle notify success email integration', [
                'user_id' => self::$currentUser ? self::$currentUser->id : null,
                'message' => $message,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle notify() error calls (no email needed for errors)
     */
    public static function handleNotifyError($message, $title = null)
    {
        // Errors typically don't require email notifications
        // But we could log them for monitoring
        Log::info('Notify error called', [
            'user_id' => self::$currentUser ? self::$currentUser->id : null,
            'message' => $message
        ]);
    }

    /**
     * Parse notification type from message content
     */
    private static function parseNotificationType($message)
    {
        $message = strtolower($message);

        if (strpos($message, 'credits') !== false && strpos($message, 'charged') !== false) {
            return strpos($message, 'read') !== false ? 'read_operation' : 'wallet_deduction';
        }

        if (strpos($message, 'credits') !== false && (strpos($message, 'added') !== false || strpos($message, 'credited') !== false)) {
            return 'wallet_credit';
        }

        if (strpos($message, 'withdrawal') !== false) {
            return 'wallet_deduction';
        }

        if (strpos($message, 'submitted') !== false && strpos($message, 'review') !== false) {
            return 'document_submitted';
        }

        if (strpos($message, 'welcome') !== false && strpos($message, 'verified') !== false) {
            return 'welcome_with_credit';
        }

        if (strpos($message, 'download') !== false) {
            return 'download_operation';
        }

        return null;
    }

    /**
     * Send wallet credit email
     */
    private static function sendWalletCreditEmail()
    {
        if (!self::$currentUser || !self::$currentAmount) {
            return;
        }

        $balanceBefore = self::$currentUser->CreditWallet ? 
            self::$currentUser->CreditWallet->ranc - self::$currentAmount : 0;
        $balanceAfter = self::$currentUser->CreditWallet ? 
            self::$currentUser->CreditWallet->ranc : self::$currentAmount;

        Mail::to(self::$currentUser->email)->send(new WalletCreditMail(
            self::$currentUser,
            self::$currentAmount,
            'earning',
            $balanceBefore,
            $balanceAfter
        ));

        Log::info('Auto-triggered wallet credit email via notify()', [
            'user_id' => self::$currentUser->id,
            'amount' => self::$currentAmount
        ]);
    }

    /**
     * Send wallet deduction email
     */
    private static function sendWalletDeductionEmail()
    {
        if (!self::$currentUser || !self::$currentAmount) {
            return;
        }

        $balanceBefore = self::$currentUser->SubscriptionWallet ? 
            self::$currentUser->SubscriptionWallet->ranc + self::$currentAmount : self::$currentAmount;
        $balanceAfter = self::$currentUser->SubscriptionWallet ? 
            self::$currentUser->SubscriptionWallet->ranc : 0;

        Mail::to(self::$currentUser->email)->send(new WalletDeductionMail(
            self::$currentUser,
            self::$currentAmount,
            $balanceBefore,
            $balanceAfter
        ));

        Log::info('Auto-triggered wallet deduction email via notify()', [
            'user_id' => self::$currentUser->id,
            'amount' => self::$currentAmount
        ]);
    }

    /**
     * Send read operation email
     */
    private static function sendReadOperationEmail()
    {
        if (!self::$currentUser || !self::$currentResource || !self::$currentAmount) {
            return;
        }

        $message = "You have successfully read '" . self::$currentResource->title . "'. " . 
                  self::$currentAmount . " credits were charged to your account.";
        
        $document = new \App\Models\Document();
        $document->title = self::$currentResource->title;
        $document->id = self::$currentResource->id;

        Mail::to(self::$currentUser->email)->send(new DocumentNotificationMail(
            self::$currentUser,
            'read_operation',
            $document,
            $message
        ));

        Log::info('Auto-triggered read operation email via notify()', [
            'user_id' => self::$currentUser->id,
            'resource_title' => self::$currentResource->title,
            'amount' => self::$currentAmount
        ]);
    }

    /**
     * Send download operation email
     */
    private static function sendDownloadOperationEmail()
    {
        if (!self::$currentUser || !self::$currentResource || !self::$currentAmount) {
            return;
        }

        $message = "You have successfully downloaded '" . self::$currentResource->title . "'. " . 
                  self::$currentAmount . " credits were charged to your account.";
        
        $document = new \App\Models\Document();
        $document->title = self::$currentResource->title;
        $document->id = self::$currentResource->id;

        Mail::to(self::$currentUser->email)->send(new DocumentNotificationMail(
            self::$currentUser,
            'download_operation',
            $document,
            $message
        ));

        Log::info('Auto-triggered download operation email via notify()', [
            'user_id' => self::$currentUser->id,
            'resource_title' => self::$currentResource->title,
            'amount' => self::$currentAmount
        ]);
    }

    /**
     * Send document submitted email
     */
    private static function sendDocumentSubmittedEmail()
    {
        if (!self::$currentUser || !self::$currentResource) {
            return;
        }

        Mail::to(self::$currentUser->email)->send(new DocumentNotificationMail(
            self::$currentUser,
            'submitted',
            self::$currentResource,
            'Your document has been submitted and is awaiting review.'
        ));

        Log::info('Auto-triggered document submitted email via notify()', [
            'user_id' => self::$currentUser->id,
            'document_title' => self::$currentResource->title
        ]);
    }

    /**
     * Send welcome with credit email
     */
    private static function sendWelcomeWithCreditEmail()
    {
        if (!self::$currentUser) {
            return;
        }

        $creditAmount = self::$currentAmount ?? 100.00;
        $currentBalance = self::$currentUser->CreditWallet ? 
            self::$currentUser->CreditWallet->ranc : $creditAmount;

        Mail::to(self::$currentUser->email)->send(new WelcomeVerifiedUserWithCreditMail(
            self::$currentUser,
            $creditAmount,
            $currentBalance
        ));

        Log::info('Auto-triggered welcome with credit email via notify()', [
            'user_id' => self::$currentUser->id,
            'credit_amount' => $creditAmount
        ]);
    }

    /**
     * Clear context after processing
     */
    public static function clearContext()
    {
        self::$currentUser = null;
        self::$currentResource = null;
        self::$currentAmount = null;
        self::$notificationQueue = [];
    }
}