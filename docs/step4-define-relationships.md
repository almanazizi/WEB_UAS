# Step 4: Tentukan Relasi

## 🎯 Tujuan
Mengidentifikasi dan mendefinisikan hubungan antar class dalam sistem.

---

## 🔗 Relationship Mapping

### **A. User Relationships**

#### User → Booking (One-to-Many)
- 1 User dapat membuat banyak Booking
- Relasi: `User.bookings()` → `Booking.user()`
- Tipe: **Association**
- Arrow: `User --> Booking`

#### User → Ticket (One-to-Many)
- 1 User dapat membuat banyak Ticket
- Relasi: `User.tickets()` → `Ticket.user()`
- Tipe: **Association**
- Arrow: `User --> Ticket`

#### User → Visitor (One-to-Many) - sebagai approver
- 1 Staff/Superadmin dapat approve banyak Visitor
- Relasi: `User.approvedVisitors()` → `Visitor.approver()`
- Tipe: **Association**
- Arrow: `User --> Visitor` (dengan label "approves")

---

### **B. Visitor Relationships**

#### Visitor → User (Many-to-One)
- Banyak Visitor di-approve oleh 1 User (staff)
- Foreign key: `approved_by`
- Relasi: `Visitor.approver()` → `User`
- Tipe: **Association**
- Arrow: `Visitor --> User`

---

### **C. Lab Relationships**

#### Lab → Asset (One-to-Many)
- 1 Lab memiliki banyak Asset
- Relasi: `Lab.assets()` → `Asset.lab()`
- Tipe: **Composition** (Asset tidak ada tanpa Lab)
- Arrow: `Lab *-- Asset`

#### Lab → Booking (One-to-Many)
- 1 Lab dapat di-booking berkali-kali
- Relasi: `Lab.bookings()` → `Booking.lab()`
- Tipe: **Association**
- Arrow: `Lab --> Booking`

---

### **D. Booking Relationships**

#### Booking → User (Many-to-One)
- Banyak Booking dibuat oleh 1 User
- Foreign key: `user_id`
- Relasi: `Booking.user()` → `User`
- Tipe: **Association**
- Arrow: `Booking --> User`

#### Booking → Lab (Many-to-One)
- Banyak Booking untuk 1 Lab
- Foreign key: `lab_id`
- Relasi: `Booking.lab()` → `Lab`
- Tipe: **Association**
- Arrow: `Booking --> Lab`

#### Booking → BookingVisitor (One-to-Many)
- 1 Booking dapat memiliki banyak BookingVisitor
- Relasi: `Booking.visitors()` → `BookingVisitor.booking()`
- Tipe: **Composition** (BookingVisitor tidak ada tanpa Booking)
- Arrow: `Booking *-- BookingVisitor`

---

### **E. Asset Relationships**

#### Asset → Lab (Many-to-One)
- Banyak Asset berada dalam 1 Lab
- Foreign key: `lab_id`
- Relasi: `Asset.lab()` → `Lab`
- Tipe: **Association**
- Arrow: `Asset --> Lab`

#### Asset → Ticket (One-to-Many)
- 1 Asset dapat memiliki banyak Ticket
- Relasi: `Asset.tickets()` → `Ticket.asset()`
- Tipe: **Association**
- Arrow: `Asset --> Ticket`

---

### **F. Ticket Relationships**

#### Ticket → User (Many-to-One)
- Banyak Ticket dibuat oleh 1 User
- Foreign key: `user_id`
- Relasi: `Ticket.user()` → `User`
- Tipe: **Association**
- Arrow: `Ticket --> User`

#### Ticket → Asset (Many-to-One)
- Banyak Ticket untuk 1 Asset
- Foreign key: `asset_id`
- Relasi: `Ticket.asset()` → `Asset`
- Tipe: **Association**
- Arrow: `Ticket --> Asset`

---

### **G. Controller Dependencies**

#### GuestVisitorController → Visitor
- Controller creates Visitor
- Tipe: **Dependency**
- Arrow: `GuestVisitorController ..> Visitor`

#### StaffDashboardController → Visitor
- Controller manages Visitor
- Tipe: **Dependency**
- Arrow: `StaffDashboardController ..> Visitor`

---

## 📊 Ringkasan Relasi

| From Class | Relationship Type | To Class | Multiplicity |
|------------|------------------|----------|--------------|
| User | Association | Booking | 1 to 0..* |
| User | Association | Ticket | 1 to 0..* |
| User | Association | Visitor | 1 to 0..* (as approver) |
| Visitor | Association | User | * to 0..1 (approved_by) |
| Lab | Composition | Asset | 1 to 1..* |
| Lab | Association | Booking | 1 to 0..* |
| Booking | Association | User | * to 1 |
| Booking | Association | Lab | * to 1 |
| Booking | Composition | BookingVisitor | 1 to 0..* |
| Asset | Association | Lab | * to 1 |
| Asset | Association | Ticket | 1 to 0..* |
| Ticket | Association | User | * to 1 |
| Ticket | Association | Asset | * to 1 |

---

## 💡 Penjelasan Tipe Relasi

### **Association (──>)**
- Hubungan umum "uses" atau "has reference to"
- Object bisa exist independent
- Contoh: User → Booking

### **Composition (◆──>)**
- "Part-of" relationship yang kuat
- Part tidak bisa exist tanpa whole
- Lifetime terikat
- Contoh: Lab ◆─> Asset, Booking ◆─> BookingVisitor

### **Dependency (..>)**
- Temporary "uses" relationship
- Controller menggunakan Model
- Contoh: Controller ..> Visitor
