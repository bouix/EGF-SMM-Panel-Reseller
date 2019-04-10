<?php

use Illuminate\Database\Seeder;

class PaymentMethodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('payment_methods')->delete();
        \DB::table('payment_methods')->insert([
            1 => [
                'id' => 1,
                'name' => 'PayPal',
                'slug' => 'paypal',
                'config_key' => null,
                'config_value' => null,
            ],
            2 => [
                'id' => 2,
                'name' => 'Stripe',
                'slug' => 'stripe',
                'config_key' => null,
                'config_value' => null,
            ],
            3 => [
                'id' => 3,
                'name' => 'Bitcoin',
                'slug' => 'bitcoin',
                'config_key' => null,
                'config_value' => null,
            ],
            4 => [
                'id' => 4,
                'name' => 'Payza',
                'slug' => 'payza',
                'config_key' => null,
                'config_value' => null,
            ],
            5 => [
                'id' => 5,
                'name' => 'Bank/Other',
                'slug' => 'bank-other',
                'config_key' => null,
                'config_value' => null,
            ],
            6 => [
                'id' => 6,
                'name' => 'Instamojo',
                'slug' => 'instamojo',
                'config_key' => null,
                'config_value' => null,
            ],
            7 => [
                'id' => 7,
                'name' => 'Skrill',
                'slug' => 'skrill',
                'config_key' => null,
                'config_value' => null,
            ],
            8 => [
                'id' => 8,
                'name' => 'Paytm',
                'slug' => 'paytm',
                'config_key' => null,
                'config_value' => null,
            ],
            9 => [
                'id' => 9,
                'name' => 'Paywant',
                'slug' => 'paywant',
                'config_key' => null,
                'config_value' => null,
            ],
            101 => [
                'id' => 101,
                'name' => 'PayPal',
                'slug' => 'paypal',
                'config_key' => 'paypal_mode',
                'config_value' => 'live',
            ],
            102 => [
                'id' => 102,
                'name' => 'Stripe',
                'slug' => 'stripe',
                'config_key' => 'stripe_secret',
                'config_value' => 'sc_abc123',
            ],
            103 => [
                'id' => 103,
                'name' => 'Stripe',
                'slug' => 'stripe',
                'config_key' => 'stripe_key',
                'config_value' => 'sc_abc123',
            ],
            104 => [
                'id' => 104,
                'name' => 'Bitcoin',
                'slug' => 'bitcoin',
                'config_key' => 'merchant_id',
                'config_value' => '123456',
            ],
            105 => [
                'id' => 105,
                'name' => 'Bitcoin',
                'slug' => 'bitcoin',
                'config_key' => 'secret_key',
                'config_value' => 'sec_key456',
            ],

            106 => [
                'id' => 106,
                'name' => 'Payza',
                'slug' => 'payza',
                'config_key' => 'ap_merchant',
                'config_value' => 'test@test.com',
            ],
            107 => [
                'id' => 107,
                'name' => 'Payza',
                'slug' => 'payza',
                'config_key' => 'payza_mode',
                'config_value' => 'live',
            ],
            108 => [
                'id' => 108,
                'name' => 'Bank/Other',
                'slug' => 'bank-other',
                'config_key' => 'bank_details',
                'config_value' => 'Update here bank accounts etc',
            ],
            109 => [
                'id' => 109,
                'name' => 'Paypal',
                'slug' => 'paypal',
                'config_key' => 'paypal_email',
                'config_value' => 'test@test.com',
            ],
            110 => [
                'id' => 110,
                'name' => 'Instamojo',
                'slug' => 'instamojo',
                'config_key' => 'instamojo_api_key',
                'config_value' => '123456',
            ],
            111 => [
                'id' => 111,
                'name' => 'Instamojo',
                'slug' => 'instamojo',
                'config_key' => 'instamojo_token',
                'config_value' => '123456',
            ],
            112 => [
                'id' => 112,
                'name' => 'Instamojo',
                'slug' => 'instamojo',
                'config_key' => 'instamojo_salt',
                'config_value' => '123456',
            ],
            113 => [
                'id' => 113,
                'name' => 'Skrill',
                'slug' => 'skrill',
                'config_key' => 'skrill_email',
                'config_value' => 'test@test.com',
            ],
            114 => [
                'id' => 114,
                'name' => 'Skrill',
                'slug' => 'skrill',
                'config_key' => 'skrill_secret',
                'config_value' => 'replace-me',
            ],
            115 => [
                'id' => 115,
                'name' => 'Paytm',
                'slug' => 'paytm',
                'config_key' => 'paytm_email_imap_address',
                'config_value' => '{imap.gmail.com:993/imap/ssl}INBOX',
            ],
            116 => [
                'id' => 116,
                'name' => 'Paytm',
                'slug' => 'paytm',
                'config_key' => 'paytm_email',
                'config_value' => 'your@email.com',
            ],
            117 => [
                'id' => 117,
                'name' => 'Paytm',
                'slug' => 'paytm',
                'config_key' => 'paytm_email_password',
                'config_value' => '123456',
            ],
            118 => [
                'id' => 118,
                'name' => 'Paytm',
                'slug' => 'paytm',
                'config_key' => 'paytm_indian_rupees_valued_1_usd',
                'config_value' => '66',
            ],
            119 => [
                'id' => 119,
                'name' => 'Instamojo',
                'slug' => 'instamojo',
                'config_key' => 'instamojo_indian_rupees_valued_1_usd',
                'config_value' => '66',
            ],
            120 => [
                'id' => 120,
                'name' => 'Paywant',
                'slug' => 'paywant',
                'config_key' => 'paywant_api_key',
                'config_value' => '123-123-123',
            ],
            121 => [
                'id' => 121,
                'name' => 'Paywant',
                'slug' => 'paywant',
                'config_key' => 'paywant_api_secret',
                'config_value' => '123-123-123',
            ],
        ]);
    }
}
