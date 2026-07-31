Payment flow notes:
- Set PAYMONGO_PUBLIC_KEY, PAYMONGO_SECRET_KEY, PAYMONGO_WEBHOOK_SECRET and PAYMONGO_ENVIRONMENT in .env.
- Use sandbox mode for capstone demos.
- The controller will redirect to PayMongo's hosted checkout page.
- Webhook route is /api/webhooks/paymongo.
