# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Stack

delegated: Laravel 13 with Blade, Eloquent, SQLite locally, and a MySQL-compatible migration path for XAMPP deployment.

## Users

Primary users are arcade staff members operating a small point-of-sale station during a busy late-night shift. They need to look up products, record a sale quickly, keep stock accurate, and review what moved. A manager or instructor also needs full CRUD access to products, customers, and staff records.

## Product Purpose

Afterhours Arcade is a complete POS application for managing an arcade floor's products and transactions. Success means a staff member can sign in, maintain the catalog, record a valid sale in seconds, and trust that inventory cannot go negative.

## Positioning

The product makes the transaction itself visible: a sale is a small, auditable “round” that updates stock, names the customer when supplied, and records the staff member who closed it.

## Operating Context

The system is used on a desktop or tablet at an arcade counter with dim ambient light. Screens should scan quickly, avoid dense spreadsheets where a clear list works, and keep the next action obvious. The system is also evaluated as a student Laravel project, so the MVC, validation, migration, seeding, authentication, and upload paths should remain easy to inspect.

## Capabilities and Constraints

- Staff authentication with hashed passwords, session-backed login, logout, and protected management routes.
- Product, customer, and staff CRUD with server-side validation.
- Safe product-image and staff-avatar upload using random storage names, image MIME validation, and display-ready URLs.
- Record Sale flow with optional customer, quantity validation, database transaction, row locking, stock decrement, and a clear over-stock error.
- Sales history with product, customer, staff, quantity, total, and timestamp.
- SQLite is the default local database; environment variables can switch to MySQL for XAMPP or deployment.
- No real payment processing is represented; the sale record is the source of truth for this laboratory activity.

## Brand Commitments

The user requested a late-night arcade world: charcoal, deep navy, muted purple, soft mint/lavender accents, subtle neon, pixel-adjacent details, a gentle early-morning glow, permit-like labels, editorial arcade photography, and restrained motion. The app is named Afterhours Arcade.

## Evidence on Hand

- The assignment DOCX requires products, customers, staff, sales, stock-safe transactions, validation, uploads, authentication, migrations/seeders, and deployment documentation.
- Modules 1–4 establish MVC/routing, database configuration and models, validated forms/CRUD/uploads, and sessions/authentication.
- Local arcade imagery supplied by the user is copied into `public/media/` for the visual surface.

## Product Principles

1. Close the sale with as little hesitation as possible.
2. Make inventory truth stronger than convenience.
3. Keep every record understandable without explaining the framework.
4. Treat the quiet, dark environment as a usability constraint, not a decoration.

## Accessibility & Inclusion

Use semantic controls, visible focus, sufficient contrast, descriptive errors, keyboard-friendly forms, and reduced-motion fallbacks. Do not rely on color alone for stock or transaction status.
