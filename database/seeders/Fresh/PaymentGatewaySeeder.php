<?php

namespace Database\Seeders\Fresh;

use App\Models\Admin\PaymentGateway;
use App\Models\Admin\PaymentGatewayCurrency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payment_gateways = array(
            array('id' => '17', 'slug' => 'add-money', 'code' => '105', 'type' => 'AUTOMATIC', 'name' => 'Paypal', 'title' => 'Global Setting for paypal in bellow', 'alias' => 'paypal', 'image' => '14ad4bf6-f2cb-4883-a54a-019cf7b2e1f8.webp', 'credentials' => '[{"label":"Secret Key","placeholder":"Enter Secret Key","name":"secret-key","value":"ENK3pTxBq0qdbYiyd6yIe1NWbI1Y8SRbrslX_o7OLeF9Xyo-rAZSVe2pjvP5vozz-dog6EmYIVEkBRQn"},{"label":"Client ID","placeholder":"Enter Client ID","name":"client-id","value":"ASBnxWkjSDubeSl2diYlvFaSiNrdYJ2w2XK3EBfALxFIVaRC09snaPD0qjKPvxwpmkL1QFmp-CTBmoql"}]', 'supported_currencies' => '["USD","AUD"]', 'crypto' => '0', 'desc' => NULL, 'input_fields' => NULL, 'env' => 'SANDBOX', 'status' => '1', 'last_edit_by' => '1', 'created_at' => '2023-08-13 09:52:32', 'updated_at' => '2023-08-13 09:53:03'),
            array('id' => '18', 'slug' => 'add-money', 'code' => '110', 'type' => 'AUTOMATIC', 'name' => 'Stripe', 'title' => 'Global Setting for stripe in bellow', 'alias' => 'stripe', 'image' => 'adfcfc2d-ddf1-4045-9fe3-92428dc4acae.webp', 'credentials' => '[{"label":"Publishable Key","placeholder":"Enter Publishable Key","name":"publishable-key","value":"pk_test_51N2RvKLjZn37cB06JN5uFzoB5m1EytdSHmd9VWY35yBErpIyhZrxCXSinEtIYQrvfmPQPAM6WCvDLMolY84hDHkS00TaXUISX5"},{"label":"Secret Key","placeholder":"Enter Secret Key","name":"secret-key","value":"sk_test_51N2RvKLjZn37cB06dDUoMluTmliLOLhnihQgf2lBgGd9ftUrKji4isdhOT0ZNY17LsjBvMDyKk4QTI3kFNoSUYIM003IyxPPZn"}]', 'supported_currencies' => '["USD","AUD"]', 'crypto' => '0', 'desc' => NULL, 'input_fields' => NULL, 'env' => 'SANDBOX', 'status' => '1', 'last_edit_by' => '1', 'created_at' => '2023-08-13 09:59:56', 'updated_at' => '2023-08-13 10:00:28'),
            array('id' => '21', 'slug' => 'add-money', 'code' => '125', 'type' => 'MANUAL', 'name' => 'ADPay', 'title' => 'ADPay Gateway', 'alias' => 'adpay', 'image' => NULL, 'credentials' => NULL, 'supported_currencies' => '["GBP"]', 'crypto' => '0', 'desc' => '<p><strong>Instructions:</strong><br>To initiate a payment using our manual payment gateway, please follow the instructions provided below. We offer two convenient methods for you to choose from:</p><p><strong>Bank Transfer</strong></p><ol><li>Visit your local bank or access your online banking platform.</li><li>Initiate a new fund transfer or payment.</li><li>Enter the recipient’s bank account details:</li><li>Bank Name: HSBC</li><li>IBAN (International Bank Account Number): 01234567890</li><li>Specify the payment amount in the currency you intend to use.</li><li>Double-check all details, including the recipient’s account information.</li><li>Confirm and authorize the transfer.</li><li>Retain the payment receipt or confirmation for future reference.</li></ol><p>Please ensure that you keep a record of your payment as proof of the transaction. In case of any discrepancies or verification requirements, you may be asked to provide this documentation.Your payment will be manually verified by our team, and once confirmed, your order will be processed promptly. We appreciate your cooperation and look forward to serving you!</p>', 'input_fields' => '[{"type":"text","label":"TRX ID","name":"trx_id","required":true,"validation":{"max":"30","mimes":[],"min":"0","options":[],"required":true}},{"type":"file","label":"Screenshot","name":"screenshot","required":true,"validation":{"max":"10","mimes":["jpg","png","jpeg"],"min":0,"options":[],"required":true}}]', 'env' => NULL, 'status' => '1', 'last_edit_by' => '1', 'created_at' => NULL, 'updated_at' => '2023-08-23 09:29:03')
        );

        PaymentGateway::insert($payment_gateways);

        $payment_gateway_currencies = array(
            array('id' => '43', 'payment_gateway_id' => '17', 'name' => 'Paypal USD', 'alias' => 'add-money-paypal-usd-automatic', 'currency_code' => 'USD', 'currency_symbol' => '$', 'image' => 'a17d7b9e-6012-4b39-a835-176e63c3d10e.webp', 'min_limit' => '0.00000000', 'max_limit' => '5000.00000000', 'percent_charge' => '2.00000000', 'fixed_charge' => '2.00000000', 'rate' => '1.00000000', 'created_at' => '2023-08-13 09:53:03', 'updated_at' => '2023-08-13 09:57:08'),
            array('id' => '45', 'payment_gateway_id' => '18', 'name' => 'Stripe USD', 'alias' => 'add-money-stripe-usd-automatic', 'currency_code' => 'USD', 'currency_symbol' => '$', 'image' => NULL, 'min_limit' => '0.00000000', 'max_limit' => '5000.00000000', 'percent_charge' => '2.00000000', 'fixed_charge' => '2.00000000', 'rate' => '1.00000000', 'created_at' => '2023-08-13 10:00:28', 'updated_at' => '2023-08-13 10:00:28'),
            array('id' => '48', 'payment_gateway_id' => '21', 'name' => 'ADPay GBP', 'alias' => 'adpay-gbp-manual', 'currency_code' => 'GBP', 'currency_symbol' => '£', 'image' => NULL, 'min_limit' => '0.00000000', 'max_limit' => '5000.00000000', 'percent_charge' => '2.00000000', 'fixed_charge' => '2.00000000', 'rate' => '1.00000000', 'created_at' => '2023-08-22 11:46:40', 'updated_at' => '2023-08-22 11:46:40')
        );


        PaymentGatewayCurrency::insert($payment_gateway_currencies);
    }
}
