---
title: User Management
tagline: The right people, the right access — every time.
menu_path: "User Management → Roles / Users"
controllers: UserController.php, RoleController.php
last_reviewed: 2026-03-09
---

# User Management
> The right people, the right access — every time.

## The Problem
When your team grows beyond a handful of people, managing who can see what becomes a nightmare. Shared logins, vague permissions, and no audit trail mean sensitive data is one wrong click away from the wrong person. You need control without complexity — a way to onboard teammates, set boundaries, and sleep well at night.

## How It Works
Bojeri's User Management lets you create team members with defined roles and granular permissions. Start by setting up **Roles** — each role gets a custom name, label, and a precise set of permissions chosen from your active modules. Then create **Users** and assign them a role. The system ships with three built-in roles — **Staff**, **Client**, and **Vendor** — each pre-loaded with sensible default permissions. Staff can manage their profile and media; Clients can view invoices and proposals; Vendors can manage purchase invoices. You can create unlimited custom roles with exactly the permissions you need. Every user action is gated by Spatie permission checks, so nobody sees or does more than they should.

## Key Benefits
- **Create custom roles** with hand-picked permissions from every active module in your workspace
- **Assign granular permissions** at the feature level — view, create, edit, delete, print, and approve are all separate controls
- **Onboard users instantly** with validated email, phone, and role assignment in one form
- **Track login history** with IP address, browser, timestamp, and role filters for complete audit trails
- **Impersonate users** to troubleshoot issues from their perspective without asking for their password
- **Manage passwords securely** — administrators can reset any team member's password with proper authorization
- **Filter and search** users by name, email, role, or type with sortable, paginated tables
- **Protect system roles** — built-in Staff, Client, and Vendor roles are locked from accidental deletion

## Who This Is For
**Business Owners** — Control exactly who accesses financial data, HR records, and client information.
**IT Administrators** — Set up and manage team access without writing code or editing config files.
**Team Leads** — Onboard new team members with pre-built role templates in under two minutes.

## Real-World Example
Marco's e-commerce company hires a new sales coordinator, Priya. He opens User Management, creates her account with name, email, and phone, and assigns her the "Sales Team" role he created earlier — which grants access to Sales Invoices, Proposals, and CRM but blocks Accounting and HRM. Priya logs in and sees only what she needs. When Marco notices Priya can't find a particular report, he uses the impersonate feature to log in as her, sees the issue (missing permission), adds it to her role, and leaves impersonation — all without ever needing her password.

## Features at a Glance
| Feature | What It Does |
|---------|-------------|
| Role Builder | Create unlimited custom roles with per-permission control |
| Built-in Roles | Staff, Client, and Vendor roles pre-configured with sensible defaults |
| Permission Sync | Assign or revoke permissions in bulk when editing a role |
| User Creation | Add users with name, email, phone, password, and role in one form |
| Login History | View every login with IP, browser, timestamp, and role filters |
| User Impersonation | Log in as any user to see their view — without their password |
| Password Management | Securely reset any team member's password |
| Sortable Tables | Filter users by name, email, role, or type with paginated results |
| Module-Aware Permissions | Permissions automatically reflect your active modules |
| Role Protection | System roles (Staff, Client, Vendor) are protected from deletion |

## Frequently Asked Questions
**Q: Is there a limit to how many roles I can create?**
A: No. Create as many custom roles as your organization needs. Each role can have a unique combination of permissions from all active modules.

**Q: Can clients and vendors log in to the platform?**
A: Yes. Clients can view their invoices and proposals; Vendors can view their purchase invoices. Both have their own pre-configured roles with appropriate permissions.

## Get Started
Take control of who sees what in your workspace. Sign up at /register and start building your team with confidence.
