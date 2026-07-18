# Akhwat Gym Entity Relationship Diagram

This document reflects the final database shape produced by all migrations in `database/migrations`.

## Exports

- [High-resolution PNG](exports/akhwat-gym-erd.png)
- [Vector PDF](exports/akhwat-gym-erd.pdf)
- [Graphviz source](exports/akhwat-gym-erd.dot)

## Core Business Domain

```mermaid
erDiagram
    USERS {
        bigint id PK
        string name
        string email UK
        string phone
        string avatar_url
        timestamp email_verified_at
        string password
        timestamp created_at
        timestamp updated_at
    }

    MEMBERS {
        bigint id PK
        bigint user_id FK,UK
        string member_code UK
        date joined_at
        timestamp created_at
        timestamp updated_at
    }

    TRAINERS {
        bigint id PK
        bigint user_id FK,UK
        string specialization
        text bio
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    MEMBERSHIP_PACKAGES {
        bigint id PK
        string name
        text description
        string package_type
        string billing_cycle
        boolean includes_personal_trainer
        boolean has_visit_limit
        int visit_limit
        json allowed_class_types
        int duration_days
        decimal price
        int discount_percent
        decimal original_price
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    MEMBERSHIP_PURCHASES {
        bigint id PK
        bigint member_id FK
        bigint membership_package_id FK
        datetime starts_at
        datetime expires_at
        string status
        boolean includes_personal_trainer
        int visits_allowed
        int visits_used
        string payment_method
        decimal amount
        string payment_reference
        timestamp created_at
        timestamp updated_at
    }

    FITNESS_CLASSES {
        bigint id PK
        bigint trainer_id FK
        string name
        string class_type
        text description
        int capacity
        string location
        boolean is_recurring
        json recurring_days
        date recurrence_ends_at
        date class_date
        time start_time
        time end_time
        boolean is_active
        boolean allow_drop_in
        decimal drop_in_price
        decimal trainer_addon_price
        timestamp created_at
        timestamp updated_at
    }

    CLASS_BOOKINGS {
        bigint id PK
        bigint member_id FK
        bigint fitness_class_id FK
        date booked_for_date UK
        string status
        string access_type
        boolean personal_trainer_requested
        decimal amount
        string payment_method
        string payment_reference
        datetime booked_at
        datetime cancelled_at
        timestamp created_at
        timestamp updated_at
    }

    ATTENDANCES {
        bigint id PK
        bigint member_id FK
        bigint fitness_class_id FK
        datetime check_in_time
        string status
        string location
        timestamp created_at
        timestamp updated_at
    }

    PRODUCT_CATEGORIES {
        bigint id PK
        string name UK
        string slug UK
        timestamp created_at
        timestamp updated_at
    }

    PRODUCTS {
        bigint id PK
        bigint product_category_id FK
        string name
        text description
        decimal price
        int stock
        string image_url
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    ORDERS {
        bigint id PK
        bigint member_id FK
        string status
        string payment_method
        decimal total_price
        string payment_reference
        timestamp created_at
        timestamp updated_at
    }

    ORDER_ITEMS {
        bigint id PK
        bigint order_id FK
        bigint product_id FK
        int quantity
        decimal unit_price
        decimal subtotal
        timestamp created_at
        timestamp updated_at
    }

    DEVICE_TOKENS {
        bigint id PK
        bigint user_id FK
        string token UK
        string platform
        timestamp created_at
        timestamp updated_at
    }

    FACILITIES {
        bigint id PK
        string name
        string slug UK
        text description
        string icon
        int sort_order
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    USERS ||--o| MEMBERS : "has member profile"
    USERS ||--o| TRAINERS : "has trainer profile"
    USERS ||--o{ DEVICE_TOKENS : "registers devices"

    MEMBERS ||--o{ MEMBERSHIP_PURCHASES : purchases
    MEMBERSHIP_PACKAGES ||--o{ MEMBERSHIP_PURCHASES : selected_package

    TRAINERS ||--o{ FITNESS_CLASSES : leads
    MEMBERS ||--o{ CLASS_BOOKINGS : books
    FITNESS_CLASSES ||--o{ CLASS_BOOKINGS : receives
    MEMBERS ||--o{ ATTENDANCES : checks_in
    FITNESS_CLASSES o|--o{ ATTENDANCES : records

    PRODUCT_CATEGORIES ||--o{ PRODUCTS : contains
    MEMBERS ||--o{ ORDERS : places
    ORDERS ||--|{ ORDER_ITEMS : contains
    PRODUCTS ||--o{ ORDER_ITEMS : purchased_as
```

