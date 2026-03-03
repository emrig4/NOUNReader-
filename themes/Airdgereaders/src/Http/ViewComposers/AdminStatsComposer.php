<?php

namespace Themes\Airdgereaders\Http\ViewComposers;

use Illuminate\Support\Facades\DB;
use App\Modules\Resource\Models\ResourceType;
use App\Modules\Resource\Models\ResourceField;
use App\Modules\Resource\Models\ResourceAuthor;
use App\Modules\Resource\Models\ResourceSubField;
use App\Modules\Resource\Models\Resource;
use App\Modules\File\Models\TemporaryFile;
use App\Modules\Payment\Models\Currency;
use App\Modules\Wallet\Models\CreditWallet;
use App\Modules\Wallet\Models\CreditWalletHolding;
use App\Modules\Wallet\Models\CreditWalletTransaction;


use Digikraaft\Paystack\Subscription;

use App\Models\User;






class AdminStatsComposer
{
    /**
     * Bind data to the view.
     *
     * @param \Illuminate\View\View $view
     * @return void
     */
    // either create or compose
    public function create($view)
    {
        $view->with([
            'resourceCounts' => $this->getResourceCounts(),
            'subscriptionCount' => $this->getSubscriptionCount(),
            'usersCount' => $this->getUsersCount(),
            'creditWalletVolume' => $this->getCreditWalletVolume(),


            'creditWalletVolume' => $this->getCreditWalletVolume(),

            'walletWithdrawalVolume' => $this->getWalletWithdrawalVolume(),
            'revenueAmount' => $this->getRevenueAmount(),
            'withdrawalCount' => $this->getPendingWithdrawalCount(),

        ]);
    }

    // airon
    private function getResourceCounts()
    {
        return Resource::all()->count();
    }

    private function getSubscriptionCount()
    { 
        return count( (array)Subscription::list() );
    }


    private function getCreditWalletVolume()
    {
      return CreditWallet::all()->sum('ranc');
    } 

    private function getWalletWithdrawalVolume()
    {
      return CreditWalletHolding::whereStatus('completed')->sum('ranc');
    }

    private function getPendingWithdrawalCount()
    {
      return CreditWalletTransaction::whereStatus('pending')->sum('ranc');
    }


    private function getUsersCount()
    {
      return User::all()->count();
    }

    private function getRevenueAmount()
    {
      return CreditWallet::whereUserId(setting('readprojecttopics_user_id'))->sum('ranc');
    }
    

}

