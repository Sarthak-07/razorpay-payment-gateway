# RazorPay Payment Gateway Extension

This extension enables users to seamlessly integrate the RazorPay Payment Gateway into their Paymenter tailored for Indian hostings. With this extension, clients can securely make payments via a wide range of options, with 100+ Payment Modes - All Cards, UPI, EMI, Wallets & More. This ensures a smooth and hassle-free payment experience specifically designed for Indian Hosting Services.

## Configuration

1. **Configure Webhooks:** Add a Webhook with event "order.paid" in RazorPay Dashboard. Ensure the Webhook URL format is `https://<your_paymenter_url>/extensions/razorpay/webhook`. For example, if your Paymenter URL is `billing.stellarhost.tech`, the webhook URL should be `https://billing.stellarhost.tech/extensions/razorpay/webhook`. Make sure to include `https://` and `/extensions/razorpay/webhook`.
2. **Enable RazorPay Extension:** Navigate to Paymenter's Extensions Settings, enable the RazorPay extension, and provide your API Key details.

Congratulations! Your RazorPay Payment Gateway setup is now complete!

## Support

For any assistance or queries, please reach out to [@sarthak77](https://discord.stellarhost.tech/) on Discord.
