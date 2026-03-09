# Codebase Marketing Audit

Generated: 2026-03-09
Source: Full codebase reconnaissance of app/ + packages/workdo/

---

## ✅ Confirmed Features

| Feature | Source File | Method / Column |
|---------|-------------|-----------------|
| Super Admin Dashboard w/ revenue chart | HomeController.php | superAdminDashboard() — Order::selectRaw monthly |
| Five dashboard views (Account, Project, HRM, POS, CRM) | HomeController.php | regularDashboard() — dynamic package menu detection |
| Spatie Permission roles & permissions | RoleController.php | create/store/edit/update/destroy + Permission model |
| Built-in Staff/Client/Vendor roles | User.php model | MakeRole() static method |
| User CRUD with role assignment | UserController.php | index/store/update/destroy |
| User impersonation | UserController.php | impersonate() / leaveImpersonation() |
| Login history with IP/browser | UserController.php | loginHistory() + LoginHistory model |
| Sales Proposals (CRUD) | SalesProposalController.php | index/store/update/destroy |
| Proposal status: Draft→Sent→Accepted→Rejected | SalesProposal migration | enum('status', ['draft','sent','accepted','rejected']) |
| Proposal→Invoice conversion | SalesProposalController.php | convertToInvoice() |
| Proposal print view | SalesProposalController.php | print() |
| Warehouse-aware product selection | SalesProposalController.php | getWarehouseProducts() |
| Sales Invoices (CRUD) | SalesInvoiceController.php | index/store/update/destroy |
| Invoice status: Draft→Posted | SalesInvoice migration | enum('status', ['draft','posted']) |
| Invoice balance tracking | SalesInvoice migration | balance_amount column |
| Invoice print view | SalesInvoiceController.php | print() |
| Sales Invoice Returns | SalesReturnController.php | full CRUD + approve/complete |
| Return status: Draft→Approved→Completed→Cancelled | SalesInvoiceReturn migration | enum('status', 4 values) |
| Purchase Invoices (CRUD) | PurchaseInvoiceController.php | index/store/update/destroy/post |
| Purchase status: Draft→Posted | PurchaseInvoice migration | enum('status', ['draft','posted']) |
| Purchase Returns | PurchaseReturnController.php | store/approve/complete/destroy |
| Purchase return auto debit note | PurchaseReturnController.php | approve() flash message confirms |
| Warehouses (CRUD) | WarehouseController.php | index/store/update/destroy |
| Warehouse fields: name,address,city,zip,phone,email,is_active | Warehouse migration | All columns confirmed |
| Stock Transfers | TransferController.php | CRUD + approve/complete |
| Transfer status: Draft→Approved→Completed→Cancelled | Transfer migration | enum('status', 4 values) |
| Products & Services items | ProductServiceItemController.php | CRUD with sku, prices, taxes, units |
| Product categories | CategoryController.php | CRUD in ProductService package |
| Tax configuration | TaxController.php | CRUD with tax_name, rate |
| Unit management | UnitController.php | CRUD in ProductService package |
| Warehouse stock tracking | WarehouseStock model | warehouse_id + product_id + quantity |
| Projects (CRUD + duplicate) | ProjectController.php (Taskly) | index/store/update/destroy/duplicate |
| Project status: not_started/in_progress/on_hold/completed/cancelled | Project migration | enum column with 5 values |
| Tasks with priority/stages/milestones | ProjectTaskController.php | CRUD + kanban + calendar + move |
| Task subtasks | ProjectTaskController.php | storeSubtask/toggleSubtask |
| Task comments | ProjectTaskController.php | storeComment/destroyComment |
| Bug tracker | ProjectBugController.php | CRUD + kanban + move + comments |
| Custom task/bug stages | TaskStageController/BugStageController | CRUD + reorder |
| Project files | ProjectController.php | storeFiles/deleteFile |
| Project reports | ProjectReportController.php | index/show |
| Project invitations (members + clients) | ProjectController.php | invite/inviteClient |
| Activity logs | ProjectActivityLog model | user_id, project_id, log_type, remark |
| Accounting module (full) | Account package | Controllers for all accounting features |
| HRM - Employees | EmployeeController.php (Hrm) | CRUD |
| HRM - Attendance with clock in/out | AttendanceController.php (Hrm) | clockIn/clockOut/calculateTotalHours |
| HRM - Overtime calculation | AttendanceController.php | calculateAttendanceData() |
| HRM - Night shift support | AttendanceController.php | is_night_shift handling in calculations |
| HRM - IP restriction | AttendanceController.php | ip_restrict setting check |
| HRM - Shifts with breaks | Shift model + migration | start_time, end_time, break_start_time, break_end_time |
| HRM - Leave management | LeaveController (Hrm) | Types, applications, balances |
| HRM - Payroll/Payslips | PayrollController (Hrm) | Processing and generation |
| HRM - Awards/Promotions/Warnings etc. | Hrm package controllers | Full CRUD for each lifecycle event |
| POS module | PosController.php | createPos + order management |
| POS barcode printing | POS package routes | barcode routes confirmed |
| CRM - Leads | LeadController.php (Lead) | CRUD with stages and assignments |
| CRM - Deals | DealController.php (Lead) | CRUD with pipeline stages |
| CRM - Lead stages: Draft→Sent→Open→Revised→Declined→Accepted | LeadUtility.php | defaultdata() method |
| CRM - Deal stages: Initial Contact→Qualification→Meeting→Proposal→Close | LeadUtility.php | defaultdata() method |
| CRM - Pipelines | Pipeline model | CRUD with default "Sales" pipeline |
| CRM - Sources | Source model | CRUD for lead origin tracking |
| CRM - Deal tasks | Deal routes | task create/edit/delete routes |
| CRM - Reports (Lead + Deal) | Lead package routes | Report controller routes confirmed |
| Media Library with Spatie | MediaController.php | page/index/batchStore/destroy |
| Media directories | MediaController.php | createDirectory/updateDirectory/destroyDirectory |
| Media batch upload | MediaController.php | batchStore() |
| Cloud storage: Local/S3/Wasabi | SettingController.php | updateStorageSettings() + StorageConfigService |
| Messenger real-time | MessengerController.php | send() + Pusher event |
| Messenger file attachments | MessengerController.php | attachment handling in send() |
| Messenger favorites/pins | MessengerController.php | toggleFavorite/togglePin |
| Messenger online presence | MessengerController.php | updatePresence/getOnlineUsers + Cache |
| Messenger edit/delete messages | MessengerController.php | editMessage/deleteMessage |
| Messenger pagination | MessengerController.php | getMessages() with pagination |
| Helpdesk tickets (CRUD) | HelpdeskTicketController.php | index/store/show/update/destroy |
| Helpdesk ticket status: open→in_progress→resolved→closed | HelpdeskTicket migration | enum('status', 4 values) |
| Helpdesk priorities | HelpdeskTicket migration | priority column |
| Helpdesk categories with color | HelpdeskCategoryController.php | color field in store/update |
| Helpdesk replies with attachments | HelpdeskReplyController.php | store() with attachments handling |
| Helpdesk internal replies | HelpdeskReplyController.php | is_internal boolean |
| Resolution timestamp | HelpdeskTicketController.php | resolved_at set on status='resolved' |
| Subscription Plans (CRUD) | PlanController.php | index/store/update/destroy |
| Plan modules/users/storage limits | Plan model + migration | modules, number_of_users, storage_limit |
| Free plans + trials | PlanController.php | assignFreePlan/startTrial |
| Stripe payments | Stripe package | StripeController + settings |
| PayPal payments | PayPal package | PayPal routes + controller |
| Bank transfer payments | BankTransferPaymentController.php | store/update/reject/destroy |
| Orders tracking | OrderController.php | index + Order model |
| Coupons system | CouponController.php | CRUD with type/limit/expiry/module rules |
| Settings: branding | SettingController.php | updateSettings() — company_name, logo, favicon |
| Settings: email/SMTP | SettingController.php | updateEmailSettings/testEmail |
| Settings: cookie consent | SettingController.php | logCookieConsent/downloadCookieData |
| Settings: SEO | SettingController.php | updateSeoSettings |
| Settings: Pusher | SettingController.php | updatePusherSettings |
| Email templates | EmailTemplateController.php | CRUD with multi-language |
| Notification templates | NotificationTemplateController.php | CRUD with multi-language |
| Language management | TranslationController.php | manage/create/update/toggle/delete |
| Package translations | TranslationController.php | getPackageTranslations/updatePackageTranslations |
| Google 2FA | composer.json | pragmarx/google2fa-laravel |
| Social login | composer.json | laravel/socialite |
| CAPTCHA | composer.json | anhskohbo/no-captcha |
| QR codes | composer.json | bacon/bacon-qr-code |
| ZATCA e-invoicing | composer.json | salla/zatca |
| Twilio SMS | composer.json | twilio/sdk |
| Mailchimp integration | composer.json | mailchimp/marketing |
| Google Calendar | composer.json | spatie/laravel-google-calendar |
| IMAP email reading | composer.json | webklex/laravel-imap |
| Event-driven architecture | app/Events/ | 35+ event classes for all major actions |
| Multi-tenant (created_by pattern) | Helper.php | creatorId() function |
| Module activation per user/plan | Helper.php | ActivatedModule/Module_is_active |
| Inertia.js + Ziggy stack | composer.json | inertiajs/inertia-laravel + tightenco/ziggy |

