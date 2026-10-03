# Shiv Aaradhana Private Limited — Staff Administration Manual

**Target Audience:** Super Administrators, Catalog Managers, Trade Desk Officers, and Content Editors.  
**Portal Access:** `https://shivaaradhana.com/admin/login` (or `http://localhost:8000/admin/login` in development)  

---

## 1. Administrative Roles & Permissions

| Role | Permitted Operations |
| :--- | :--- |
| **Super Administrator** | Universal administrative privileges: user accounts, system configuration, audit logs, catalog, and inquiries. |
| **Catalog Manager** | Add, edit, archive, and publish categories, product types, and export products. Manage specifications and media galleries. |
| **Inquiry Manager** | Review incoming trade inquiries, update progress status, add internal notes, and export CSV reports. |
| **Content Editor** | Edit corporate storytelling on homepage, heritage, mission/vision, and verified contact sections. |

---

## 2. Managing the Export Product Catalog

### Adding a New Export Commodity
1. Log in to the management suite and navigate to **Export Products** in the sidebar.
2. Click **+ Add Export Product**.
3. Select the parent **Commodity Category** (e.g. *Agro Products*) and specific **Product Line** (e.g. *Oilseeds*).
4. Enter the commercial product name (e.g. *Natural White Sesame Seeds (99/1)*), origin (default: *Gujarat, India*), and HS Code.
5. Provide a clear **Short Description** (appears in card listings) and comprehensive **Full Specification Description**.
6. Set commercial parameters:
   - **Harvest Season:** e.g. *October – January*
   - **Minimum Order Qty (MOQ):** e.g. *1 x 20ft FCL (19 MT)*
   - **Monthly Supply Capacity:** e.g. *500 MT / Month*
   - **Packaging Options:** e.g. *25kg PP bags, multi-wall paper bags with inner poly barrier*
7. Configure laboratory parameters in the **Quality Specifications Builder** (Purity, Moisture, Admixture, Oil Content, Sortex Grading).
   *Note: Only populated attributes will be rendered on the public website.*
8. Set the lifecycle status to **Published (Live on Catalog)** or **Draft (Private)**.
9. Click **Save & Publish Product**.

### Toggling Publication Status
Products can be toggled between **Draft** and **Published** with a single click from the products table using the **Publish / Unpublish** button.

---

## 3. Managing Buyer Inquiries & Quotation Leads

### Reviewing an Incoming Inquiry
1. Navigate to **Buyer Inquiries** in the sidebar.
2. Filter leads by status tabs: **New**, **In Progress**, **Responded**, or **Closed**.
3. Click **Review &rarr;** on any inquiry to open its comprehensive dossier.
4. Review the buyer's company name, email, WhatsApp number, destination country, requested commodity, volume, and target discharge port.

### Updating Status & Recording Activities
1. In the right panel of the inquiry page, select the new status from the dropdown:
   - **In Progress:** Quotation being prepared or supplier mandi pricing being evaluated.
   - **Responded:** Formal Proforma Invoice or CIF quote sent to buyer.
   - **Closed:** Consignment agreed or deal concluded.
   - **Spam:** Disqualified automated submission.
2. Click **Update Status**.
3. Under **Internal Follow-up & Audit Trail**, enter any relevant operational notes (e.g., *“Dispatched 500g Sortex seed sample via DHL tracking #8491029”*) and click **Add Internal Note**.

### Exporting Inquiries to CSV
Click **Export CSV Report** on the top right of the Inquiries screen to generate an immediate spreadsheet download for executive review or CRM synchronization.

---

## 4. Editing Landing Page Content (CMS)

Authorized editors can update corporate storytelling sections without modifying code:
1. Navigate to **CMS Sections** in the sidebar.
2. Choose the target section (e.g., *Hero*, *About Heritage*, *Mission & Vision*, or *Sourcing Process*).
3. Click **Edit Content &rarr;**.
4. Update the title, subtitle, or narrative body.
5. Click **Update Section Content**. The public website will immediately reflect the changes.

---

## 5. Security & Audit Trail

Super Administrators can inspect the **Audit Trail** (`/admin/audit-logs`) to monitor administrative operations, including staff logins, product creations, status modifications, and data exports, complete with timestamps and originating IP addresses.
