# 🏥 Multi-Campus Hospital Accounting & Receipt Management System

A multi-role accounting web application built with Laravel and MySQL to manage multi-branch patient billing, voucher disbursements, and real-time executive financial statements.

## ✨ Key Features
- **Role-Based Access Control (RBAC):** Admin and Staff clearance using Laravel Gates (blocking access to branches, cost centers, and user management for non-admins).
- **Inflow & Outflow Tracking:** Patient OPD/consultation receipt generation with automated biller attribution and payment voucher logging.
- **Multi-Branch Operations:** Centralized filtering across hospital campuses and cost centers.
- **Reporting Engine:** Real-time consolidated Income vs. Expense financial summaries with streaming CSV exports.
- **Audit Trail:** Printable receipts and vouchers carrying unique transaction references and user timestamps.

## 🛠️ Tech Stack
- **Framework:** Laravel 12 (PHP)
- **Database:** MySQL
- **Frontend:** Blade, Tailwind CSS / Vanilla CSS
