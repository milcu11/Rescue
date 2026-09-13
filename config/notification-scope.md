# Notification Scope

RescuePH currently delivers notifications in-app through the web notification center and authenticated API notification endpoint. Email, SMS, push notifications, and external messaging integrations are not implemented in this scope; they remain future extensions.

Current thresholds are configurable in `config/notifications.php`:

- Low stock: each inventory item's configured `minimum_threshold`.
- Near expiration: `NOTIFICATION_NEAR_EXPIRATION_DAYS`, default 30 days.
- Near capacity: `NOTIFICATION_NEAR_CAPACITY_PERCENT`, default 80 percent.
- Full capacity: 100 percent.
- Duplicate alert suppression: `NOTIFICATION_DUPLICATE_WINDOW_HOURS`, default 24 hours.

The `notifications:check-expirations` scheduled command should run daily in the deployment scheduler to create expiry alerts as dates approach, even when inventory records are not edited.
