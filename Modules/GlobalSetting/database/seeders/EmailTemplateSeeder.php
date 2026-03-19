<?php

namespace Modules\GlobalSetting\database\seeders;

use Illuminate\Database\Seeder;
use Modules\GlobalSetting\app\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = self::emailTemplates();

        foreach ($templates as $index => $template) {
            $new_template          = new EmailTemplate;
            $new_template->name    = $template['name'];
            $new_template->subject = $template['subject'];
            $new_template->message = $template['message'];
            $new_template->save();
        }
    }

    public static function emailTemplates()
    {
        return [
            [
                'name'    => 'password_changed',
                'subject' => 'Password Changed',
                'message' => '<p>Dear {{user_name}},</p>
                <p>Your password has been changed successfully.</p>',
            ],
            [
                'name'    => 'email_changed',
                'subject' => 'Email Changed',
                'message' => '<p>Dear {{user_name}},</p>
                <p>Your email has been changed successfully. Please Click the following link and Verified Your Email. Your new email is {{email}}</p>',
            ],
            [
                'name'    => 'password_reset',
                'subject' => 'Password Reset',
                'message' => '<p>Dear {{user_name}},</p>
                <p>Do you want to reset your password? Please Click the following link and Reset Your Password.</p>',
            ],
            [
                'name'    => 'contact_mail',
                'subject' => 'Contact Email',
                'message' => '<p>Hello there,</p>
                <p>&nbsp;Mr. {{name}} has sent a new message. you can see the message details below.&nbsp;</p>
                <p>Email: {{email}}</p>
                <p>Phone: {{phone}}</p>
                <p>Subject: {{subject}}</p>
                <p>Message: {{message}}</p>',
            ],
            [
                'name'    => 'subscribe_notification',
                'subject' => 'Subscribe Notification',
                'message' => '<p>Hi there, Congratulations! Your Subscription has been created successfully. Please Click the following link and Verified Your Subscription. If you will not approve this link, you can not get any newsletter from us.</p>',
            ],
            [
                'name'    => 'social_login',
                'subject' => 'Social Login',
                'message' => '<p>Hello {{user_name}},</p>
                <p>Welcome to {{app_name}}! Your account has been created successfully.</p>
                <p>Your password: {{password}}</p>
                <p>You can log in to your account at <a href="https://websolutionus.com">https://websolutionus.com</a></p>
                <p>Thank you for joining us.</p>',
            ],

            [
                'name'    => 'user_verification',
                'subject' => 'User Verification',
                'message' => '<p>Dear {{user_name}},</p>
                <p>Congratulations! Your account has been created successfully. Please click the following link to activate your account.</p>',
            ],

            [
                'name'    => 'shop_verification',
                'subject' => 'Vendor Shop Verification',
                'message' => '<p>Dear {{shop_name}},</p>
                <p>Congratulations! Your shop has been created successfully. Please click the following link to activate your shop.</p>',
            ],

            [
                'name'    => 'shop_verification_complete',
                'subject' => 'Shop Verification Completed',
                'message' => '<p>Dear {{shop_name}},</p>
                <p>Congratulations! Your shop has been verified successfully. Now you can start selling your products.</p>',
            ],

            // [
            //     'name'    => 'approved_refund',
            //     'subject' => 'Refund Request Approval',
            //     'message' => '<p>Dear {{user_name}},</p>
            //     <p>We are happy to say that, we have send {{refund_amount}} USD to your provided bank information. </p>',
            // ],

            // [
            //     'name'    => 'new_refund',
            //     'subject' => 'New Refund Request',
            //     'message' => '<p>Hello websolutionus, </p>

            //     <p>Mr. {{user_name}} has send a new refund request to you.</p>',
            // ],

            [
                'name'    => 'order_placed',
                'subject' => 'Order Placed',
                'message' => '<p>Hello {{user_name}},</p>
                <p>Your order has been placed successfully. Your order id is: #{{order_id}}</p>
                <p>Order Status: {{order_status}}</p>
                <p>Amount To Pay: {{amount}} {{amount_currency}}</p>
                <p>Payment Method: {{payment_method}}</p>
                <p>Payment Status: {{payment_status}}</p>
                <p>Shipping Address: {{shipping_address}}</p>
                <p>Thanks &amp; Regards</p>',
            ],

            [
                'name'    => 'order_status_update',
                'subject' => 'Order Status Update',
                'message' => '<p>Hello {{user_name}},</p>
                <p>Your order <b>#{{order_id}}</b> status has been changed to {{order_status}}</p>
                <p>Payment: {{amount}} {{amount_currency}}</p>
                <p>Payment Method: {{payment_method}}</p>
                <p>Payment Status: {{payment_status}}</p>
                <p>Shipping Address: {{shipping_address}}</p>
                <p>Thanks &amp; Regards</p>',
            ],

            [
                'name'    => 'order_payment_status_update',
                'subject' => 'Order Payment Status Update',
                'message' => '<p>Hello {{user_name}},</p>
                <p>Your order <b>#{{order_id}}</b> payment status has been changed to {{payment_status}}</p>
                <p>Order Status: {{order_status}}</p>
                <p>Payment: {{amount}} {{amount_currency}}</p>
                <p>Payment Method: {{payment_method}}</p>
                <p>Shipping Address: {{shipping_address}}</p>
                <p>Thanks &amp; Regards</p>',
            ],

            [
                'name'    => 'seller_order_status_update',
                'subject' => 'Order Status Update by Admin',
                'message' => '<p>Hello {{user_name}},</p>
                <p>A order on your shop with order id <b>#{{order_id}}</b> status has been changed to {{order_status}} by admin</p>
                <p>Payment: {{amount}} {{amount_currency}}</p>
                <p>Payment Method: {{payment_method}}</p>
                <p>Payment Status: {{payment_status}}</p>
                <p>Shipping Address: {{shipping_address}}</p>
                <p>Thanks &amp; Regards</p>',
            ],

            [
                'name'    => 'order_payment_confirmed',
                'subject' => 'Order Payment Confirmed',
                'message' => '<p>Hello {{user_name}},</p>
                <p>Your order <b>#{{order_id}}</b> payment has been confirmed</p>
                <p>Payment: {{amount}} {{amount_currency}}</p>
                <p>Payment Method: {{payment_method}}</p>
                <p>Payment Status: {{payment_status}}</p>
                <p>Shipping Address: {{shipping_address}}</p>
                <p>Billing Address: {{billing_address}}</p>
                <p>Thanks &amp; Regards</p>',
            ],

            [
                'name'    => 'requested_withdraw',
                'subject' => 'Withdraw Request Received',
                'message' => '<p>Dear {{user_name}},</p>
                <p>We are happy to say that, we have received your withdraw request.</p>
                <p>Thanks &amp; Regards</p>',
            ],

            [
                'name'    => 'approved_withdraw',
                'subject' => 'Withdraw Request Approval',
                'message' => '<p>Dear {{user_name}},</p>
                <p>We are happy to say that, we have send a withdraw amount {{amount}} to your provided {{method}} payment method information.</p>
                <p>Thanks &amp; Regards</p>',
            ],

            [
                'name'    => 'product_approved',
                'subject' => 'Your Product Approved',
                'message' => '<p>Dear {{shop_name}},</p>
                <p>Congratulations! Your product <b>{{product_name}}</b> has been approved successfully. Now you can start selling your products. Check the product details at from the links below.</p>
                <p>Thanks &amp; Regards</p>',
            ],

            [
                'name'    => 'kyc_approved',
                'subject' => 'Your ID Verification Approved',
                'message' => '<p>Dear {{shop_name}},</p>
                <p>Congratulations! Your ID verification has been approved successfully. Now you can start selling your products.</p>
                <p>Thanks &amp; Regards</p>',
            ],

            [
                'name'    => 'wallet_request_approved',
                'subject' => 'Wallet Request Approved',
                'message' => '<p>Dear {{shop_name}},</p>
                <p>Congratulations! We have added {{amount}} to your wallet. Check your wallet balance at from the links below.</p>
                <p>Thanks &amp; Regards</p>',
            ],

            [
                'name'    => 'commission_received',
                'subject' => 'Commission Received',
                'message' => '<p>Dear {{shop_name}},</p>
                <p>Congratulations! You received a commission of {{amount}} for your product <b>{{product_name}}</b>. Check the product details at from the links below.</p>
                <p>Thanks &amp; Regards</p>',
            ],

            [
                'name'    => 'seller_deleted',
                'subject' => 'Seller Profile Deleted',
                'message' => '<p>Dear {{shop_name}},</p>
                <p>Your seller profile has been deleted successfully. Now you can not sell your products. But you can use this id as a regular user.</p>
                <p>Thanks &amp; Regards</p>',
            ],

            [
                'name'    => 'order_placed_vendor',
                'subject' => 'New Order Placed on Your Shop',
                'message' => '<p>Hello {{user_name}},</p>
                <p>Your order has been placed to your {{shop_name}}. Your order id is: #{{order_id}}</p>
                <p>Order Status: {{order_status}}</p>
                <p>Amount To Pay: {{amount}} {{amount_currency}}</p>
                <p>Payment Method: {{payment_method}}</p>
                <p>Payment Status: {{payment_status}}</p>
                <p>Shipping Address: {{shipping_address}}</p>
                <p>Thanks &amp; Regards</p>',
            ],

        ];
    }
}
