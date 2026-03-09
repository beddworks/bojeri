---
title: Media Library
tagline: Every file, organized and accessible — your team's shared workspace for media.
menu_path: "Media Library"
controllers: MediaController.php
last_reviewed: 2026-03-09
---

# Media Library
> Every file, organized and accessible — your team's shared workspace for media.

## The Problem
Business files scattered across email attachments, personal drives, and desktop folders means no one can find what they need when they need it. Duplicate files multiply, old versions get used by mistake, and there's no way to organize files by project, department, or client. You need a central media hub that your whole team can access with proper controls.

## How It Works
Bojeri's Media Library provides a centralized file management system powered by Spatie MediaLibrary. Upload files individually or in **batch** — documents, images, videos, and any file type your business uses. Organize files into **directories** that you can create, rename, and nest to match your organizational structure. Move files between directories to keep everything tidy. Every file and directory supports granular permissions: manage, create, download, and delete — with separate controls for "own" media vs. "all" media. Team members see files appropriate to their role. The library supports multiple **storage backends** — local storage, AWS S3, or Wasabi — configurable through Settings. A custom path generator ensures files are organized on disk with clean, predictable paths.

## Key Benefits
- **Upload in batch** — drag and drop multiple files at once for fast uploads
- **Organize with directories** — create, rename, and nest folders to match your structure
- **Move files** between directories to keep your library organized as your needs evolve
- **Control access granularly** — manage, create, download, and delete permissions per role
- **Separate own vs. all** — users can manage their own files while admins manage everything
- **Store anywhere** — local, AWS S3, or Wasabi cloud storage backends
- **Support any file type** — configurable allowed file types and maximum upload sizes
- **Clean file paths** — custom path generator organizes storage automatically

## Who This Is For
**Team Leads** — Set up shared directories for projects, clients, or departments.
**Content Creators** — Upload, organize, and share media assets with the team.
**Administrators** — Configure storage backends and control who can access what.

## Real-World Example
An architecture firm uses Bojeri's Media Library to manage project deliverables. They create directories: "Active Projects / Sunset Tower / Renders" and "Active Projects / Sunset Tower / Floor Plans." The design team batch-uploads 30 renders from the latest review. The project manager moves finalized floor plans from "Drafts" to "Final." When a client needs specific renders, the firm sets their role to allow viewing and downloading from specific directories — without exposing internal drafts. Archived projects get their directories moved to "Completed" to keep the active library clean.

## Features at a Glance
| Feature | What It Does |
|---------|-------------|
| Batch Upload | Upload multiple files at once with drag-and-drop support |
| Directory Management | Create, rename, and nest directories for organized file storage |
| File Movement | Move files between directories to stay organized |
| Spatie MediaLibrary | Enterprise-grade file handling with media collections |
| Multiple Storage Backends | Local, AWS S3, or Wasabi cloud storage support |
| Configurable Limits | Set allowed file types and maximum upload sizes |
| Own vs. All Access | Users manage their own files; admins manage everything |
| Download Controls | Separate permission for file downloads |
| Custom Path Generator | Clean, predictable file organization on disk |
| Permission Controls | Granular access for media, directories, create, edit, delete |

## Frequently Asked Questions
**Q: What cloud storage services are supported?**
A: Currently, Bojeri supports local storage, AWS S3, and Wasabi. You can configure your preferred storage backend in Settings with your access keys, bucket, and region.

**Q: Is there a file size limit?**
A: Yes, but you control it. The maximum upload size is configurable in Settings, so you can set it based on your storage plan and needs.

## Get Started
Give your team a single source of truth for every file. Sign up at /register and start organizing your media library.
