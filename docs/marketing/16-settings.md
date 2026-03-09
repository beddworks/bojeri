---
title: Settings
tagline: Configure everything — your brand, your email, your storage, your way.
menu_path: "Settings"
controllers: SettingController.php, EmailTemplateController.php, NotificationTemplateController.php, TranslationController.php
last_reviewed: 2026-03-09
---

# Settings
> Configure everything — your brand, your email, your storage, your way.

## The Problem
Every business is different — different branding, different currencies, different email providers, different compliance requirements. A rigid system forces you into someone else's workflow. You need a control center where you can tailor the platform to your exact requirements without touching code.

## How It Works
Bojeri's Settings module is your platform's control center. Configure your **brand** with company name, logo, and favicon, plus choose your **theme** mode and color scheme. Set **system preferences** — default language, date format, time format, and calendar start day. Define your **currency** with symbol, format, decimal separator, thousands separator, and symbol position. Set up your **email** provider (SMTP, Mailgun, SendGrid, etc.) with full SMTP configuration and test email functionality. Configure **storage** across local, AWS S3, or Wasabi backends with access keys, buckets, and regions. Manage **cookie consent** with GDPR-compliant consent logging. Configure **Pusher** for real-time messaging. Set up **bank transfer** payment settings with custom instructions. Manage **email templates** with multi-language support — each template has a subject, body, and placeholders you can customize per language. Configure **notification templates** similarly. The **language management** system lets you create new languages, toggle status, and edit translation strings for complete localization — including package-level translations. Configure **SEO** settings for public-facing pages.

## Key Benefits
- **Brand your workspace** with custom company name, logo, favicon, theme, and colors
- **Set your currency** with full format control — symbol, position, decimals, separators
- **Configure email delivery** with SMTP settings and live test email functionality
- **Choose your storage** — local, AWS S3, or Wasabi with configurable access and region
- **Manage cookie consent** with GDPR-compliant logging and downloadable consent data
- **Customize email templates** per language with dynamic placeholders
- **Localize fully** — create languages, edit translations, and manage per-package strings
- **Configure Pusher** for real-time messaging with app-specific credentials
- **Set up bank transfers** with custom payment instructions
- **Optimize for SEO** with configurable meta tags and settings for public pages
- **Control email notifications** — toggle which events trigger email notifications

## Who This Is For
**Platform Administrators** — Configure every aspect of the platform from a single control center.
**Marketing Teams** — Customize branding, SEO, and email templates without developer support.
**International Businesses** — Localize the entire platform with multi-language management.

## Real-World Example
A logistics company expanding to three countries configures Bojeri for their needs. The admin sets the logo, picks a dark theme, and configures currency as Euro (€) with comma decimal separator. They set up SMTP with SendGrid, send a test email to verify delivery, and then customize the "Invoice Created" email template in English, French, and Arabic. They create all three languages, edit translation strings so every button and label reads naturally, and toggle French and Arabic as active languages. For file storage, they configure AWS S3 with their bucket in eu-west-1. Finally, they enable cookie consent, set SEO meta tags, and configure Pusher credentials for the Messenger. One afternoon of setup, and the platform is fully tailored for three markets.

## Features at a Glance
| Feature | What It Does |
|---------|-------------|
| Brand Settings | Company name, logo, favicon, theme mode, and color scheme |
| System Preferences | Default language, date format, time format, calendar start day |
| Currency Configuration | Symbol, format, decimals, separators, position |
| Email Configuration | SMTP settings with provider selection and test email |
| Storage Backends | Local, AWS S3, or Wasabi with full credential management |
| Cookie Consent | GDPR-compliant consent logging and downloadable consent CSV |
| Email Templates | Multi-language templates with customizable subjects and placeholders |
| Notification Templates | Configurable notification content per language |
| Language Management | Create, toggle, and edit language translations |
| Package Translations | Edit translations specific to individual modules/packages |
| Pusher Configuration | App ID, Key, Secret, and Cluster for real-time messaging |
| Bank Transfer Settings | Enable/disable with custom payment instructions |
| SEO Settings | Meta tags and SEO configuration for public pages |
| Email Notification Toggles | Control which events trigger email notifications |
| Permission Controls | Granular permissions for each settings category |

## Frequently Asked Questions
**Q: Can I have different settings for different companies on the platform?**
A: Yes. Company-level settings are scoped to each company — each company can have its own branding, currency, language preferences, and module configurations. Platform-wide settings (like payment gateway credentials and storage) are managed by the Super Admin.

**Q: How does the multi-language system work?**
A: You can create any language, edit frontend and backend translation strings, and manage per-package translations. Users can switch languages from their profile, and the interface immediately reflects the selected language.

## Get Started
Make the platform yours. Sign up at /register and configure your workspace the way your business needs it.
