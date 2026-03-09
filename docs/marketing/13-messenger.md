---
title: Messenger
tagline: Instant team communication — right inside your workspace.
menu_path: "Messenger"
controllers: MessengerController.php
last_reviewed: 2026-03-09
---

# Messenger
> Instant team communication — right inside your workspace.

## The Problem
Switching between your ERP and a separate chat tool breaks your flow. Context gets lost when conversations about invoices, projects, and tasks happen in one app while the actual work happens in another. You need messaging that lives where your business lives — with the same people, the same permissions, and zero app-switching.

## How It Works
Bojeri Messenger is a real-time messaging system built directly into your workspace, powered by **Pusher** for instant message delivery. Open Messenger to see your **contacts** — automatically populated from your team based on permissions. Send **text messages** and **file attachments** with real-time delivery. Messages show **read receipts** (seen/unseen) so you know when your message has been viewed. **Edit** or **delete** messages you've sent — deleted messages are tracked separately for sender and receiver. Mark contacts as **favorites** for quick access to your most frequent conversations. **Pin** important conversations so they stay at the top. See who's **online** with real-time presence indicators powered by cache-based status tracking. The system supports **pagination** for message history — scroll back to load older messages without loading everything at once.

## Key Benefits
- **Message in real time** with Pusher-powered instant delivery — no page refreshes needed
- **Share files** by attaching documents, images, and other files directly in conversations
- **See who's online** with live presence indicators that update automatically
- **Track read status** with seen/unseen indicators on every message
- **Edit sent messages** to correct typos or update information
- **Delete messages** with separate tracking for sender and receiver
- **Favorite contacts** for one-click access to your most frequent conversations
- **Pin conversations** to keep important threads at the top of your list
- **Access from workspace** — no separate app or login required

## Who This Is For
**Team Members** — Chat with colleagues without leaving the workspace where you're already doing work.
**Managers** — Quickly reach team members with questions about invoices, projects, or tasks.
**Remote Teams** — Stay connected with real-time presence and instant messaging across locations.

## Real-World Example
A consulting firm's project manager spots a discrepancy in a Sales Invoice. Instead of switching to Slack, finding the right channel, and explaining the context, she opens Messenger — right there in Bojeri — and messages the finance coordinator. She attaches a screenshot of the invoice, types "Line 3 tax rate should be 10%, not 12% — can you check?", and sends. The coordinator sees the notification instantly (Pusher real-time), opens the message, sees the attachment, and replies within minutes. The conversation is pinned for follow-up, and the manager marks the coordinator as a favorite for future quick access.

## Features at a Glance
| Feature | What It Does |
|---------|-------------|
| Real-Time Messaging | Instant delivery powered by Pusher WebSockets |
| File Attachments | Send documents, images, and files within conversations |
| Online Presence | Live indicators showing who's online right now |
| Read Receipts | Seen/unseen status on every message |
| Message Editing | Edit sent messages to correct or update content |
| Message Deletion | Delete messages with separate sender/receiver tracking |
| Favorite Contacts | Mark frequently messaged people for quick access |
| Pinned Conversations | Keep important threads at the top of your list |
| Contact List | Auto-populated from your team based on permissions |
| Message Pagination | Load older messages as needed without performance impact |
| Permission Controls | Separate permissions for send, view, edit, delete, favorite, pin |

## Frequently Asked Questions
**Q: Do I need to set up Pusher separately?**
A: Yes, you'll need a Pusher account. Enter your Pusher App ID, Key, Secret, and Cluster in Settings, and real-time messaging activates automatically.

**Q: Can I message people outside my team?**
A: Messenger connects you with users in your workspace based on permissions. External contacts would need a user account to receive messages.

## Get Started
Stop switching apps to talk to your team. Sign up at /register and start messaging from inside your workspace.
