---
title: Helpdesk
tagline: Every request tracked, every issue resolved — support that never drops the ball.
menu_path: "Helpdesk"
controllers: HelpdeskTicketController.php, HelpdeskCategoryController.php, HelpdeskReplyController.php
last_reviewed: 2026-03-09
---

# Helpdesk
> Every request tracked, every issue resolved — support that never drops the ball.

## The Problem
Support requests buried in email threads, untracked issues, and no way to measure resolution times. When customers or team members need help, they send messages that get lost, duplicated, or forgotten. Without a ticketing system, you can't prioritize, you can't track, and you can't improve.

## How It Works
Bojeri's Helpdesk provides a complete internal and customer-facing ticketing system. Create **tickets** with a title, description, priority level, and category. Tickets follow a clear status lifecycle: **Open → In Progress → Resolved → Closed**. Assign tickets to **categories** — each category has a name, description, and color tag for visual organization. Team members respond through **replies** with full text messages and **file attachments** — replies can be marked as **internal** (visible only to support staff) or public (visible to the ticket creator). When a ticket is resolved, the system records the **resolved_at** timestamp for SLA tracking. Each ticket shows its complete history: original description, all replies with timestamps and author details, and status changes. Categories can be toggled active or inactive.

## Key Benefits
- **Track every request** with structured tickets that never get lost in email
- **Prioritize with levels** — set priority on each ticket to triage what gets resolved first
- **Categorize tickets** with color-coded categories for organized handling
- **Resolve with replies** — text messages and file attachments directly on the ticket
- **Mark replies as internal** to discuss issues privately before responding to the requester
- **Track resolution time** with automatic timestamps when tickets are resolved
- **Control status flow** — Open, In Progress, Resolved, Closed for clear lifecycle management
- **Attach files to replies** — share screenshots, documents, and evidence inline
- **Assign categories** for reporting and routing tickets to the right team

## Who This Is For
**Support Teams** — Manage, prioritize, and resolve tickets with full conversation history.
**Managers** — Monitor resolution times, identify patterns, and allocate support resources.
**Employees and Clients** — Submit requests and track progress with clear status updates.

## Real-World Example
An IT services company handles 40+ internal support requests per week. They create Helpdesk categories: "Hardware Issues" (red), "Software Bugs" (orange), "Access Requests" (blue), and "General Inquiries" (green). When an employee can't access the CRM, they submit a ticket: "Cannot login to CRM dashboard" with priority "High" under "Access Requests." The IT tech replies with an internal note: "Checked permissions — role was accidentally modified." Then adds a public reply: "Fixed! Please try logging in again." The ticket moves to "Resolved" and the system stamps the resolution time — 23 minutes from open to resolved. At month end, the IT manager sees that "Access Requests" average 18-minute resolution, while "Hardware Issues" average 2.4 hours — informing a decision to stock spare equipment.

## Features at a Glance
| Feature | What It Does |
|---------|-------------|
| Ticket Creation | Title, description, priority, and category assignment |
| Status Lifecycle | Open → In Progress → Resolved → Closed |
| Priority Levels | Prioritize tickets for triage and resource allocation |
| Category Management | Color-coded categories with descriptions and active/inactive status |
| Threaded Replies | Text responses and file attachments on each ticket |
| Internal Replies | Private notes visible only to support staff |
| File Attachments | Upload screenshots, documents, and evidence on replies |
| Resolution Tracking | Automatic timestamp when tickets are resolved for SLA measurement |
| Ticket History | Complete timeline of description, replies, and status changes |
| Paginated Listing | Sort triggers by status, priority, category, and date |
| Event System | Events fire on create, update, and delete for automation |
| Permission Controls | Separate permissions for creating, editing, viewing, and deleting tickets and replies |

## Frequently Asked Questions
**Q: Can clients submit helpdesk tickets?**
A: Yes. Any user with the "create-helpdesk-tickets" permission can submit tickets. You can assign this permission to client or external user roles.

**Q: Can I use internal replies to discuss privately before responding to the requester?**
A: Absolutely. Mark any reply as "internal" and it will only be visible to users with helpdesk management permissions — the ticket creator won't see it.

## Get Started
Stop losing support requests. Sign up at /register and start resolving issues with clarity and speed.