---

## ⚠️ Flagged Claims (needs verification)

| File | Claim | Reason |
|------|-------|--------|
| 09-hrm.md | "Payslip Generation" | PayrollController exists in HRM package but full payslip generation code not individually verified |
| 10-pos.md | "Every sale updates warehouse stock in real time" | POS creates orders but stock deduction logic may be in package events not directly inspected |
| 10-pos.md | "Daily, weekly, monthly sales analytics" | POS Reports route exists but report granularity not individually verified |
| 08-accounting.md | "Double-entry bookkeeping" | Account package exists with full controller set but individual journal entry mechanics not line-by-line verified |
| 08-accounting.md | "Profit and Loss, Balance Sheet reports" | Account package has report routes but specific report types not individually confirmed |
| homepage-hero.md | Social proof testimonials | Placeholder content — marked as such in the file |

---

## 💎 Hidden Gems (in code, not yet prioritized in marketing)

| Feature | Source | Marketing Opportunity |
|---------|--------|----------------------|
| ZATCA e-invoicing compliance | composer.json: salla/zatca | Saudi Arabia market landing page |
| Twilio SMS integration | composer.json: twilio/sdk | SMS notification feature highlight |
| Mailchimp integration | composer.json: mailchimp/marketing | Email marketing connection highlight |
| Google Calendar sync | composer.json: spatie/laravel-google-calendar | Calendar integration landing page |
| IMAP email reading | composer.json: webklex/laravel-imap | Email-to-ticket or email sync feature |
| Microsoft Graph integration | composer.json: microsoft/microsoft-graph | Microsoft 365 integration highlight |
| Google OAuth login | composer.json: google/apiclient | Google workspace integration |
| Meta/Facebook webhook | MetaController.php | Social media chat integration |
| Auto-installer | InstallerController.php | Self-hosted deployment simplicity |
| Auto-updater | UpdaterController.php | One-click platform updates |
| LandingPage package | packages/workdo/LandingPage | Built-in marketing page builder |
| Demo seeders | All packages | Quick-start demo data for new signups |

---

## 📋 Recommended Next Content

1. **Role-specific landing pages** — Separate pages for CFOs, HR Managers, Sales Teams, and IT Admins
2. **Integration guides** — Stripe setup, PayPal configuration, AWS S3 storage, Pusher real-time
3. **API documentation** — REST API endpoints documented in api.php with Sanctum auth
4. **Video scripts** — Screen recordings for each module's key workflow
5. **Case studies** — Template for customer success stories (using the real-world examples as a base)
6. **ZATCA compliance page** — Dedicated landing page for Saudi/GCC market
7. **Security whitepaper** — 2FA, Spatie permissions, IP restriction, CAPTCHA, data isolation
8. **Migration guide** — How to move from spreadsheets or QuickBooks to Bojeri
9. **Module comparison page** — Interactive tool showing which modules each plan includes
10. **Developer documentation** — Event system, package development, and extensibility guide
