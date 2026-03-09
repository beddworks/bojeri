---
title: Purchase
tagline: Control what comes in — every vendor, every warehouse, every return.
menu_path: "Purchase → Purchase Invoice / Purchase Returns / Warehouses / Transfers"
controllers: PurchaseInvoiceController.php, PurchaseReturnController.php, WarehouseController.php, TransferController.php
last_reviewed: 2026-03-09
---

# Purchase
> Control what comes in — every vendor, every warehouse, every return.

## The Problem
Purchase management falls apart when invoices live in email attachments, warehouse stock is tracked in separate spreadsheets, and returns require a chain of phone calls to process. You can't see what you've bought, where it's stored, or whether your vendors delivered what they promised. Blind purchasing leads to overstocking, cash flow problems, and supplier disputes.

## How It Works
Bojeri's Purchase module covers the full procurement cycle across four integrated tools. **Purchase Invoices** let you record vendor purchases with multi-line items, taxes, discounts, and a Draft → Posted lifecycle. **Purchase Returns** handle goods sent back to vendors, linked to the original invoice with item-level return quantities and reasons — following a Draft → Approved → Completed flow that auto-creates debit notes on approval. **Warehouses** give you named storage locations with address, city, phone, and email, each with active/inactive status. **Transfers** move stock between warehouses with a Draft → Approved → Completed → Cancelled lifecycle, tracking source warehouse, destination warehouse, and per-item quantities.

## Key Benefits
- **Track purchases end-to-end** from vendor invoice creation through posting with locked data integrity
- **Handle returns properly** with linked returns that reference original invoice items and auto-create debit notes
- **Manage multiple warehouses** with full address details and active/inactive status controls
- **Transfer stock between locations** with an approval workflow that prevents unauthorized movements
- **Auto-calculate purchase totals** including per-item discounts, multi-rate taxes, and balance amounts
- **Print purchase invoices** with vendor details, item breakdown, and professional layout
- **Control access granularly** with separate permissions for create, edit, view, print, post, approve, and delete
- **Filter everything** by vendor, status, date, and warehouse with sortable, paginated tables

## Who This Is For
**Procurement Managers** — Create purchase invoices, track vendor deliveries, and process returns from one place.
**Warehouse Managers** — Manage storage locations and approve stock transfers between warehouses.
**Vendors** — View purchase invoices shared with them through the vendor portal with read-only access.

## Real-World Example
A restaurant chain uses Bojeri to manage supplies across three kitchens. The procurement manager creates a Purchase Invoice for 500kg of rice from their grain supplier at $2/kg, assigned to "Main Warehouse." After receiving the shipment, she posts the invoice. The next day, the Uptown kitchen needs 100kg. She creates a Transfer from Main Warehouse to Uptown Kitchen — the warehouse manager approves it, and stock records update across both locations. When 50kg of rice arrives spoiled from another vendor order, she creates a Purchase Return linked to the original invoice, specifying "Quality issue — spoiled goods." On approval, the system automatically flags a debit note.

## Features at a Glance
| Feature | What It Does |
|---------|-------------|
| Purchase Invoice Builder | Multi-line invoices with vendor, items, taxes, and discounts |
| Draft → Posted Lifecycle | Edit freely in Draft; lock data integrity when Posted |
| Purchase Returns | Linked returns with per-item quantities, reasons, and approval flow |
| Auto Debit Notes | Approved returns automatically create debit notes |
| Return Lifecycle | Returns follow Draft → Approved → Completed |
| Warehouse Management | Named locations with address, city, phone, email, and status |
| Stock Transfers | Move items between warehouses with per-item quantities |
| Transfer Lifecycle | Transfers follow Draft → Approved → Completed → Cancelled |
| Print Invoices | Professional purchase invoice print view with vendor details |
| Vendor Portal Access | Vendors can view purchase invoices assigned to them |
| Balance Tracking | Track total vs. balance amounts on every purchase invoice |
| Event System | Events fire on create, update, post, approve, and delete actions |
| Module Permissions | Granular access control for every purchase action |

## Frequently Asked Questions
**Q: Can I restrict who approves transfers between warehouses?**
A: Yes. Transfer approval requires the specific "approve-transfers" permission, which you can assign to warehouse managers or supervisors through the Role builder.

**Q: What happens when I approve a purchase return?**
A: The return status moves to "Approved" and a debit note is automatically created. You can then mark it as "Completed" once the vendor has processed the return.

## Get Started
Take control of your procurement today. Sign up at /register and start tracking every purchase from vendor to warehouse.
