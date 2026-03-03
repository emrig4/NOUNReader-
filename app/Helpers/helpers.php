<?php

if( !function_exists('theme_asset') )
{
    function theme_asset($path, $secure = null)
    {
        $activeTheme = config('themes')['active'];
        $siteUrl = URL::to('');
        return  $siteUrl . '/themes/' . $activeTheme . '/' . $path;
    }
}

if(! function_exists('translate')){
	function translate($string){
		$tr = new Stichoza\GoogleTranslate\GoogleTranslate();
		//$tr->setSource('en'); // Translate from English
		$tr->setSource(); // Detect language automatically
		$tr->setTarget('ka'); // Translate to Georgian
		return $tr->translate($string);
	}
}


if(! function_exists('ranc_equivalent')){
	function ranc_equivalent($fiat_amount, $currency){

		$ngn_rate = setting('ngn_rate') ?? App\Modules\Payment\Models\Currency::whereCode('NGN')->first()->rate;
		$usd_rate = setting('usd_rate') ?? App\Modules\Payment\Models\Currency::whereCode('USD')->first()->rate;
		$ranc_rate = setting('ranc_rate') ??  App\Modules\Payment\Models\Currency::whereCode('RANC')->first()->rate;

		if($currency == 'NGN'){
			return ($fiat_amount/ $ngn_rate ) * $ranc_rate ;
		}else{
			return ($fiat_amount/ $usd_rate ) * $ranc_rate ;
		}
		
	}
}


if(! function_exists('currency_exchange')){
	function currency_exchange($amount, $from_currency = null,  $to_currency = null){

		$ngn_rate = setting('ngn_rate') ??  App\Modules\Payment\Models\Currency::whereCode('NGN')->first()->rate;
		$usd_rate = setting('usd_rate') ?? App\Modules\Payment\Models\Currency::whereCode('USD')->first()->rate;
		$ranc_rate = setting('ranc_rate') ?? App\Modules\Payment\Models\Currency::whereCode('RANC')->first()->rate;


		if($from_currency == 'NGN' && $to_currency == 'USD'){

			$result =  ($amount / $ngn_rate ) ;
			return (float) number_format( $result, 2, '.', '' );
			
		}

		if($from_currency == 'USD' && $to_currency == 'NGN'){
			return ($amount * $ngn_rate ) ;
		}

		if($from_currency == 'RANC' && $to_currency == 'USD'){
			$result = ($amount/$ranc_rate ) ;
			return (float) number_format( $result, 2, '.', '' );
		}

		if($from_currency == 'USD' && $to_currency == 'RANC'){
			return ($amount * $ranc_rate ) ;
		}

		if($from_currency == 'NGN' && $to_currency == 'RANC'){
			return ( ($amount/$ngn_rate) * $ranc_rate ) ;
		}

		if($from_currency == 'RANC' && $to_currency == 'NGN'){
			return ( ($amount/$ranc_rate) * $ngn_rate ) ;
		}

		return $amount ;
		
	}
}


if(! function_exists('fiat_equivalent')){
	function fiat_equivalent($ranc_amount, $currency){

		$ngn_rate = setting('ngn_rate') ?? App\Modules\Payment\Models\Currency::whereCode('NGN')->first()->rate;
		$usd_rate =  setting('usd_rate') ?? App\Modules\Payment\Models\Currency::whereCode('USD')->first()->rate;
		$ranc_rate = setting('ranc_rate') ?? App\Modules\Payment\Models\Currency::whereCode('RANC')->first()->rate;

		 if($currency === 'NGN') {
            return  floor(  ( $ranc_amount/100 ) * $ngn_rate  );  //convert cent to dollar then to naira
        }else{
            return (float) number_format( $ranc_amount / $ranc_rate, 2, '.', '' ); //convert cent to dollar
        }	
	}
}

/**
 * Email Notification Helper Functions
 * Integration with notify() function to trigger email notifications
 */

if(! function_exists('send_wallet_credit_email')) {
    /**
     * Send wallet credit email notification
     * 
     * @param string $email User email address
     * @param array $data Email data including user, amount, balance info
     * @return bool
     */
    function send_wallet_credit_email($email, $data = []) {
        try {
            $emailService = new App\Services\EmailNotificationService();
            $emailService->sendWalletCreditEmail($email, $data);
            return true;
        } catch (\Exception $e) {
            Log::error('Wallet credit email failed: ' . $e->getMessage());
            return false;
        }
    }
}

if(! function_exists('send_wallet_deduction_email')) {
    /**
     * Send wallet deduction email notification
     * 
     * @param string $email User email address
     * @param array $data Email data including user, amount, balance info
     * @return bool
     */
    function send_wallet_deduction_email($email, $data = []) {
        try {
            $emailService = new App\Services\EmailNotificationService();
            $emailService->sendWalletDeductionEmail($email, $data);
            return true;
        } catch (\Exception $e) {
            Log::error('Wallet deduction email failed: ' . $e->getMessage());
            return false;
        }
    }
}

if(! function_exists('send_read_operation_email')) {
    /**
     * Send read operation email notification
     * 
     * @param string $email User email address
     * @param array $data Email data including user, resource info, deduction amount
     * @return bool
     */
    function send_read_operation_email($email, $data = []) {
        try {
            $emailService = new App\Services\EmailNotificationService();
            $emailService->sendReadOperationEmail($email, $data);
            return true;
        } catch (\Exception $e) {
            Log::error('Read operation email failed: ' . $e->getMessage());
            return false;
        }
    }
}