### Core Constraints

- `members.user_id` and `trainers.user_id` are unique one-to-one profile links.
- A booking is unique by `member_id`, `fitness_class_id`, and `booked_for_date`.
- Deleting a member cascades to memberships, bookings, attendance, and orders.
- Deleting a fitness class cascades to bookings, but attendance keeps its record and sets `fitness_class_id` to null.
- Membership packages, trainers, product categories, and products use restrictive deletion where historical data depends on them.
- `facilities` is currently an independent catalog without a foreign-key relationship.

## Access, Authentication, and Notifications

```mermaid
erDiagram
    USERS {
        bigint id PK
        string email UK
    }

    ROLES {
        bigint id PK
        string name UK
        string guard_name
    }

    PERMISSIONS {
        bigint id PK
        string name UK
        string guard_name
    }

    MODEL_HAS_ROLES {
        bigint role_id PK,FK
        string model_type PK
        bigint model_id PK
    }

    MODEL_HAS_PERMISSIONS {
        bigint permission_id PK,FK
        string model_type PK
        bigint model_id PK
    }

    ROLE_HAS_PERMISSIONS {
        bigint role_id PK,FK
        bigint permission_id PK,FK
    }

    PERSONAL_ACCESS_TOKENS {
        bigint id PK
        string tokenable_type
        bigint tokenable_id
        string name
        string token UK
        text abilities
        timestamp last_used_at
        timestamp expires_at
    }

    DEVICE_TOKENS {
        bigint id PK
        bigint user_id FK
        string token UK
        string platform
    }

    NOTIFICATIONS {
        uuid id PK
        string type
        string notifiable_type
        bigint notifiable_id
        json data
        timestamp read_at
    }

    USERS ||--o{ MODEL_HAS_ROLES : "assigned via polymorphic model"
    ROLES ||--o{ MODEL_HAS_ROLES : assignments
    USERS ||--o{ MODEL_HAS_PERMISSIONS : "direct permissions"
    PERMISSIONS ||--o{ MODEL_HAS_PERMISSIONS : assignments
    ROLES ||--o{ ROLE_HAS_PERMISSIONS : grants
    PERMISSIONS ||--o{ ROLE_HAS_PERMISSIONS : included_in
    USERS ||--o{ PERSONAL_ACCESS_TOKENS : "owns via tokenable"
    USERS ||--o{ DEVICE_TOKENS : registers
    USERS ||--o{ NOTIFICATIONS : "receives via notifiable"
```

The configured administrative roles are `Owner`, `Super admin`, and `Admin di lokasi`. The Spatie pivot tables are polymorphic, but this application currently assigns them to `App\\Models\\User`.

## Polymorphic Support Tables

```mermaid
erDiagram
    POLYMORPHIC_MODEL {
        string model_type
        bigint model_id
    }

    MEDIA {
        bigint id PK
        string model_type
        bigint model_id
        uuid uuid UK
        string collection_name
        string file_name
        string mime_type
        string disk
        bigint size
        json custom_properties
    }

    ACTIVITY_LOG {
        bigint id PK
        string log_name
        text description
        string event
        string subject_type
        bigint subject_id
        string causer_type
        bigint causer_id
        json attribute_changes
        json properties
    }

    POLYMORPHIC_MODEL ||--o{ MEDIA : owns
    POLYMORPHIC_MODEL o|--o{ ACTIVITY_LOG : subject
    POLYMORPHIC_MODEL o|--o{ ACTIVITY_LOG : causer
```

## Framework Infrastructure Tables

The following tables are intentionally omitted from the relationship diagrams because they are framework storage rather than business entities:

- `password_reset_tokens`
- `sessions`
- `cache`
- `cache_locks`
- `jobs`
- `job_batches`
- `failed_jobs`
