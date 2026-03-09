# Bojeri ERP — Everything Your Business Needs, In One Platform

Bojeri is a modular, multi-tenant ERP platform built for growing businesses that refuse to stitch together five separate tools. From invoicing and accounting to project management, HRM, CRM, and point-of-sale — Bojeri unifies your operations under a single login, a single database, and a single source of truth. Built on Laravel 12 with real-time capabilities, multi-language support, and flexible subscription plans, Bojeri scales from solo operators to organizations with hundreds of team members across multiple warehouses and locations.

---

## Everything You Get

### Core Business Operations
- **Sales Invoicing** — Multi-line invoices with taxes, discounts, balance tracking, print views, and a full returns workflow (Draft → Posted; Returns: Draft → Approved → Completed)
- **Purchase Management** — Vendor invoices, purchase returns with auto debit notes, warehouse management, and inter-warehouse stock transfers
- **Proposals** — Create, send, and track proposals with one-click conversion to invoices (Draft → Sent → Accepted → Rejected)
- **Products & Services** — Centralized catalog with SKUs, dual pricing, categories, taxes, units, and per-warehouse stock levels
- **POS** — Browser-based point-of-sale with barcode printing, order tracking, and real-time inventory sync

### Financial Management
- **Accounting** — Full double-entry bookkeeping with Chart of Accounts, revenue, expenses, bank accounts, transfers, customer/vendor payments, credit notes, debit notes, and financial reports

### Team & People Management
- **HRM** — Employees, shifts, clock in/out with IP restriction, overtime calculation, leave management, payroll, payslips, awards, promotions, warnings, complaints, and more
- **Project Management** — Projects with tasks, subtasks, milestones, Kanban boards, calendar views, bug tracking, file uploads, activity logs, and project reports

### Customer & Sales
- **CRM** — Lead and deal pipelines with custom stages, multi-user assignments, source tracking, and conversion analytics

### Communication & Support
- **Messenger** — Real-time messaging powered by Pusher with file attachments, favorites, pins, presence, and read receipts
- **Helpdesk** — Ticket system with categories, priorities, threaded replies, internal notes, file attachments, and resolution tracking

### Platform & Administration
- **User Management** — Custom roles, granular permissions (Spatie), user impersonation, and login history auditing
- **Media Library** — Centralized file management with batch upload, directories, and cloud storage (AWS S3, Wasabi)
- **Subscriptions** — SaaS billing with Stripe, PayPal, bank transfers, free plans, trials, and coupon codes
- **Settings** — Branding, currency, email/SMTP, storage backends, cookie consent, SEO, multi-language, Pusher, and email/notification templates
- **Dashboard** — Five purpose-built views (Account, Project, HRM, POS, CRM) with live metrics and charts

### Hidden Gems
- 🌍 **Full Localization** — Create unlimited languages, edit translation strings, manage per-package translations
- 🔐 **Two-Factor Authentication** — Google 2FA integration for enhanced security
- 📱 **Social Login** — Sign in with Google via Laravel Socialite
- 🔔 **CAPTCHA Protection** — Google reCAPTCHA on registration and login
- 📧 **Customizable Email Templates** — Multi-language templates with dynamic placeholders
- ☁️ **Multi-Cloud Storage** — Switch between local, AWS S3, and Wasabi without code changes
- 📦 **Modular Architecture** — Activate/deactivate modules per plan for flexible packaging
- 🕐 **Night Shift Support** — Attendance calculations that correctly handle overnight shifts
- 🧾 **ZATCA Compliance** — Saudi e-invoicing support via the Salla ZATCA package
- 📊 **QR Codes** — Generated with bacon/bacon-qr-code for invoices and receipts
- 🔄 **Event-Driven Architecture** — Every major action fires events for extensibility
- 🌐 **Inertia.js + Vue** — Server-driven SPA experience without API overhead

---

## Module Guides

| # | Module | Tagline | Guide |
|---|--------|---------|-------|
| 01 | [Dashboard](01-dashboard.md) | Your entire business at a glance | Overview of five dashboard views |
| 02 | [User Management](02-user-management.md) | The right people, the right access | Roles, permissions, and user lifecycle |
| 03 | [Proposals](03-proposals.md) | Turn quotes into commitments | Proposal lifecycle and invoice conversion |
| 04 | [Sales Invoice](04-sales-invoice.md) | From draft to paid | Invoicing, posting, returns |
| 05 | [Purchase](05-purchase.md) | Control what comes in | Purchasing, returns, warehouses, transfers |
| 06 | [Products & Services](06-products-services.md) | Your complete catalog | Items, categories, taxes, warehouse stock |
| 07 | [Projects](07-projects.md) | Plan, track, and deliver | Tasks, bugs, milestones, Kanban |
| 08 | [Accounting](08-accounting.md) | Your books, balanced | Chart of Accounts, banking, reports |
| 09 | [HRM](09-hrm.md) | Manage your people | Attendance, payroll, leaves, lifecycle |
| 10 | [POS](10-pos.md) | Sell in person, sync to books | Point-of-sale, barcodes, reports |
| 11 | [CRM](11-crm.md) | First contact to closed deal | Leads, deals, pipelines |
| 12 | [Media Library](12-media-library.md) | Every file, organized | Upload, organize, share media |
| 13 | [Messenger](13-messenger.md) | Instant team communication | Real-time chat with Pusher |
| 14 | [Helpdesk](14-helpdesk.md) | Every request tracked | Tickets, replies, resolution tracking |
| 15 | [Subscriptions](15-subscriptions.md) | Flexible plans, transparent billing | Plans, Stripe, PayPal, coupons |
| 16 | [Settings](16-settings.md) | Configure everything | Branding, email, storage, languages |

---

## Bonus Content

| File | Purpose |
|------|---------|
| [Homepage Hero](homepage-hero.md) | Headlines, value props, and CTAs for the landing page |
| [Email Onboarding](email-onboarding-sequence.md) | 5-email welcome sequence for new signups |
| [Competitor Comparison](competitor-comparison.md) | How Bojeri compares to spreadsheets and stitched tools |
| [Audit Report](_audit.md) | Codebase verification of every marketing claim |
