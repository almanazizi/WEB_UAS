# Step 1: Identifikasi Class Utama

## 🎯 Tujuan
Mengidentifikasi semua class penting yang akan ditampilkan dalam class diagram sistem Guest Visitor Management.

## 📦 Class yang Teridentifikasi

### **A. Models/Entities (7 class)**
Berdasarkan database tables:

1. **User** - Pengguna sistem (superadmin, staff, user)
2. **Visitor** - Data pengunjung tamu yang check-in
3. **Booking** - Reservasi laboratorium
4. **BookingVisitor** - Pengunjung dalam booking
5. **Lab** - Data laboratorium
6. **Asset** - Aset/peralatan dalam lab
7. **Ticket** - Tiket maintenance/kerusakan

### **B. Controllers (2 class utama untuk visitor)**
Berdasarkan business logic:

1. **GuestVisitorController** - Handle check-in tamu (public)
2. **StaffDashboardController** - Manage visitor oleh staff

### **C. Enumerations (5 enum)**
Berdasarkan status/role:

1. **UserRole** - user, staff, superadmin
2. **VisitorStatus** - pending, approved, active, completed, rejected
3. **BookingStatus** - pending, approved, rejected
4. **TicketStatus** - open, resolved
5. **AssetCondition** - good, damaged, maintenance

---

## 📋 Ringkasan

**Total Class:** 14
- Models: 7
- Controllers: 2
- Enums: 5

**Fokus Utama:** Visitor Management System dengan integrasi ke Lab Booking System
