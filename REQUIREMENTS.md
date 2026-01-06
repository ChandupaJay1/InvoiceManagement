# Project Requirements: Invoice Management System (Kirindiwela Weekly Fair)

## 1. Project Overview
A web-based application designed to manage stall (stole) rentals and invoice generation for a weekly fair (Pola). The system allows staff to book stalls, issue invoices, and print receipts, while administrators can monitor overall occupancy and revenue.

## 2. User Roles
### 2.1 Administrator
*   **Access**: Full system access.
*   **Capabilities**:
    *   View comprehensive Dashboard with real-time statistics.
    *   Monitor Stall Occupancy (Visual Grid).
    *   Manage Users (Staff members).
    *   View and Filter Invoice History (All users).
    *   Manage Subscription Plans.

### 2.2 Staff (User)
*   **Access**: Restricted to operational tasks.
*   **Capabilities**:
    *   Create new Invoices for stall rentals.
    *   View own Invoice History.
    *   Edit existing Invoices.
    *   Print Thermal Receipts.

## 3. Functional Requirements

### 3.1 Authentication & Authorization
*   Secure Login/Logout functionality.
*   Role-based access control (Admin vs. User).
*   Profile management (update details).

### 3.2 Stall (Stole) Management
*   **Inventory**: Fixed capacity of 100 Stalls (numbered 1-100).
*   **Availability Logic**:
    *   Stalls availability is **date-specific**.
    *   A stall booked for a specific date cannot be re-booked for the same date.
*   **Visual Interface**:
    *   Interactive grid for selection.
    *   Real-time status indication:
        *   **Available**: White/Selectable.
        *   **Taken**: Gray/Disabled (already booked for selected date).
        *   **Selected**: Highlighted (Indigo).

### 3.3 Invoice Processing
*   **Create Invoice**:
    *   **Inputs**: Customer Name, Contact Number, Date.
    *   **Stall Selection**: Select one or multiple stalls from the visual grid.
    *   **Pricing**: Automatic line item generation. Price entered per stall.
    *   **Currency**: Sri Lankan Rupees (Rs.).
*   **Edit Invoice**:
    *   Modify customer details or date.
    *   Add/Remove stalls (logic checks availability for new date).
*   **Logic**:
    *   Quantity is fixed to 1 per stall.
    *   "Total Amount" column is removed (redundant), only "Price" is shown.
    *   Subtotal calculated automatically.

### 3.4 Printing (Thermal Receipt)
*   **Format**: Optimized for **80mm Thermal Printers**.
*   **Content**:
    *   Header: "සති පොල කිරිදිවෙල Invoice" (Kirindiwela Weekly Fair Invoice).
    *   Metadata: Invoice Number, Date & **Time**, **Issued By** (Staff Name).
    *   Customer Details: Name, Phone.
    *   Line Items: Stall Number, Price.
    *   Footer: Total Amount.
*   **Styling**:
    *   Navigation and sidebars hidden during print.
    *   Black & white high-contrast text.
    *   No scrollbars or cutoff content.

### 3.5 Admin Dashboard
*   **Key Metrics**: Total Users, Total Invoices, Stoles Capacity vs. Paid Count, Total Collection.
*   **Stall Overview (Visual)**:
    *   Split view: Grid (Left) | Stats (Right).
    *   **Grid**: 100 Stalls (Pagination: 1-50, 51-100).
    *   **Color Code**: White (Paid), Dark Gray (Available/Unpaid).
    *   **Responsiveness**: Stacks vertically on mobile, side-by-side on desktop.
*   **Filtering**:
    *   **Invoice History**: Filter by **Staff Member** and **Date**. (Inputs validated to be of equal visual width).

## 4. Non-Functional Requirements
*   **Platform**: Web-based (PHP/Laravel).
*   **Database**: MySQL.
*   **Responsiveness**: Mobile-friendly UI (Tailwind CSS).
*   **Localization**:
    *   Currency: **Rs.**
    *   Labels: Mixed English and Sinhala (e.g., "මුදල", "කඩ අංකය").
    *   Timezone: Local time display.

## 5. UI/UX Standards
*   **Framework**: Tailwind CSS.
*   **Interactivity**: Alpine.js for dynamic modals (stall picker) and toggle logic.
*   **Aesthetics**: Clean, modern interface with consistent spacing and typography (Inter font).
