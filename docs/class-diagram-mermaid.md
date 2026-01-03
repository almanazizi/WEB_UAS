# Class Diagram - Mermaid Format (Karya Ilmiah)

> **Sistem:** SIM-LAB - Guest Visitor Management  
> **Dibuat mengikuti:** 6 Langkah Pembuatan Class Diagram  
> **Format:** Mermaid.js - Compatible with GitHub, GitLab, Notion, Obsidian

---

## 📊 Class Diagram Lengkap

```mermaid
classDiagram
    %% ===== STEP 1 & 2 & 3: CLASS DEFINITION dengan Attributes dan Methods =====
    
    %% ===== MODELS/ENTITIES =====
    class User {
        -bigint id
        -string name
        -string email
        -string password
        -enum role
        +bookings() HasMany
        +tickets() HasMany
        +approvedVisitors() HasMany
        +isSuperadmin() bool
        +isStaff() bool
        +isUser() bool
    }
    
    class Visitor {
        -bigint id
        -string name
        -string email
        -string phone
        -text purpose
        -string person_to_meet
        -string photo
        -timestamp check_in_time
        -timestamp check_out_time
        -enum status
        -bigint approved_by
        -timestamp approved_at
        -text notes
        +approver() BelongsTo
        +getPhotoUrlAttribute() string
        +scopePending() Builder
        +scopeApproved() Builder
        +scopeActive() Builder
        +isPending() bool
        +isApproved() bool
        +isActive() bool
        +isCompleted() bool
    }
    
    class Booking {
        -bigint id
        -bigint user_id
        -bigint lab_id
        -datetime start_time
        -datetime end_time
        -text purpose
        -string status
        +user() BelongsTo
        +lab() BelongsTo
        +visitors() HasMany
        +scopeActive() Builder
        +scopePending() Builder
        +scopeApproved() Builder
    }
    
    class BookingVisitor {
        -bigint id
        -bigint booking_id
        -string name
        -string email
        -string phone
        +booking() BelongsTo
    }
    
    class Lab {
        -bigint id
        -string name
        -integer capacity
        -string location
        +assets() HasMany
        +bookings() HasMany
    }
    
    class Asset {
        -bigint id
        -bigint lab_id
        -string code
        -string name
        -text spec
        -string condition
        +lab() BelongsTo
        +tickets() HasMany
    }
    
    class Ticket {
        -bigint id
        -bigint user_id
        -bigint asset_id
        -text issue_description
        -string status
        +user() BelongsTo
        +asset() BelongsTo
        +scopeOpen() Builder
        +scopeResolved() Builder
    }
    
    %% ===== CONTROLLERS =====
    class GuestVisitorController {
        <<Controller>>
        +showCheckInForm() View
        +store(Request) RedirectResponse
        -storePhoto(photo) string
        -notifyStaff(visitor) void
    }
    
    class StaffDashboardController {
        <<Controller>>
        +index(Request) View
        +notifications() View
        +visitorDetail(id) View
        +approve(id) RedirectResponse
        +reject(id) RedirectResponse
        +checkOutVisitor(id) RedirectResponse
        +exportExcel() Download
        +exportPDF() Download
    }
    
    %% ===== ENUMERATIONS =====
    class UserRole {
        <<enumeration>>
        user
        staff
        superadmin
    }
    
    class VisitorStatus {
        <<enumeration>>
        pending
        approved
        active
        completed
        rejected
    }
    
    class BookingStatus {
        <<enumeration>>
        pending
        approved
        rejected
    }
    
    class TicketStatus {
        <<enumeration>>
        open
        resolved
    }
    
    class AssetCondition {
        <<enumeration>>
        good
        damaged
        maintenance
    }
    
    %% ===== STEP 4 & 5: RELATIONSHIPS dengan MULTIPLICITY =====
    
    %% User Relationships
    User "1" --> "0..*" Booking : creates
    User "1" --> "0..*" Ticket : reports
    User "0..1" <-- "0..*" Visitor : approves
    
    %% Lab Relationships
    Lab "1" *-- "1..*" Asset : contains
    Lab "1" --> "0..*" Booking : is_booked_by
    
    %% Booking Relationships
    Booking "*" --> "1" User : created_by
    Booking "*" --> "1" Lab : reserves
    Booking "1" *-- "0..*" BookingVisitor : includes
    
    %% Asset Relationships
    Asset "*" --> "1" Lab : located_in
    Asset "1" --> "0..*" Ticket : has_issues
    
    %% Ticket Relationships
    Ticket "*" --> "1" User : reported_by
    Ticket "*" --> "1" Asset : concerns
    
    %% Controller Dependencies
    GuestVisitorController ..> Visitor : creates
    StaffDashboardController ..> Visitor : manages
    
    %% Enum Associations
    User --> UserRole : has
    Visitor --> VisitorStatus : has
    Booking --> BookingStatus : has
    Ticket --> TicketStatus : has
    Asset --> AssetCondition : has
    
    %% ===== NOTES =====
    note for Visitor "Status Lifecycle:\npending → approved → active → completed\nMust be approved by Staff/Superadmin"
    note for User "Roles: user, staff, superadmin\nDifferent access levels per role"
    note for GuestVisitorController "Public access - No authentication required"
```

