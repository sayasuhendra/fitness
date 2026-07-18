# Akhwat Gym Database Design

The complete visual database diagram is available in [ERD.md](ERD.md).

## Core Tables

- `users`: auth identity, contact fields, Sanctum owner.
- `members`: member profile linked one-to-one to users.
- `trainers`: trainer profile linked one-to-one to users.
- `membership_packages`: purchasable package catalog.
- `membership_purchases`: member package transactions and active/expired state.
- `fitness_classes`: trainer-led schedules with date, time, location, and capacity.
- `class_sessions`: generated dated sessions from weekly class templates. Members book these dated sessions, not the weekly template directly.
- `class_bookings`: member class reservations with cancellation state, linked to a dated class session when available.
- `personal_trainer_sessions`: scheduled personal trainer sessions for PT memberships or one-time PT visitors.
- `attendances`: QR/manual check-in records for gym visits, class attendance, and personal trainer sessions.
- `bank_accounts`, `qris_payment_methods`, and `payment_confirmations`: manual payment setup, proof upload, WhatsApp follow-up, and admin verification.
- `product_categories`: Healthy Food, Healthy Drink, Supplements.
- `products`: stock-managed store catalog.
- `orders` and `order_items`: checkout history and stock deduction audit.
- `device_tokens`: Firebase Cloud Messaging targets.

## Relationships

- User has one member or trainer profile.
- Member has many memberships, bookings, personal trainer sessions, attendances, and orders.
- Trainer has many fitness classes and personal trainer sessions.
- Fitness class has many generated class sessions, bookings, and attendance records.
- Class session belongs to a fitness class and stores the real date, time, capacity, and booking count for that occurrence.
- Personal trainer session belongs to a member and trainer, can be paid manually, and can have attendance records.
- Product belongs to a category and appears in many order items.
- Order belongs to member and has many order items.

## Payment and Notification Notes

Payment uses manual transfer and QRIS confirmation. Admins manage active bank accounts and QRIS images, members submit payment confirmations with optional proof uploads or WhatsApp follow-up links, and admins approve or reject payments before membership, one-time visit, or order benefits become active.
