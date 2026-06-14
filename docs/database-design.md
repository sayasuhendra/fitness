# Fitness Akhwat Database Design

## Core Tables

- `users`: auth identity, contact fields, Sanctum owner.
- `members`: member profile linked one-to-one to users.
- `trainers`: trainer profile linked one-to-one to users.
- `membership_packages`: purchasable package catalog.
- `membership_purchases`: member package transactions and active/expired state.
- `fitness_classes`: trainer-led schedules with date, time, location, and capacity.
- `class_bookings`: member class reservations with cancellation state.
- `attendances`: QR check-in records.
- `product_categories`: Healthy Food, Healthy Drink, Supplements.
- `products`: stock-managed store catalog.
- `orders` and `order_items`: checkout history and stock deduction audit.
- `device_tokens`: Firebase Cloud Messaging targets.

## Relationships

- User has one member or trainer profile.
- Member has many memberships, bookings, attendances, and orders.
- Trainer has many fitness classes.
- Fitness class has many bookings and attendance records.
- Product belongs to a category and appears in many order items.
- Order belongs to member and has many order items.

## Payment and Notification Notes

Payment references are generated with a Midtrans-ready placeholder. Production Midtrans callbacks should update `membership_purchases.status` or `orders.status` inside a signed webhook controller. Notifications can be queued from those status transitions using Laravel Notifications and stored in the `notifications` table.
