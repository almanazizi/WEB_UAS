# Step 5: Tambahkan Multiplicity

## 🎯 Tujuan
Menentukan kardinalitas untuk setiap relasi antar class.

---

## 🔢 Multiplicity Reference

| Notasi | Arti | Contoh |
|--------|------|--------|
| `1` | Tepat satu | Setiap Booking punya tepat 1 User |
| `0..1` | Nol atau satu | Visitor bisa di-approve 0 atau 1 Staff |
| `*` atau `0..*` | Nol atau lebih | User bisa punya 0 atau banyak Booking |
| `1..*` | Satu atau lebih | Lab harus punya minimal 1 Asset |

---

## 📊 Multiplicity untuk Setiap Relasi

### **1. User ↔ Booking**
```
User "1" --> "0..*" Booking : creates
```
**Penjelasan:**
- 1 User dapat membuat 0 atau banyak Booking
- 1 Booking dibuat oleh tepat 1 User

**Cara Baca:** "One User creates zero to many Bookings"

---

### **2. User ↔ Ticket**
```
User "1" --> "0..*" Ticket : reports
```
**Penjelasan:**
- 1 User dapat melaporkan 0 atau banyak Ticket
- 1 Ticket dilaporkan oleh tepat 1 User

---

### **3. User ↔ Visitor (as approver)**
```
User "0..1" <-- "0..*" Visitor : approved_by
```
**Penjelasan:**
- 1 Staff/Superadmin dapat approve 0 atau banyak Visitor
- 1 Visitor di-approve oleh 0 atau 1 User (nullable)

**Catatan:** 0..1 karena visitor bisa belum di-approve (pending)

---

### **4. Lab ↔ Asset**
```
Lab "1" *-- "1..*" Asset : contains
```
**Penjelasan:**
- 1 Lab berisi minimal 1 Asset (atau lebih)
- 1 Asset berada dalam tepat 1 Lab

**Catatan:** Composition relationship (Asset tidak exist tanpa Lab)

---

### **5. Lab ↔ Booking**
```
Lab "1" --> "0..*" Booking : is_booked_by
```
**Penjelasan:**
- 1 Lab dapat di-booking 0 atau banyak kali
- 1 Booking untuk tepat 1 Lab

---

### **6. Booking ↔ User**
```
Booking "*" --> "1" User : created_by
```
**Penjelasan:**
- Banyak Booking dibuat oleh 1 User
- Setiap Booking punya tepat 1 creator

---

### **7. Booking ↔ Lab**
```
Booking "*" --> "1" Lab : reserves
```
**Penjelasan:**
- Banyak Booking untuk 1 Lab
- Setiap Booking untuk tepat 1 Lab

---

### **8. Booking ↔ BookingVisitor**
```
Booking "1" *-- "0..*" BookingVisitor : includes
```
**Penjelasan:**
- 1 Booking dapat memiliki 0 atau banyak BookingVisitor
- 1 BookingVisitor dalam tepat 1 Booking

**Catatan:** Composition (BookingVisitor tidak exist tanpa Booking)

---

### **9. Asset ↔ Lab**
```
Asset "*" --> "1" Lab : located_in
```
**Penjelasan:**
- Banyak Asset dalam 1 Lab
- Setiap Asset berada dalam tepat 1 Lab

---

### **10. Asset ↔ Ticket**
```
Asset "1" --> "0..*" Ticket : has_issues
```
**Penjelasan:**
- 1 Asset dapat memiliki 0 atau banyak Ticket
- 1 Ticket untuk tepat 1 Asset

---

### **11. Ticket ↔ User**
```
Ticket "*" --> "1" User : reported_by
```
**Penjelasan:**
- Banyak Ticket dilaporkan oleh 1 User
- Setiap Ticket dilaporkan oleh tepat 1 User

---

### **12. Ticket ↔ Asset**
```
Ticket "*" --> "1" Asset : concerns
```
**Penjelasan:**
- Banyak Ticket untuk 1 Asset
- Setiap Ticket terkait dengan tepat 1 Asset

---

## 📋 Tabel Ringkasan Multiplicity

| Relasi | Sisi A | Multiplicity | Sisi B | Tipe |
|--------|--------|--------------|--------|------|
| User - Booking | User | 1 | 0..* | Association |
| User - Ticket | User | 1 | 0..* | Association |
| User - Visitor | User | 0..1 | 0..* | Association |
| Lab - Asset | Lab | 1 | 1..* | Composition |
| Lab - Booking | Lab | 1 | 0..* | Association |
| Booking - BookingVisitor | Booking | 1 | 0..* | Composition |
| Asset - Ticket | Asset | 1 | 0..* | Association |

---

## 💡 Tips Menentukan Multiplicity

### **Pertanyaan untuk Sisi 1:**
"Berapa banyak [ClassB] yang dapat dimiliki 1 [ClassA]?"
- Jawaban: 0..1, 1, 0..*, 1..*, dll.

### **Pertanyaan untuk Sisi 2:**
"Berapa banyak [ClassA] yang terkait dengan 1 [ClassB]?"
- Jawaban: 0..1, 1, 0..*, 1..*, dll.

### **Contoh:**
"Berapa banyak Booking yang dapat dimiliki 1 User?"
- Jawaban: 0 atau banyak → `0..*`

"Berapa banyak User yang membuat 1 Booking?"
- Jawaban: Tepat 1 → `1`

**Result:** `User "1" --> "0..*" Booking`

---

## 🎯 Business Rules yang Mempengaruhi Multiplicity

1. **User → Booking** (0..*)
   - User belum tentu punya booking
   - User bisa punya banyak booking

2. **Lab → Asset** (1..*)
   - Lab HARUS punya minimal 1 asset
   - Tidak boleh ada lab kosong

3. **Visitor → User** (0..1 as approver)
   - Visitor bisa belum di-approve (pending)
   - Jika approved, pasti ada 1 approver
