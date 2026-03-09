---
title: Sales Invoice
tagline: From draft to paid — track every dollar with precision.
menu_path: "Sales Invoice → Sales Invoice / Sales Invoice Returns"
controllers: SalesInvoiceController.php, SalesReturnController.php
last_reviewed: 2026-03-09
---

# Sales Invoice
> From draft to paid — track every dollar with precision.

## The Problem
Manual invoicing means typos, missed line items, and no easy way to track what's been paid or what's overdue. When a client disputes a charge or returns goods, you're scrambling through email threads and spreadsheets to reconcile. You need a system that handles the full invoice lifecycle — creation, posting, payments, and returns — without the chaos.

## How It Works
Create a Sales Invoice by selecting a customer, choosing products from your catalog, and adding line items with quantities, prices, per-item discounts, and multiple tax rates. The system auto-generates an invoice number and calculates everything — subtotals, total discounts, total taxes, and the final amount due. Invoices follow a **Draft → Posted** lifecycle. While in Draft, you can freely edit; once Posted, the invoice is locked for integrity. Track the balance amount separately from the total to see outstanding payments at a glance. Need to handle returns? Create a **Sales Invoice Return** linked to the original invoice, specifying which items and quantities are being returned with full reason tracking. Returns follow their own **Draft → Approved → Completed → Cancelled** lifecycle with automatic calculations.

## Key Benefits
- **Auto-calculate everything** — subtotals, per-item discounts, multi-rate taxes, and balance amounts
- **Track invoice status** through a clear Draft → Posted lifecycle with edit protection on posted invoices
- **Handle returns elegantly** with linked Sales Invoice Returns that reference original line items
- **Print invoices** with professional, print-ready layouts including all customer and item details
- **Manage balance tracking** — total amount vs. balance amount shows outstanding payments instantly
- **Apply named tax rates** per line item for full tax compliance across jurisdictions
- **Filter by status, customer, date** with sortable, paginated invoice tables
- **Protect data integrity** — posted invoices cannot be edited or deleted

## Who This Is For
**Finance Teams** — Create, post, and track invoices with built-in balance and payment tracking.
**Sales Managers** — Monitor outstanding balances and overdue invoices across all customers.
**Clients** — View their invoices and returns through the client portal with appropriate permissions.

## Real-World Example
Ahmad runs a wholesale electronics business. He creates a Sales Invoice for a retailer: 100 USB cables at $5 each with a 3% discount, and 50 power adapters at $12 each with 10% VAT. The system calculates everything instantly — $485 for cables, $660 for adapters with tax, total $1,145. He posts the invoice to lock it. A week later, the retailer returns 10 defective cables. Ahmad creates a Sales Invoice Return linked to the original, selects the cable line item, enters 10 as the return quantity with "Defective units" as the reason. The return goes through Draft → Approved → Completed, and his records stay perfectly clean.

## Features at a Glance
| Feature | What It Does |
|---------|-------------|
| Invoice Builder | Multi-line invoices with products, quantities, prices, and taxes |
| Auto-Numbering | Unique, sequential invoice numbers generated automatically |
| Draft/Posted Status | Edit freely in Draft; lock for integrity when Posted |
| Balance Tracking | Separate total vs. balance fields for payment tracking |
| Sales Returns | Create returns linked to original invoices with item-level detail |
| Return Lifecycle | Returns follow Draft → Approved → Completed → Cancelled |
| Per-Item Taxes | Named tax rates applied per line with automatic calculations |
| Per-Item Discounts | Percentage-based discounts calculated before tax |
| Print View | Professional invoice layout ready for printing or sharing |
| Warehouse Integration | Select products from specific warehouses |
| Customer Event Notifications | Events fire on create, update, post, and return actions |
| Payment Terms & Notes | Attach payment terms and internal notes to every invoice |
| Role-Based Access | Separate permissions for create, edit, view, print, post, delete |

## Frequently Asked Questions
**Q: Can I edit an invoice after it's been posted?**
A: No. Posted invoices are locked to protect financial integrity. If you need to adjust, create a Sales Invoice Return for the correction and issue a new invoice.

**Q: How do returns affect my financials?**
A: Sales Invoice Returns are tracked separately with their own approval workflow. When approved, they create a clear paper trail linked back to the original invoice.

## Get Started
Stop chasing payments through spreadsheets. Sign up at /register and create your first professional invoice in minutes.
