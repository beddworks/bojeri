---
title: Subscriptions
tagline: Flexible plans, transparent billing — grow your platform with confidence.
menu_path: "Plan → Setup Subscription Plan / Bank Transfer Requests / Orders"
controllers: PlanController.php, OrderController.php, BankTransferPaymentController.php, CouponController.php
last_reviewed: 2026-03-09
---

# Subscriptions
> Flexible plans, transparent billing — grow your platform with confidence.

## The Problem
Monetizing a SaaS platform means juggling subscriptions, payment gateways, trial periods, coupons, and upgrade paths — all while keeping the experience smooth for your customers. If your billing is confusing, customers hesitate. If your plans are rigid, you lose business to competitors with more options.

## How It Works
Bojeri's Subscription system powers the commercial side of your platform. Create **Plans** with customizable pricing — monthly and annual durations, user limits, storage limits, and selectable feature modules. Toggle plans as **free plans** or paid, and enable **trial periods** for prospects to test before committing. Payments process through **Stripe** and **PayPal** integrations with live API connections, or through **Bank Transfer** with a manual approval workflow (submit → review → approve/reject). Every payment generates an **Order** record with order ID, plan name, price, currency, payment type, payment status, and transaction details. Apply **Coupons** with discount codes, percentage or fixed amount types, usage limits, per-user limits, minimum/maximum spend thresholds, expiry dates, and module inclusion/exclusion rules. Track coupon usage through **User Coupon** records. The platform handles plan changes, upgrades, and module activation automatically.

## Key Benefits
- **Create flexible plans** with monthly/annual pricing, user limits, storage quotas, and feature modules
- **Offer free trials** — let prospects explore the platform before committing to a paid plan
- **Accept payments via Stripe** with live API integration and automatic order generation
- **Accept payments via PayPal** with seamless checkout and transaction tracking
- **Support bank transfers** with a manual approval workflow for enterprise clients
- **Apply coupon codes** with percentage or fixed discounts, usage limits, and expiry dates
- **Track every order** with complete payment records including status, amounts, and gateway details
- **Activate modules per plan** — each plan defines which ERP modules are available
- **Manage user and storage limits** per plan for resource control
- **Review bank transfer requests** with approve/reject workflow and receipt uploads

## Who This Is For
**Platform Owners** — Design and manage subscription tiers that match your market strategy.
**Finance Teams** — Track revenue from subscriptions, reconcile payments, and manage coupon campaigns.
**Customers** — Choose a plan, start a trial, apply coupons, and pay through their preferred method.

## Real-World Example
A SaaS company launches Bojeri with three plans: "Starter" ($29/month, 5 users, basic modules), "Professional" ($79/month, 25 users, all modules), and "Enterprise" (custom pricing via bank transfer). They create a "LAUNCH30" coupon — 30% off for the first month, limited to 100 uses, minimum $50 spend, valid until month end. A prospect starts a free trial on the Professional plan, explores HRM and CRM modules for 14 days, then upgrades with the coupon code — paying $55.30 via Stripe. The order record shows plan details, discount applied, payment status "succeeded," and Stripe transaction ID. An enterprise client submits a bank transfer for the annual plan — the finance team reviews the receipt upload and approves the payment, activating the account.

## Features at a Glance
| Feature | What It Does |
|---------|-------------|
| Plan Builder | Create plans with name, price, duration, user limits, and modules |
| Monthly & Annual Pricing | Offer both billing cycles with separate pricing |
| Free Plans | Provide zero-cost plans with limited modules |
| Trial Periods | Let prospects test the platform before paying |
| Stripe Integration | Accept credit card payments with live API connection |
| PayPal Integration | Accept PayPal payments with seamless checkout |
| Bank Transfer | Manual payment with receipt upload and approve/reject workflow |
| Order Tracking | Complete records with order ID, plan, price, status, and transaction |
| Coupon System | Discount codes with type, limits, thresholds, and expiry |
| Module Activation | Plans control which ERP modules are available |
| User Limits | Set maximum users per plan for resource management |
| Storage Limits | Set storage quotas per plan |
| Coupon Usage Tracking | Monitor which users applied which coupons on which orders |
| Plan Assignment | Automatic module and limit activation on subscription |

## Frequently Asked Questions
**Q: Can I offer different modules on different plans?**
A: Yes. Each plan defines which modules (Accounting, HRM, CRM, POS, etc.) are available. When a user subscribes, only their plan's modules are activated.

**Q: How does bank transfer payment work?**
A: Customers submit a bank transfer request with their payment receipt. Your finance team reviews the request and approves or rejects it. On approval, the plan is activated automatically.

## Get Started
Build a subscription model that grows with you. Sign up at /register and configure your first plan today.
