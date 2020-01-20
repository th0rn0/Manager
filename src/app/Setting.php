<?php

namespace App;

use DB;
use Storage;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'settings';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'setting',
        'value',
        'default',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'created_at',
        'updated_at'
    );

    /**
     * Get Organization Name
     * @return String
     */
    public static function getOrgName()
    {
        return self::where('setting', 'org_name')->first()->value;
    }

    /**
     * Set Organization Name
     * @param String $name
     */
    public static function setOrgName($name)
    {
        $setting = self::where('setting', 'org_name')->first();
        $setting->value = $name;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Organization Tagline
     * @return String
     */
    public static function getOrgTagline()
    {
        return self::where('setting', 'org_tagline')->first()->value;
    }

    /**
     * Set Organization Tagline
     * @param String $tagline
     */
    public static function setOrgTagline($tagline)
    {
        $setting = self::where('setting', 'org_tagline')->first();
        $setting->value = $tagline;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Organization Logo Path
     * @return String
     */
    public static function getOrgLogo()
    {
        return (self::where('setting', 'org_logo')->first()->value) . '?cb=' . mt_rand(10, 9999);
    }

    /**
     * Set Organization Logo
     * @param Image $logo
     */
    public static function setOrgLogo($logo)
    {
        Storage::delete(self::getOrgLogo());
        $path = str_replace(
            'public/',
            '/storage/',
            Storage::putFileAs(
                'public/images/main',
                $logo,
                'logo_main.png'
            )
        );
        $setting = self::where('setting', 'org_logo')->first();
        $setting->value = $path;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Organization Favicon Path
     * @return String
     */
    public static function getOrgFavicon()
    {
        return (self::where('setting', 'org_favicon')->first()->value) . '?cb=' . mt_rand(10, 9999);
    }

    /**
     * Set Organization Favicon
     * @param Image $favicon
     */
    public static function setOrgFavicon($favicon)
    {
        Storage::delete(self::getOrgFavicon());
        $path = str_replace(
            'public/',
            '/storage/',
            Storage::putFileAs(
                'public/images/main',
                $favicon,
                'favicon.ico'
            )
        );
        $setting = self::where('setting', 'org_favicon')->first();
        $setting->value = $path;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Terms And Conditions
     * @return String
     */
    public static function getPurchaseTermsAndConditions()
    {
        return self::where('setting', 'purchase_terms_and_conditions')->first()->value;
    }

    /**
     * Set Terms And Conditions
     * @param String $text
     */
    public static function setPurchaseTermsAndConditions($text)
    {
        $setting = self::where('setting', 'purchase_terms_and_conditions')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Registration Terms And Conditions
     * @return String
     */
    public static function getRegistrationTermsAndConditions()
    {
        return self::where('setting', 'registration_terms_and_conditions')->first()->value;
    }

    /**
     * Set Registration Terms And Conditions
     * @param String $text
     */
    public static function setRegistrationTermsAndConditions($text)
    {
        $setting = self::where('setting', 'registration_terms_and_conditions')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Discord Link
     * @return String
     */
    public static function getDiscordLink()
    {
        return self::where('setting', 'discord_link')->first()->value;
    }

    /**
     * Set Discord Link
     * @param String $text
     */
    public static function setDiscordLink($text)
    {
        $setting = self::where('setting', 'discord_link')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Discord ID
     * @return String
     */
    public static function getDiscordId()
    {
        return self::where('setting', 'discord_id')->first()->value;
    }

    /**
     * Set Discord ID
     * @param String $text
     */
    public static function setDiscordId($text)
    {
        $setting = self::where('setting', 'discord_id')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Facebook Link
     * @return String
     */
    public static function getFacebookLink()
    {
        return self::where('setting', 'facebook_link')->first()->value;
    }
    
    /**
     * Set Discord Link
     * @param String $text
     */
    public static function setFacebookLink($text)
    {
        $setting = self::where('setting', 'facebook_link')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Steam Link
     * @return String
     */
    public static function getSteamLink()
    {
        return self::where('setting', 'steam_link')->first()->value;
    }

    /**
     * Set Discord Link
     * @param String $text
     */
    public static function setSteamLink($text)
    {
        $setting = self::where('setting', 'steam_link')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Reddit Link
     * @return String
     */
    public static function getRedditLink()
    {
        return self::where('setting', 'reddit_link')->first()->value;
    }

    /**
     * Set Discord Link
     * @param String $text
     */
    public static function setRedditLink($text)
    {
        $setting = self::where('setting', 'reddit_link')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Teamspeak Link
     * @return String
     */
    public static function getTeamspeakLink()
    {
        return self::where('setting', 'teamspeak_link')->first()->value;
    }

    /**
     * Set Discord Link
     * @param String $text
     */
    public static function setTeamspeakLink($text)
    {
        $setting = self::where('setting', 'teamspeak_link')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Participant Count Offset
     * @return Integer
     */
    public static function getParticipantCountOffset()
    {
        return self::where('setting', 'participant_count_offset')->first()->value;
    }

    /**
     * Set Participant Count Offset
     * @param Integer $number
     */
    public static function setParticipantCountOffset($number)
    {
        $setting = self::where('setting', 'participant_count_offset')->first();
        $setting->value = $number;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Lan Count Offset
     * @return Integer
     */
    public static function getEventCountOffset()
    {
        return self::where('setting', 'event_count_offset')->first()->value;
    }

    /**
     * Set Lan Count Offset
     * @param Integer $number
     */
    public static function setEventCountOffset($number)
    {
        $setting = self::where('setting', 'event_count_offset')->first();
        $setting->value = $number;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }


    /**
     * Get Frontpage Alot Tagline
     * @return Integer
     */
    public static function getFrontpageAlotTagline()
    {
        return self::where('setting', 'frontpage_alot_tagline')->first()->value;
    }

    /**
     * Set Frontpage Alot Tagline
     * @param string $text
     */
    public static function setFrontpageAlotTagline($text)
    {
        $setting = self::where('setting', 'frontpage_alot_tagline')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Currency
     * @return String
     */
    public static function getCurrency()
    {
        return self::where('setting', 'currency')->first()->value;
    }

    /**
     * Get Currency Symbol
     * @return String
     */
    public static function getCurrencySymbol()
    {
        $currency = self::where('setting', 'currency')->first()->value;
        switch ($currency) {
            case 'USD':
                $symbol = '$';
                break;
            case 'GBP':
                $symbol = '£';
                break;
            case 'EUR':
                $symbol = '€';
                break;
            default:
                $symbol = '£';
                break;
        }
        return $symbol;
    }

    /**
     * Set Currency
     * @param String $text
     */
    public static function setCurrency($currency)
    {
        $setting = self::where('setting', 'currency')->first();
        $setting->value = $currency;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get About Main
     * @return String
     */
    public static function getAboutMain()
    {
        return self::where('setting', 'about_main')->first()->value;
    }

    /**
     * Set About Main
     * @param String $text
     */
    public static function setAboutMain($text)
    {
        $setting = self::where('setting', 'about_main')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get About Short
     * @return String
     */
    public static function getAboutShort()
    {
        return self::where('setting', 'about_short')->first()->value;
    }

    /**
     * Set About Short
     * @param String $text
     */
    public static function setAboutShort($text)
    {
        $setting = self::where('setting', 'about_short')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get About Our Aim
     * @return String
     */
    public static function getAboutOurAim()
    {
        return self::where('setting', 'about_our_aim')->first()->value;
    }

    /**
     * Set About Our Aim
     * @param String $text
     */
    public static function setAboutOurAim($text)
    {
        $setting = self::where('setting', 'about_our_aim')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get About Who's Who
     * @return String
     */
    public static function getAboutWho()
    {
        return self::where('setting', 'about_who')->first()->value;
    }

    /**
     * Set About Who's Who
     * @param String $text
     */
    public static function setAboutWho($text)
    {
        $setting = self::where('setting', 'about_who')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Facebook Page Access Tokens
     * @return String
     */
    public static function getSocialFacebookPageAccessTokens()
    {
        return unserialize(self::where('setting', 'social_facebook_page_access_token')->first()->value);
    }

    /**
     * Set Facebook Page Access Tokens
     * @param Array $facebookAccessTokens
     */
    public static function setSocialFacebookPageAccessTokens($facebookAccessTokens)
    {
        $setting = self::where('setting', 'social_facebook_page_access_token')->first();
        $setting->value = serialize($facebookAccessTokens);
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Active Payment Gateways
     * @return Array
     */
    public static function getPaymentGateways()
    {
        $paymentGateways = self::where('setting', 'like', '%payment_gateway_%')->get();
        $return = array();
        foreach ($paymentGateways as $gateway) {
            if ($gateway->value) {
                $return[] = str_replace('payment_gateway_', '', $gateway->setting);
            }
        }
        return $return;
    }

    /**
     * Get Supported Payment Gateways
     * @return Array
     */
    public static function getSupportedPaymentGateways()
    {
        $return = array();
        foreach (config('laravel-omnipay.gateways') as $key => $gateway) {
            $return[] = $key;
        }
        return $return;
    }

    /**
     * Enable Payment Gateway
     * @return Boolean
     */
    public static function enablePaymentGateway($gateway)
    {
        if (!$paymentGateway = self::where('setting', 'like', '%payment_gateway_'. $gateway . '%')->first()) {
            return false;
        }
        $paymentGateway->value = true;
        if (!$paymentGateway->save()) {
            return false;
        }
        return true;
    }

    /**
     * Disable Payment Gateway
     * @return Boolean
     */
    public static function disablePaymentGateway($gateway)
    {
        if (!$paymentGateway = self::where('setting', 'like', '%payment_gateway_'. $gateway . '%')->first()) {
            return false;
        }
        $paymentGateway->value = false;
        if (!$paymentGateway->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Payment Gateway Display Name
     * @return String
     */
    public static function getPaymentGatewayDisplayName($gateway)
    {
        return config('laravel-omnipay.gateways.' . $gateway . '.options.displayName');
    }

    /**
     * Get Payment Gateway Note
     * @return String
     */
    public static function getPaymentGatewayNote($gateway)
    {
        return config('laravel-omnipay.gateways.' . $gateway . '.options.note');
    }

    /**
     * Is Credit System Enabled
     * @return Boolean
     */
    public static function isCreditEnabled()
    {
        return self::where('setting', 'credit_enabled')->first()->value;
    }

    /**
     * Enable Credit System
     * @return Boolean
     */
    public static function enableCreditSystem()
    {
        if (!$creditSystemEnabled = self::where('setting', 'credit_enabled')->first()) {
            return false;
        }
        $creditSystemEnabled->value = true;
        if (!$creditSystemEnabled->save()) {
            return false;
        }
        return true;
    }

    /**
     * Disable Credit System
     * @return Boolean
     */
    public static function disableCreditSystem()
    {
        if (!$creditSystemEnabled = self::where('setting', 'credit_enabled')->first()) {
            return false;
        }
        $creditSystemEnabled->value = false;
        if (!$creditSystemEnabled->save()) {
            return false;
        }
        return true;
    }

    /**
     * Set Credit Tournament Participation Amount
     * @param Integer $amount
     * @return Boolean
     */
    public static function setCreditTournamentParticipation($amount)
    {
        $setting = self::where('setting', 'credit_award_tournament_participation')->first();
        $setting->value = $amount;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Set Credit Tournament First Amount
     * @param Integer $amount
     * @return Boolean
     */
    public static function setCreditTournamentFirst($amount)
    {
        $setting = self::where('setting', 'credit_award_tournament_first')->first();
        $setting->value = $amount;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Set Credit Tournament Second Amount
     * @param Integer $amount
     * @return Boolean
     */
    public static function setCreditTournamentSecond($amount)
    {
        $setting = self::where('setting', 'credit_award_tournament_second')->first();
        $setting->value = $amount;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Set Credit Tournament Third Amount
     * @param Integer $amount
     * @return Boolean
     */
    public static function setCreditTournamentThird($amount)
    {
        $setting = self::where('setting', 'credit_award_tournament_third')->first();
        $setting->value = $amount;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Set Credit Site Registration Amount
     * @param Integer $amount
     * @return Boolean
     */
    public static function setCreditRegistrationEvent($amount)
    {
        $setting = self::where('setting', 'credit_award_registration_event')->first();
        $setting->value = $amount;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Set Credit Event Registration Amount
     * @param Integer $amount
     * @return Boolean
     */
    public static function setCreditRegistrationSite($amount)
    {
        $setting = self::where('setting', 'credit_award_registration_site')->first();
        $setting->value = $amount;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Credit Tournament Participation Amount
     * @return Integer $amount
     */
    public static function getCreditTournamentParticipation()
    {
        return self::where('setting', 'credit_award_tournament_participation')->first()->value;
    }

    /**
     * Get Credit Tournament First Amount
     * @return Integer $amount
     */
    public static function getCreditTournamentFirst()
    {
        return self::where('setting', 'credit_award_tournament_first')->first()->value;
    }

    /**
     * Get Credit Tournament Second Amount
     * @return Integer $amount
     */
    public static function getCreditTournamentSecond()
    {
        return self::where('setting', 'credit_award_tournament_second')->first()->value;
    }

    /**
     * Get Credit Tournament Third Amount
     * @return Integer $amount
     */
    public static function getCreditTournamentThird()
    {
        return self::where('setting', 'credit_award_tournament_third')->first()->value;
    }

    /**
     * Get Credit Site Registration Amount
     * @return Integer $amount
     */
    public static function getCreditRegistrationEvent()
    {
        return self::where('setting', 'credit_award_registration_event')->first()->value;
    }

    /**
     * Get Credit Event Registration Amount
     * @return Integer $amount
     */
    public static function getCreditRegistrationSite()
    {
        return self::where('setting', 'credit_award_registration_site')->first()->value;
    }

    /**
     * Is Shop System Enabled
     * @return Boolean
     */
    public static function isShopEnabled()
    {
        return self::where('setting', 'shop_enabled')->first()->value;
    }

    /**
     * Enable Shop System
     * @return Boolean
     */
    public static function enableShopSystem()
    {
        if (!$shopSystemEnabled = self::where('setting', 'shop_enabled')->first()) {
            return false;
        }
        $shopSystemEnabled->value = true;
        if (!$shopSystemEnabled->save()) {
            return false;
        }
        return true;
    }

    /**
     * Disable Shop System
     * @return Boolean
     */
    public static function disableShopSystem()
    {
        if (!$shopSystemEnabled = self::where('setting', 'shop_enabled')->first()) {
            return false;
        }
        $shopSystemEnabled->value = false;
        if (!$shopSystemEnabled->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Shop Status
     * @return Boolean
     */
    public static function getShopStatus()
    {
        return self::where('setting', 'shop_status')->first()->value;
    }

    /**
     * Set Shop Status
     * @param String $text
     * @return Boolean
     */
    public static function setShopStatus($text)
    {
        if (!$shop = self::where('setting', 'shop_status')->first()) {
            return false;
        }
        $shop->value = $text;
        if (!$shop->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Shop Welcome Message
     * @return String
     */
    public static function getShopWelcomeMessage()
    {
        return self::where('setting', 'shop_welcome_message')->first()->value;
    }

    /**
     * Set Shop Welcome Message
     * @param String $text
     * @return Boolean
     */
    public static function setShopWelcomeMessage($text)
    {
        $setting = self::where('setting', 'shop_welcome_message')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Shop Closed Message
     * @return String
     */
    public static function getShopClosedMessage()
    {
        return self::where('setting', 'shop_closed_message')->first()->value;
    }

    /**
     * Set Shop Closed Message
     * @param String $text
     * @return Boolean
     */
    public static function setShopClosedMessage($text)
    {
        $setting = self::where('setting', 'shop_closed_message')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Active Login Methods
     * @return Array
     */
    public static function getLoginMethods()
    {
        $loginMethods = self::where('setting', 'like', '%login_%')->get();
        $return = array();
        foreach ($loginMethods as $method) {
            if ($method->value) {
                $return[] = str_replace('login_', '', $method->setting);
            }
        }
        return $return;
    }

    /**
     * Get Supported Login Methods
     * @return Array
     */
    public static function getSupportedLoginMethods()
    {
        $loginMethods = self::where('setting', 'like', '%login_%')->get();
        $return = array();
        foreach ($loginMethods as $method) {
            $return[] = str_replace('login_', '', $method->setting);
        }
        return $return;
    }

    /**
     * Enable Login Method
     * @return Boolean
     */
    public static function enableLoginMethod($method)
    {
        if (!$loginMethod = self::where('setting', 'like', '%login_'. $method . '%')->first()) {
            return false;
        }
        $loginMethod->value = true;
        if (!$loginMethod->save()) {
            return false;
        }
        return true;
    }

    /**
     * Disable Login Method
     * @return Boolean
     */
    public static function disableLoginMethod($method)
    {
        if (!$loginMethod = self::where('setting', 'like', '%login_'. $method . '%')->first()) {
            return false;
        }
        $loginMethod->value = false;
        if (!$loginMethod->save()) {
            return false;
        }
        return true;
    }

    /**
     * Is App Installed
     * @return Boolean
     */
    public static function isInstalled()
    {
        return self::where('setting', 'installed')->first()->value;
    }

    /**
     * Set App as Installed
     * @return Boolean
     */
    public static function setInstalled()
    {
        if (!$setting = self::where('setting', 'installed')->first()) {
            return false;
        }
        $setting->value = true;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

}