if(! function_exists('send_download_operation_email')) {
    /**
     * Send download operation email notification
     * 
     * @param string $email User email address
     * @param array $data Email data including user, resource info, deduction amount
     * @return bool
     */
    function send_download_operation_email($email, $data = []) {
        try {
            $emailService = new App\Services\EmailNotificationService();
            $emailService->sendDownloadOperationEmail($email, $data);
            return true;
        } catch (\Exception $e) {
            Log::error('Download operation email failed: ' . $e->getMessage());
            return false;
        }
    }
}

if(! function_exists('send_document_notification_email')) {
    /**
     * Send document notification email
     * 
     * @param string $email User email address
     * @param string $subject Email subject
     * @param array $data Email data
     * @return bool
     */
    function send_document_notification_email($email, $subject, $data = []) {
        try {
            $emailService = new App\Services\EmailNotificationService();
            $emailService->sendDocumentNotificationEmail($email, $subject, $data);
            return true;
        } catch (\Exception $e) {
            Log::error('Document notification email failed: ' . $e->getMessage());
            return false;
        }
    }
}

if(! function_exists('send_welcome_verified_user_email')) {
    /**
     * Send welcome email with credit for verified user
     * 
     * @param string $email User email address
     * @param array $data Email data including user and credit amount
     * @return bool
     */
    function send_welcome_verified_user_email($email, $data = []) {
        try {
            $emailService = new App\Services\EmailNotificationService();
            $emailService->sendWelcomeVerifiedUserWithCreditEmail($email, $data);
            return true;
        } catch (\Exception $e) {
            Log::error('Welcome verified user email failed: ' . $e->getMessage());
            return false;
        }
    }
}

if(! function_exists('trigger_email_notification')) {
    /**
     * Generic function to trigger email notification with notify() integration
     * 
     * @param string $type Notification type (wallet_credit, wallet_deduction, read_operation, download_operation)
     * @param string $email User email address
     * @param array $data Email data
     * @param string $message Success message for notify()
     * @param string $type_notify Notify type (success, error, warning, info)
     * @return array Result with success status and message
     */
    function trigger_email_notification($type, $email, $data = [], $message = 'Email notification sent successfully', $type_notify = 'success') {
        $result = [
            'success' => false,
            'message' => $message,
            'notify_type' => $type_notify
        ];

        try {
            switch ($type) {
                case 'wallet_credit':
                    $result['success'] = send_wallet_credit_email($email, $data);
                    break;
                case 'wallet_deduction':
                    $result['success'] = send_wallet_deduction_email($email, $data);
                    break;
                case 'read_operation':
                    $result['success'] = send_read_operation_email($email, $data);
                    break;
                case 'download_operation':
                    $result['success'] = send_download_operation_email($email, $data);
                    break;
                case 'document_notification':
                    $result['success'] = send_document_notification_email($email, $data['subject'] ?? 'Document Notification', $data);
                    break;
                case 'welcome_verified_user':
                    $result['success'] = send_welcome_verified_user_email($email, $data);
                    break;
                default:
                    $result['success'] = false;
                    $result['message'] = 'Unknown notification type';
                    $result['notify_type'] = 'error';
                    break;
            }

            if ($result['success']) {
                // Trigger notify() function with success message
                if (function_exists('notify')) {
                    notify($result['message'], $type_notify);
                }
            } else {
                // Update message for failure and trigger notify with error
                $result['message'] = 'Failed to send email notification';
                $result['notify_type'] = 'error';
                if (function_exists('notify')) {
                    notify($result['message'], 'error');
                }
            }

        } catch (\Exception $e) {
            Log::error('Email notification trigger failed: ' . $e->getMessage());
            $result['success'] = false;
            $result['message'] = 'Email notification failed: ' . $e->getMessage();
            $result['notify_type'] = 'error';
            
            if (function_exists('notify')) {
                notify($result['message'], 'error');
            }
        }

        return $result;
    }
}

if(! function_exists('format_currency_display')) {
    /**
     * Helper function to format currency display for email templates
     * 
     * @param float $amount Amount to format
     * @param string $currency Currency code (RNC, USD, NGN)
     * @return string Formatted currency string
     */
    function format_currency_display($amount, $currency = 'RNC') {
        switch ($currency) {
            case 'RNC':
                return number_format($amount / 100, 2) . ' RNC';
            case 'USD':
                return '$' . number_format($amount, 2);
            case 'NGN':
                return '₦' . number_format($amount, 2);
            default:
                return number_format($amount, 2) . ' ' . $currency;
        }
    }
}

if(! function_exists('get_balance_change_description')) {
    /**
     * Helper function to get descriptive text for balance changes
     * 
     * @param string $transactionType Type of transaction
     * @param string $operation Operation performed (credit/debit)
     * @param string $description Additional description
     * @return string Descriptive text
     */
    function get_balance_change_description($transactionType, $operation, $description = '') {
        $baseDescriptions = [
            'wallet_credit' => 'Wallet credited',
            'wallet_debit' => 'Wallet debited',
            'subscription_credit' => 'Subscription wallet credited',
            'subscription_debit' => 'Subscription wallet debited'
        ];

        $operationText = $operation === 'credit' ? 'credited' : 'debited';
        
        if (isset($baseDescriptions[$transactionType])) {
            return $baseDescriptions[$transactionType] . ($description ? ' - ' . $description : '');
        }

        return 'Wallet ' . $operationText . ($description ? ' - ' . $description : '');
    }

	
}