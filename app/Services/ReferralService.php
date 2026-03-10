<?php

namespace App\Services;

use App\Models\User;
use App\Modules\Wallet\Models\SubscriptionWallet;
use App\Modules\Wallet\Http\Traits\WalletTrait;
use App\Modules\Setting\Models\Setting;
use App\Mail\ReferralBonusMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReferralService
{
    use WalletTrait;

    /**
     * Credit amount for referral (100 credits)
     */
    const REFERRAL_BONUS_AMOUNT = 100;

    /**
     * Process referral when a new user registers
     * 
     * @param User $user The newly registered user
     * @param string|null $referralCode The referral code used
     * @return bool
     */
    public function processReferral(User $user, ?string $referralCode = null)
    {
        try {
            // If no referral code, nothing to process
            if (!$referralCode) {
                Log::info('ReferralService: No referral code provided', ['user_id' => $user->id]);
                return false;
            }

            Log::info('ReferralService: Processing referral', [
                'user_id' => $user->id,
                'referral_code' => $referralCode
            ]);

            // Find the referrer by referral code
            $referrer = User::where('referral_code', $referralCode)->first();

            if (!$referrer) {
                Log::warning('ReferralService: Invalid referral code', [
                    'user_id' => $user->id,
                    'referral_code' => $referralCode
                ]);
                return false;
            }

            // Don't allow self-referral
            if ($referrer->id === $user->id) {
                Log::warning('ReferralService: Self-referral detected', [
                    'user_id' => $user->id
                ]);
                return false;
            }

            // Check if referrer is verified (only verified users can earn referral bonuses)
            if (!$referrer->email_verified_at) {
                Log::warning('ReferralService: Referrer email not verified', [
                    'referrer_id' => $referrer->id
                ]);
                return false;
            }

            // Check if this referral already exists
            if ($this->referralExists($referrer->id, $user->id)) {
                Log::warning('ReferralService: Referral already exists', [
                    'referrer_id' => $referrer->id,
                    'referred_id' => $user->id
                ]);
                return false;
            }

            // Save the referral record
            $this->createReferralRecord($referrer->id, $user->id);

            // Credit the referrer
            $this->creditReferrer($referrer);

            Log::info('ReferralService: Referral processed successfully', [
                'referrer_id' => $referrer->id,
                'referred_id' => $user->id,
                'bonus_amount' => self::REFERRAL_BONUS_AMOUNT
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('ReferralService: Exception occurred', [
                'user_id' => $user->id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Credit the referrer with referral bonus
     * 
     * @param User $referrer
     * @return bool
     */
    public function creditReferrer(User $referrer)
    {
        try {
            // Get or create subscription wallet
            $wallet = $referrer->SubscriptionWallet;
            
            if (!$wallet) {
                Log::warning('ReferralService: No subscription wallet found for referrer', [
                    'referrer_id' => $referrer->id
                ]);
                return false;
            }

            $balanceBefore = $wallet->ranc ?? 0;

            // Credit using the same method as AutoCreditService
            self::creditSubscriptionWallet(self::REFERRAL_BONUS_AMOUNT, 'referral_bonus', $referrer->id);

            // Get new balance
            $wallet->refresh();
            $balanceAfter = $wallet->ranc ?? 0;

            Log::info('ReferralService: Referrer credited successfully', [
                'referrer_id' => $referrer->id,
                'amount' => self::REFERRAL_BONUS_AMOUNT,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter
            ]);

            // Send email notification to referrer
            $this->sendReferralBonusEmail($referrer, self::REFERRAL_BONUS_AMOUNT);

            return true;

        } catch (\Exception $e) {
            Log::error('ReferralService: Failed to credit referrer', [
                'referrer_id' => $referrer->id,
                'error_message' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Check if referral already exists
     * 
     * @param int $referrerId
     * @param int $referredId
     * @return bool
     */
    protected function referralExists(int $referrerId, int $referredId): bool
    {
        return DB::table('referrals')
            ->where('referrer_id', $referrerId)
            ->where('referred_id', $referredId)
            ->exists();
    }

    /**
     * Create referral record
     * 
     * @param int $referrerId
     * @param int $referredId
     * @return bool
     */
    protected function createReferralRecord(int $referrerId, int $referredId): bool
    {
        try {
            DB::table('referrals')->insert([
                'referrer_id' => $referrerId,
                'referred_id' => $referredId,
                'status' => 'completed',
                'bonus_amount' => self::REFERRAL_BONUS_AMOUNT,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('ReferralService: Failed to create referral record', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send referral bonus email to referrer
     * 
     * @param User $referrer
     * @param float $amount
     */
    protected function sendReferralBonusEmail(User $referrer, float $amount)
    {
        try {
            Mail::to($referrer->email)->send(new ReferralBonusMail($referrer, $amount));
            Log::info('ReferralService: Referral bonus email sent', [
                'referrer_id' => $referrer->id,
                'amount' => $amount
            ]);
        } catch (\Exception $e) {
            Log::error('ReferralService: Failed to send referral bonus email', [
                'referrer_id' => $referrer->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Generate unique referral code for user
     * 
     * @param User $user
     * @return string
     */
    public function generateReferralCode(User $user): string
    {
        return strtoupper(substr($user->first_name, 0, 3) . substr($user->last_name, 0, 3) . $user->id . rand(100, 999));
    }
}