---

## 📐 Versi Minimal (Fokus Visitor Management)

Jika diagram terlalu kompleks untuk laporan, gunakan versi minimal ini:

```mermaid
classDiagram
    %% ===== FOKUS VISITOR MANAGEMENT =====
    
    class User {
        -bigint id
        -string name
        -string email
        -enum role
        +approvedVisitors() HasMany
        +isStaff() bool
    }
    
    class Visitor {
        -bigint id
        -string name
        -string phone
        -text purpose
        -string photo
        -enum status
        -bigint approved_by
        +approver() BelongsTo
        +isPending() bool
        +isApproved() bool
    }
    
    class GuestVisitorController {
        <<Controller>>
        +showCheckInForm() View
        +store(Request) RedirectResponse
    }
    
    class StaffDashboardController {
        <<Controller>>
        +index() View
        +approve(id) RedirectResponse
        +reject(id) RedirectResponse
    }
    
    class VisitorStatus {
        <<enumeration>>
        pending
        approved
        active
        completed
        rejected
    }
    
    %% Relationships
    User "0..1" <-- "0..*" Visitor : approves
    Visitor --> VisitorStatus : has
    GuestVisitorController ..> Visitor : creates
    StaffDashboardController ..> Visitor : manages
    
    note for Visitor "Guest check-in tanpa login,\nmenunggu approval dari Staff"
```

---

## 🎨 Cara Menggunakan

### **1. GitHub Markdown**
Copy code mermaid ke dalam file `.md`:

````markdown
```mermaid
classDiagram
    class User {
        -id: bigint
        +getName() string
    }
```
````

### **2. Mermaid Live Editor**
1. Buka https://mermaid.live/
2. Paste code
3. Export ke PNG/SVG

### **3. VS Code**
1. Install extension "Markdown Preview Mermaid Support"
2. Preview file `.md`

### **4. Notion/Obsidian**
Paste dalam code block dengan language `mermaid`

---

## 📖 Penjelasan Notasi Mermaid

| Notasi | Arti UML | Contoh |
|--------|----------|--------|
| `-->` | Association | `User --> Booking` |
| `*--` | Composition | `Lab *-- Asset` |
| `o--` | Aggregation | `Department o-- Employee` |
| `<\|--` | Inheritance | `Staff <\|-- User` |
| `..\|>` | Realization | `User ..\|> Authenticatable` |
| `..>` | Dependency | `Controller ..> Model` |

---

## 📋 Multiplicity dalam Mermaid

```mermaid
User "1" --> "0..*" Booking
     ↑              ↑
   sisi A        sisi B
```

Format: `ClassA "multiplicity_A" --> "multiplicity_B" ClassB : label`

---

## 💾 Export untuk Laporan

### **Format PNG (300 DPI):**
1. Buka Mermaid Live Editor
2. Paste code
3. Click "PNG" → Download
4. Gunakan dalam Word/LaTeX

### **Format SVG (Vector):**
- Click "SVG" → Download
- Cocok untuk scaling tanpa blur

### **Format PDF:**
- Export SVG → Convert to PDF
- Atau screenshot dengan resolusi tinggi

---

## 📝 Caption untuk Laporan

**Bahasa Indonesia:**
```
Gambar 3.1 Class Diagram Sistem Guest Visitor Management
```

**Bahasa Inggris:**
```
Figure 3.1 Class Diagram of Guest Visitor Management System
```

---

## ✅ Checklist Kualitas

- [x] Step 1: Class teridentifikasi (Models, Controllers, Enums)
- [x] Step 2: Attributes lengkap dengan tipe data
- [x] Step 3: Methods public yang meaningful
- [x] Step 4: Relationships defined (association, composition, dependency)
- [x] Step 5: Multiplicity tercantum pada setiap relasi
- [x] Step 6: Layout terorganisir (grouping by type)
- [x] Visibility modifiers benar (+, -, #)
- [x] Stereotypes untuk Controller dan Enum
- [x] Notes menjelaskan business rules
- [x] Consistent naming convention

---

## 🎓 Untuk Karya Ilmiah

**Yang Harus Ada:**
1. ✅ Judul/Caption
2. ✅ Nomor gambar (Gambar X.X)
3. ✅ Sumber (jika dari referensi)
4. ✅ Penjelasan dalam teks BAB III
5. ✅ Resolusi tinggi (300 DPI untuk print)

**Tips:**
- Gunakan **versi minimal** jika diagram terlalu kompleks
- Fokus pada **modul yang dibahas** (Visitor Management)
- Tambahkan **notes** untuk menjelaskan lifecycle/rules
- **Referensikan** dalam teks: "seperti ditunjukkan pada Gambar 3.1..."

---

**Diagram ini sudah mengikuti 6 langkah pembuatan dan standar UML untuk karya ilmiah! 🎯**
