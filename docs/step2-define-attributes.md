# Step 2: Tentukan Attributes

## 🎯 Tujuan
Mendefinisikan atribut/field untuk setiap class yang teridentifikasi di Step 1.

---

## 📦 Attributes untuk Models

### **1. User**
```
- id: bigint
- name: string
- email: string
- password: string
- role: enum (user|staff|superadmin)
- email_verified_at: timestamp
- created_at: timestamp
- updated_at: timestamp
```

### **2. Visitor** ⭐
```
- id: bigint
- name: string
- email: string (nullable)
- phone: string
- purpose: text
- person_to_meet: string
- photo: string
- check_in_time: timestamp (nullable)
- check_out_time: timestamp (nullable)
- status: enum
- approved_by: bigint (FK to users, nullable)
- approved_at: timestamp (nullable)
- notes: text (nullable)
- created_at: timestamp
- updated_at: timestamp
```

### **3. Booking**
```
- id: bigint
- user_id: bigint (FK)
- lab_id: bigint (FK)
- start_time: datetime
- end_time: datetime
- purpose: text
- status: string
- created_at: timestamp
- updated_at: timestamp
```

### **4. BookingVisitor**
```
- id: bigint
- booking_id: bigint (FK)
- name: string
- email: string (nullable)
- phone: string
- created_at: timestamp
- updated_at: timestamp
```

### **5. Lab**
```
- id: bigint
- name: string
- capacity: integer
- location: string
- created_at: timestamp
- updated_at: timestamp
```

### **6. Asset**
```
- id: bigint
- lab_id: bigint (FK)
- code: string
- name: string
- spec: text
- condition: string
- created_at: timestamp
- updated_at: timestamp
```

### **7. Ticket**
```
- id: bigint
- user_id: bigint (FK)
- asset_id: bigint (FK)
- issue_description: text
- status: string
- created_at: timestamp
- updated_at: timestamp
```

---

## 💡 Catatan untuk Karya Ilmiah

Dalam class diagram final:
- **Timestamps** (created_at, updated_at) **bisa di-skip** jika tidak relevan
- Fokus pada **business attributes** yang penting
- **Foreign keys** ditunjukkan melalui relationship, tidak perlu duplikasi di attributes
- Gunakan **tipe data yang jelas**: string, integer, text, datetime, enum
